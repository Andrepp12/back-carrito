<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Listar todos los productos
    public function index()
    {
        $productos = Producto::with('categoria')->where('estado', 1)->get();

        return response()->json($productos, 200);
    }

    // Crear un nuevo producto
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categoria,id',
            // 'estado' => 'required|boolean',
        ]);

        $data = $request->all();
        $data['estado'] = 1; // Estado por defecto

        if ($request->hasFile('imagen')) {
            // Guardar la imagen en el directorio `public/uploads`
            $path = $request->file('imagen')->store('uploads', 'public');
            $data['imagen'] = $path;
        }
        

        $producto = Producto::create($data);

        return response()->json(['message' => 'Producto creado con éxito', 'data' => $producto], 201);
    }

    // Mostrar un producto específico
    public function show($id)
    {
        $producto = Producto::with('categoria')->where('estado',1)->where('id', $id)->first();

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json($producto, 200);
    }

    // Actualizar un producto
    public function update(Request $request, $id)
    {
        $producto = Producto::where('id', $id)->where('estado', 1)->first();

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'categoria_id' => 'sometimes|required|exists:categoria,id',
            'estado' => 'sometimes|required|boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior, si existe
            if ($producto->imagen && Storage::exists('public/' . $producto->imagen)) {
                Storage::delete('public/' . $producto->imagen);
            }
    
            // Guardar la nueva imagen
            $path = $request->file('imagen')->store('uploads', 'public');
            $data['imagen'] = $path;
        }
    

        $producto->update($data);

        return response()->json(['message' => 'Producto actualizado con éxito', 'data' => $producto], 200);
    }

    // Eliminar un producto
    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $producto->update(['estado' => 0]); // Cambiar el estado a desactivado

        return response()->json(['message' => 'Producto desactivado con éxito'], 200);
    }
}
