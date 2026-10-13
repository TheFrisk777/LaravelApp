<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Orden;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with([
            'orden.mesa'
        ])
        ->latest()
        ->get();

        return response()->json($pagos);
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'orden_id' => 'required|exists:ordenes,id',
            'metodo' => 'required|in:efectivo,tarjeta'
        ]);

        $pago = DB::transaction(function () use ($datos) {

            $orden = Orden::lockForUpdate()
                ->findOrFail($datos['orden_id']);

            if ($orden->estado !== 'entregado') {
                throw ValidationException::withMessages([
                    'orden_id' => [
                        'La orden debe estar entregada antes de realizar el pago.'
                    ]
                ]);
            }

            $yaPagada = Pago::where('orden_id', $orden->id)
                ->where('estado', 'pagado')
                ->exists();

            if ($yaPagada) {
                throw ValidationException::withMessages([
                    'orden_id' => [
                        'Esta orden ya fue pagada.'
                    ]
                ]);
            }

            $pago = Pago::create([
                'orden_id' => $orden->id,
                'metodo' => $datos['metodo'],

                // El monto viene del servidor, no del cliente
                'monto' => $orden->total,

                'estado' => 'pagado'
            ]);

            $orden->update([
                'estado' => 'cerrada'
            ]);

            $orden->mesa()->update([
                'estado' => 'disponible'
            ]);

            return $pago;
        });

        return response()->json([
            'mensaje' => 'Pago registrado correctamente',
            'pago' => $pago->load('orden.mesa')
        ], 201);
    }
}