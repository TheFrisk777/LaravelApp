<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MesaController extends Controller
{
    public function index()
    {
        return response()->json(
            Mesa::orderBy('numero')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|integer|min:1|unique:mesas,numero',
            'estado' => [
                'required',
                Rule::in([
                    'disponible',
                    'ocupada',
                    'reservada',
                    'fuera_servicio'
                ])
            ]
        ]);

        $mesa = Mesa::create($request->all());

        return response()->json([
            'mensaje' => 'Mesa registrada correctamente',
            'mesa' => $mesa
        ], 201);
    }

    public function show(string $id)
    {
        $mesa = Mesa::find($id);

        if (!$mesa) {
            return response()->json([
                'mensaje' => 'Mesa no encontrada'
            ], 404);
        }

        return response()->json($mesa);
    }

    public function update(Request $request, string $id)
    {
        $mesa = Mesa::find($id);

        if (!$mesa) {
            return response()->json([
                'mensaje' => 'Mesa no encontrada'
            ], 404);
        }

        $request->validate([
            'numero' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                Rule::unique('mesas', 'numero')->ignore($mesa->id)
            ],

            'estado' => [
                'sometimes',
                'required',
                Rule::in([
                    'disponible',
                    'ocupada',
                    'reservada',
                    'fuera_servicio'
                ])
            ]
        ]);

        $mesa->update($request->all());

        return response()->json([
            'mensaje' => 'Mesa actualizada correctamente',
            'mesa' => $mesa
        ]);
    }

    public function destroy(string $id)
    {
        $mesa = Mesa::find($id);

        if (!$mesa) {
            return response()->json([
                'mensaje' => 'Mesa no encontrada'
            ], 404);
        }

        $mesa->delete();

        return response()->json([
            'mensaje' => 'Mesa eliminada correctamente'
        ]);
    }
}