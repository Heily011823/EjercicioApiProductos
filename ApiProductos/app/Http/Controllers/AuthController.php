<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){

        $validador = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validador->fails()){
            return response()->json(['errors' => $validador->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if(! $token = JWTAuth::attempt($credentials)){
            return response()->json(['message' => 'Credenciales invalidas'], 401);
        }

        return response()->json([
            'message' => 'Login Correcto',
            'token' => $token,
            'user' => auth('api')->user(),
        ]);
    }

    public function me(){
        return response()->json(auth('api')->user());
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'Sesión cerrada correctamente'
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'No se pudo cerrar sesión'
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return response()->json([
                'message' => 'Token renovado correctamente',
                'token' => $newToken,
                'user' => auth('api')->user(),
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'No se pudo renovar el token'
            ], 500);
        }
    }
}
