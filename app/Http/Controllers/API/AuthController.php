<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json($user);
    }



    public function login(Request $request)
{
    $request->validate([
        'email' => 'email|required',
        'password' => 'required'
    ]);

    $credentials = request(['email', 'password']);
    if (!auth()->attempt($credentials)) {
        return response()->json([
            'message' => 'Las credenciales no son validas',
            'errors' => [
                'password' => [
                    'Las credenciales no son validas'
                ],
            ]
        ], 422);
    }

    $user = User::where('email', $request->email)->first();
    $authToken = $user->createToken('auth-token')->plainTextToken;

    return response()->json([
        'access_token' => $authToken,
    ]);
}
}
