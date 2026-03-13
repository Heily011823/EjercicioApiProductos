<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request){

        // Validar datos
        $validador = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validador->fails()){
            return response()->json([
                'errors' => $validador->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        // Verificar credenciales
        if(!Auth::attempt($credentials)){
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $user = Auth::user();

        // Generar código de verificación de 6 dígitos
        $codigo = rand(100000,999999);

        // Guardar código y expiración
        $user->verification_code = $codigo;
        $user->code_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // Enviar correo con el código
        Mail::raw("Tu código de verificación es: $codigo", function($message) use ($user){

            $message->to($user->email)
                    ->subject('Código de verificación ApiProductos');

        });

        return response()->json([
            'message' => 'Se envió un código de verificación a tu correo'
        ]);
    }


    public function verifyCode(Request $request)
    {

        $validador = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required'
        ]);

        if($validador->fails()){
            return response()->json([
                'errors' => $validador->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        if ($user->verification_code != $request->code) {
            return response()->json([
                'message' => 'Código incorrecto'
            ], 401);
        }

        if (now()->greaterThan($user->code_expires_at)) {
            return response()->json([
                'message' => 'El código ha expirado'
            ], 401);
        }

        // Generar token JWT
        $token = JWTAuth::fromUser($user);

        // Limpiar el código
        $user->verification_code = null;
        $user->save();

        return response()->json([
            'message' => 'Código verificado correctamente',
            'token' => $token,
            'user' => $user
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

        } catch (\Exception $e) {

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

        } catch (\Exception $e) {

            return response()->json([
                'error' => 'No se pudo renovar el token'
            ], 500);
        }
    }

}