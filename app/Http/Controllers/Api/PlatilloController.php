<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Platillo;
use Illuminate\Http\Request;

class PlatilloController extends Controller
{
    public function index()
    {
        $platillos = Platillo::with('categoria')->get();

        return response()->json($platillos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|string',
            'disponible' => 'boolean'
        ]);

        $platillo = Platillo::create($request->all());

        return response()->json([
            'mensaje' => 'Platillo registrado correctamente',
            'platillo' => $platillo
        ], 201);
    }

    public function show(string $id)
    {
        $platillo = Platillo::with('categoria')->find($id);

        if (!$platillo) {
            return response()->json([
                'mensaje' => 'Platillo no encontrado'
            ], 404);
        }

        return response()->json($platillo);
    }

    public function update(Request $request, string $id)
    {
        $platillo = Platillo::find($id);

        if (!$platillo) {
            return response()->json([
                'mensaje' => 'Platillo no encontrado'
            ], 404);
        }

        $request->validate([
            'categoria_id' => 'sometimes|required|exists:categorias,id',
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'sometimes|required|numeric|min:0',
            'imagen' => 'nullable|string',
            'disponible' => 'boolean'
        ]);

        $platillo->update($request->all());

        return response()->json([
            'mensaje' => 'Platillo actualizado correctamente',
            'platillo' => $platillo
        ]);
    }

    public function destroy(string $id)
    {
        $platillo = Platillo::find($id);

        if (!$platillo) {
            return response()->json([
                'mensaje' => 'Platillo no encontrado'
            ], 404);
        }

        $platillo->delete();

        return response()->json([
            'mensaje' => 'Platillo eliminado correctamente'
        ]);
    }
}