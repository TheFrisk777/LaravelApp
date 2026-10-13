<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'mensaje' => 'No autenticado'
            ], 401);
        }

        $rol = $user->role?->nombre;

        if (!in_array($rol, $roles)) {
            return response()->json([
                'mensaje' => 'No tienes permiso para realizar esta acción'
            ], 403);
        }

        return $next($request);
    }
}