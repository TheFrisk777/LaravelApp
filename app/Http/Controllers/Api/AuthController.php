<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::with('role')
            ->where('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.']
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('ionic-app')->plainTextToken;

        return response()->json([
            'mensaje' => 'Inicio de sesión correcto',
            'token' => $token,
            'usuario' => [
                'id' => $user->id,
                'nombre' => $user->name,
                'email' => $user->email,
                'rol' => $user->role?->nombre
            ]
        ]);
    }

    public function me(Request $request)
    {
        return response()->json(
            $request->user()->load('role')
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'mensaje' => 'Sesión cerrada correctamente'
        ]);
    }
}