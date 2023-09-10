<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [LoginController::class,'login']);
Route::post('/register', [RegisterController::class,'register']);
Route::post('/logout',  [LoginController::class,'logout'])->middleware('auth:api');;


// criar rotas autenticadas
Route::middleware('auth:api') ->group(function(){
//
});
