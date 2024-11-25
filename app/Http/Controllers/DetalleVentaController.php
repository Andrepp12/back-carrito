<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    // Listar todos los detalles de venta con estado = 1
    public function index()
    {
        $detallesVenta = DetalleVenta::where('estado', 1)
        ->with(['venta', 'producto']) // Cargar cliente y producto
        ->get();
        return response()->json($detallesVenta, 200);
    }

    // Crear un nuevo detalle de venta con estado = 1 automáticamente
    public function store(Request $request)
    {
        $request->validate([
            'venta_id' => 'required|exists:venta,id',
            'producto_id' => 'required|exists:producto,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $detalleVenta = DetalleVenta::create([
            'venta_id' => $request->venta_id,
            'producto_id' => $request->producto_id,
            'cantidad' => $request->cantidad,
            'precio_unitario' => $request->precio_unitario,
            'subtotal' => $request->subtotal,
            'estado' => 1, // Estado por defecto
        ]);

        return response()->json(['message' => 'Detalle de venta creado con éxito', 'data' => $detalleVenta], 201);
    }

    // Mostrar un detalle de venta específico por ID
    public function show($id)
    {
        $detalleVenta = DetalleVenta::where('estado', 1)
        ->with(['venta', 'producto']) // Cargar cliente y producto
        ->where('id', $id)
        ->first();

        if (!$detalleVenta) {
            return response()->json(['message' => 'Detalle de venta no encontrado o deshabilitado'], 404);
        }

        return response()->json($detalleVenta, 200);
    }

    // Actualizar un detalle de venta
    public function update(Request $request, $id)
    {
        $detalleVenta = DetalleVenta::where('id', $id)->where('estado', 1)->first();

        if (!$detalleVenta) {
            return response()->json(['message' => 'Detalle de venta no encontrado o deshabilitado'], 404);
        }

        $request->validate([
            'venta_id' => 'sometimes|required|exists:venta,id',
            'producto_id' => 'sometimes|required|exists:producto,id',
            'cantidad' => 'sometimes|required|integer|min:1',
            'precio_unitario' => 'sometimes|required|numeric|min:0',
            'subtotal' => 'sometimes|required|numeric|min:0',
        ]);

        $detalleVenta->update($request->all());

        return response()->json(['message' => 'Detalle de venta actualizado con éxito', 'data' => $detalleVenta], 200);
    }

    // Deshabilitar un detalle de venta (cambiar estado a 0 en lugar de eliminar)
    public function destroy($id)
    {
        $detalleVenta = DetalleVenta::where('id', $id)->where('estado', 1)->first();

        if (!$detalleVenta) {
            return response()->json(['message' => 'Detalle de venta no encontrado o ya deshabilitado'], 404);
        }

        $detalleVenta->estado = 0;
        $detalleVenta->save();

        return response()->json(['message' => 'Detalle de venta deshabilitado con éxito'], 200);
    }

    public function detallesPorVenta($ventaId)
        {
            $detalleVenta = DetalleVenta::with('producto')->where('venta_id', $ventaId)->get();
            return response()->json($detalleVenta);
        }
}

