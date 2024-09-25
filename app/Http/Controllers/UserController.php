<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request){
        $request ->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|string|max:200|email|unique:users',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ]
        ]);
        $user = new user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = 'user';

        if($user->save()){
            return response()->json([
                'message' => 'Registration success.Please try to login.'
            ], 201);
        }else{
            return response()->json([
                'message' => 'Some error occurred. Please try again later'
            ], 500);
        }
    }
    public function login(Request $request){
        $request ->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return response()->json([
                'message' => 'Invalid username or password'
            ], 401);
        }

        $user = $request->user();

        $user->tokens()-> delete();

        if($user->role == 'admin'){
            $token = $user->createToken('Personal Access Token', ['admin']);
        }else{
            $token = $user->createToken('Personal Access Token', ['user']);
        }
        return response()->json([
            'user' => $user,
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'abilities' => $token->accessToken->abilities
        ], 200);
    }
    
    public function logout(Request $request){
        if($request->user()->tokens()->delete()){
            return response()->json([
                'message' => 'Logged out successfully'
            ], 200);
        }else{
            return response()->json([
                'message' => 'Some error occurred, please try again '
            ], 500);
        }

    }
}
