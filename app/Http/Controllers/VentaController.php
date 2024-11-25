<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Cliente;

class VentaController extends Controller
{
        // Listar todas las ventas con estado = 1
        public function index()
        {
            $ventas = Venta::with('cliente')->where('estado', 1)->get();
            return response()->json($ventas, 200);
        }
    
        // Crear una nueva venta con estado = 1 automáticamente
        public function store(Request $request)
        {
            $request->validate([
                'cliente_id' => 'required|exists:cliente,id',
                'total' => 'required|numeric|min:0',
                'fecha' => 'required|date',
            ]);
    
            // Crear la venta con estado = 1
            $data = $request->all();
            $data['estado'] = 1; // Estado por defecto

            $venta = Venta::create($data);
    
            return response()->json(['message' => 'Venta creada con éxito', 'data' => $venta], 201);
        }
    
        // Mostrar una venta específica por ID
        public function show($id)
        {
            $venta = Venta::with('cliente')->find($id);
    
            if (!$venta || $venta->estado != 1) {
                return response()->json(['message' => 'Venta no encontrada o deshabilitada'], 404);
            }
    
            return response()->json($venta, 200);
        }
    
        // Actualizar una venta
        public function update(Request $request, $id)
        {
            $venta = Venta::find($id);
    
            if (!$venta || $venta->estado != 1) {
                return response()->json(['message' => 'Venta no encontrada o deshabilitada'], 404);
            }
    
            $request->validate([
                'cliente_id' => 'sometimes|required|exists:clientes,id',
                'total' => 'sometimes|required|numeric|min:0',
                'fecha' => 'sometimes|required|date',
            ]);
    
            $venta->update($request->all());
    
            return response()->json(['message' => 'Venta actualizada con éxito', 'data' => $venta], 200);
        }
    
        // Cambiar el estado de la venta a 0 en lugar de eliminarla
        public function destroy($id)
        {
            $venta = Venta::find($id);
    
            if (!$venta || $venta->estado != 1) {
                return response()->json(['message' => 'Venta no encontrada o ya deshabilitada'], 404);
            }
    
            // Cambiar el estado a 0
            $venta->estado = 0;
            $venta->save();
    
            return response()->json(['message' => 'Venta deshabilitada con éxito'], 200);
        }

        public function ventasPorCliente($clienteId)
        {
            $ventas = Venta::where('cliente_id', $clienteId)->get();
            return response()->json($ventas);
        }
    }