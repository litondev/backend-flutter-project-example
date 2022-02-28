<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    SigninRequest,
    SignupRequest,
    ForgotPasswordRequest,
    ResetPasswordRequest
};

class AuthController extends Controller
{
    public function signin(SigninRequest $request){
        if (!auth()->attempt($request->validated())) {
            return response()->json([
                "message" => "Email Atau Password Salah"
            ],401);
        }

        return response()->json([
            'access_token' => auth()->user()->createToken('access_token')->plainTextToken,
            'user' => auth()->user()
        ]);
    }

    public function signup(SignupRequest $request){
        try{
            \DB::beginTransaction();

            User::create([
                "password" => \Hash::make($request->password)
            ] + $request->validated());

            \DB::commit();
            return response()->json([
                "message" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request){
        try{
            \DB::beginTransaction();

            $user = User::query()
                ->where("email",$request->email)
                ->firstOrFail();

            $user->update([
                "remember_token" => "12345"
            ]);

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    public function resetPassword(ResetPasswordRequest $request){
        try{
            \DB::beginTransaction();

            $user = User::query()
                ->where("remember_token",$request->token)
                ->where("email",$request->email)
                ->firstOrFail();

            $user->update([
                "password" => \Hash::make($request->password),
                "remember_token" => Null
            ]);

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);    
    }

    public function me(){
        return response()->json(auth()->user());
    }
}
