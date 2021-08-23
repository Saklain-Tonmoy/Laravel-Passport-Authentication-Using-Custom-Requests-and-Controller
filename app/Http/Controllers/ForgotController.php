<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ForgotRequest;
use App\Http\Requests\ResetRequest;
use App\Mail\ForgotMail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotController extends Controller
{
    public function forgotPassword(ForgotRequest $request){
        $email = $request->email;

        if(User::where('email', $email)->doesntExist()){
            return response([
                'message' => 'Email not found'
            ], 404);
        }

        $token = rand(10, 100000);

        try{
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token
            ]);
    
            Mail::to($email)->send(new ForgotMail($token));
    
            return response([
                'message' => 'Reset password link send to your email!'
            ],200);
        } catch(Exception $e){
            return response([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function resetPassword(ResetRequest $request){
        $email = $request->email;
        $token = $request->token;
        $password = Hash::make($request->password);

        $emailcheck = DB::table('password_resets')->where('email', $email)->first();
        $pincheck = DB::table('password_resets')->where('token', $token)->first();

        if(!$emailcheck){
            return response([
                'message' => 'Email not found'
            ], 401);
        }
        if(!$pincheck){
            return response([
                'message' => 'Invalid PIN'
            ], 401);
        }

        DB::table('users')->where('email', $email)->update(['password' => $password]);
        DB::table('password_resets')->where('email', $email)->delete();

        return response([
            'message' => 'Password Changed Successfully'
        ], 200);
    }
}
