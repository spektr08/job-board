<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\HomeController;
use \App\Http\Controllers\Api\RegisterController;

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




Route::get('/', [HomeController::class, 'home']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login']);

