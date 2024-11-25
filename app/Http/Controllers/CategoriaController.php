<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    // Listar todas las categorías
    public function index()
    {
        // return response()->json(Categoria::all(), 200);

        // Filtrar solo categorías con estado = 1
        $categorias = Categoria::where('estado', 1)->get();

        return response()->json($categorias, 200);
    }

    // Crear una nueva categoría
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        // Agregar el estado automáticamente al crear
        $data = $request->all();
        $data['estado'] = 1; // Estado por defecto

        $categoria = Categoria::create($data);

        return response()->json(['message' => 'Categoría creada con éxito', 'data' => $categoria], 201);
    }

    // Mostrar una categoría por ID
    public function show($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        return response()->json($categoria, 200);
    }

    // Actualizar una categoría
    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'sometimes|required|boolean',
        ]);

        $categoria->update($request->all());

        return response()->json(['message' => 'Categoría actualizada con éxito', 'data' => $categoria], 200);
    }

    // Eliminar una categoría
    public function destroy($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        // $categoria->delete();
        $categoria->estado = 0;
        $categoria->save();

        return response()->json(['message' => 'Categoría eliminada con éxito'], 200);
    }
}
