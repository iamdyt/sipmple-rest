<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function(){
    Route::post('register', [AuthController::class, 'addUser']);
    Route::post('login', [AuthController::class, 'loginUser']);
    Route::post('logout', [AuthController::class, 'logout']);

});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('user', function(Request $request){
        return $request->user();
    });

    Route::prefix('post')->group(function(){
       
        Route::get('view/{slug}', [PostController::class, 'viewPost']);
        Route::post('create', [PostController::class, 'createPost']);
        Route::put('update', [PostController::class, 'updatePost']);
        Route::delete('delete/{slug}', [PostController::class, 'deletePost']);
    });
});
