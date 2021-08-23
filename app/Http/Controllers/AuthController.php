<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(request $request){

        try{
            if(Auth::attempt($request->only('email', 'password'))){
                $user = Auth::user();
                $token = $user->createToken('app')->accessToken;
    
                return response([
                    'message' => 'successfully login',
                    'token' => $token,
                    'user' => $user
                ], 400);
            }
        } catch(Exception $e){
            return response([
                'message' => $e->getMessage()
            ],400);
        }
        

        return response([
            'message' => 'Invalid Email or Password'
        ], 401);
    }

    public function register(RegisterRequest $request){

        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ], 400);
    
            $token = $user->createToken('app')->accessToken;
    
            return response([
                'message' => 'Successfully registered',
                'token' => $token,
                'user' => $user
            ], 400);
        } catch(Exception $e){
            return response([
                'message' => $e->getMessage()
            ],401);
        }
        
    }
}
