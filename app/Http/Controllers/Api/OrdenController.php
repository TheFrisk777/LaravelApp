<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Orden;
use App\Models\OrdenDetalle;
use App\Models\Platillo;
use App\Models\Mesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenController extends Controller
{
    public function index()
    {
        $ordenes = Orden::with([
            'mesa',
            'user',
            'detalles.platillo'
        ])
        ->latest()
        ->get();

        return response()->json($ordenes);
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'mesa_id' => 'required|exists:mesas,id',

            'items' => 'required|array|min:1',

            'items.*.platillo_id' => 'required|exists:platillos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.notas' => 'nullable|string|max:500'
        ]);

        $orden = DB::transaction(function () use ($request, $datos) {

            $orden = Orden::create([
                'mesa_id' => $datos['mesa_id'],

                // El usuario sale directamente del token Sanctum
                'user_id' => $request->user()->id,

                'estado' => 'pendiente',

                'total' => 0
            ]);

            $total = 0;

            foreach ($datos['items'] as $item) {

                $platillo = Platillo::where('disponible', true)
                    ->findOrFail($item['platillo_id']);

                $precio = $platillo->precio;

                $subtotal = $precio * $item['cantidad'];

                $total += $subtotal;

                OrdenDetalle::create([
                    'orden_id' => $orden->id,
                    'platillo_id' => $platillo->id,
                    'cantidad' => $item['cantidad'],
                    'precio' => $precio,
                    'notas' => $item['notas'] ?? null
                ]);
            }

            $orden->update([
                'total' => $total
            ]);

            Mesa::where('id', $datos['mesa_id'])
                ->update([
                    'estado' => 'ocupada'
                ]);

            return $orden;
        });

        return response()->json([
            'mensaje' => 'Orden registrada correctamente',

            'orden' => $orden->load([
                'mesa',
                'user',
                'detalles.platillo'
            ])
        ], 201);
    }

    public function show(Orden $orden)
    {
        return response()->json(
            $orden->load([
                'mesa',
                'user',
                'detalles.platillo'
            ])
        );
    }

    public function updateEstado(Request $request, Orden $orden)
    {
        $datos = $request->validate([
            'estado' => 'required|in:pendiente,preparando,listo,entregado,cancelada'
        ]);

        $orden->update([
            'estado' => $datos['estado']
        ]);

        return response()->json([
            'mensaje' => 'Estado actualizado correctamente',
            'orden' => $orden
        ]);
    }
}