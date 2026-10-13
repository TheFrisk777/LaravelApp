<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        return response()->json(
            Categoria::with('platillos')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        $categoria = Categoria::create($request->all());

        return response()->json([
            'mensaje' => 'Categoría registrada correctamente',
            'categoria' => $categoria
        ], 201);
    }

    public function show(string $id)
    {
        $categoria = Categoria::with('platillos')->find($id);

        if (!$categoria) {
            return response()->json([
                'mensaje' => 'Categoría no encontrada'
            ], 404);
        }

        return response()->json($categoria);
    }

    public function update(Request $request, string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'mensaje' => 'Categoría no encontrada'
            ], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        $categoria->update($request->all());

        return response()->json([
            'mensaje' => 'Categoría actualizada correctamente',
            'categoria' => $categoria
        ]);
    }

    public function destroy(string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'mensaje' => 'Categoría no encontrada'
            ], 404);
        }

        if ($categoria->platillos()->exists()) {
            return response()->json([
                'mensaje' => 'No se puede eliminar una categoría que tenga platillos'
            ], 409);
        }

        $categoria->delete();

        return response()->json([
            'mensaje' => 'Categoría eliminada correctamente'
        ]);
    }
}