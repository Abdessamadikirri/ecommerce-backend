<?php

use App\Http\Controllers\CartApiController;
use App\Http\Controllers\CartItemApiController;
use App\Http\Controllers\ProductApiController;
use App\Http\Controllers\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [UserApiController::class, 'register']);
Route::post('/login', [UserApiController::class, 'login']);
Route::post('/logout', [UserApiController::class, 'logout'])->middleware('auth:sanctum');



Route::get('products',[ProductApiController::class,'index']);
Route::post('products',[ProductApiController::class,'store']);
Route::get('products/{id}',[ProductApiController::class,'show']);
Route::put('products/{id}',[ProductApiController::class,'update']);
Route::delete('products/{id}',[ProductApiController::class,'destroy']);

Route::post('cartitem',[CartItemApiController::class,'store']);


Route::get('cart',[CartApiController::class,'index']);