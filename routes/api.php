<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    ProfilController,
    ProductController
};  

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("/status",function(){
    return response()->json([
        "status" => "Active"
    ]);
});

Route::group(["as" => "auth."],function(){
    Route::post("/signup",[AuthController::class,"signup"])->name("signup");
    Route::post("/signin",[AuthController::class,"signin"])->name("signin");

    Route::post("/forgot-password",[AuthController::class,"forgotPassword"])->name("forgot-password");
    Route::post("/reset-password",[AuthController::class,"resetPassword"])->name("reset-password");

    Route::group(["middleware" => "auth:sanctum"],function(){
        Route::post("/logout",[AuthController::class,'logout'])->name("logout");
        Route::get("/me",[AuthController::class,'me'])->name("me");
    });
});

Route::group(["middleware" => ["auth:sanctum"]],function(){
    Route::group(["prefix" => "profil","as" => "profil."],function(){
        Route::post("/data",[ProfilController::class,"updateData"])->name("update.data");
        Route::post("/photo",[ProfilController::class,"updatePhoto"])->name("update.photo");
        Route::post("/password",[ProfilController::Class,"updatePassword"])->name("update.password");
    });

    Route::apiResource("product",ProductController::class);
});