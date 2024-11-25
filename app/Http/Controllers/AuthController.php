<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:cliente,correo',
            'password' => 'required|min:8|confirmed', // password_confirmation también debe enviarse
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
        ]);

        // Crear un nuevo cliente
        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password), // Encriptar la contraseña
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'estado' => 1, // Asignar un estado por defecto
        ]);

        // Opcional: autenticar automáticamente al cliente después del registro
        Auth::login($cliente);

        return response()->json(['message' => 'Registro exitoso', 'user' => $cliente], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        // Buscar el cliente por correo
        $cliente = Cliente::where('correo', $request->correo)->first();

        if (!$cliente || !Hash::check($request->password, $cliente->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        // Autenticación exitosa
        Auth::login($cliente);
        return response()->json(['message' => 'Login exitoso', 'user' => $cliente], 200);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logout exitoso'], 200);
    }

    public function me()
    {
        // Obtener el cliente autenticado
        $cliente = Auth::user();

        // Verificar si hay un cliente autenticado
        if (!$cliente) {
            return response()->json(['message' => 'No estás autenticado'], 401);
        }

        // Retornar los datos del cliente autenticado
        return response()->json(['user' => $cliente], 200);
    }
}
