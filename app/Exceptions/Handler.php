<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        // Devuelve una respuesta JSON personalizada si la solicitud espera JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Debes iniciar sesión para acceder a esta ruta.',
            ], 401);
        }

        // Para solicitudes no JSON, redirige a la página de login (opcional)
        return redirect()->guest(route('login'));
    }

}
