<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class ApiAuth extends Controller
{
    public function generate_token(Request $request){

        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'password' => [
                'required',
                'max:255',
            ]
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json([
                "Erro" => "Usuário incorreto"
            ]);
        } else if(!Hash::check($request->password, $user->password)){
            return response()->json([
                "Erro" => "Senha incorreta"
            ]);
        }

        $user->tokens()->delete();

        return response()->json([
            "token" => $user->createToken("Default")->plainTextToken
        ]);
    }
}
