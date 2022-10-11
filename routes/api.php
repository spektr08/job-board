<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\HomeController;
use \App\Http\Controllers\Api\RegisterController;
use \App\Http\Controllers\Api\JobController;
use \App\Http\Controllers\Api\FavoritesController;

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
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{id}', [JobController::class, 'show']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login']);

Route::group(['middleware' => ['auth:api']],
    function () {
        Route::post('/jobs', [JobController::class, 'store']);
        Route::post('/jobs/{jobVacancy}', [JobController::class, 'edit']);
        Route::post('/jobs/publish/{jobVacancy}', [JobController::class, 'publish']);
        Route::delete('/jobs/{jobVacancy}', [JobController::class, 'delete']);
        Route::post('/jobs/response/{jobVacancy}', [JobController::class, 'response']);
        Route::delete('/jobs/response/{jobVacancyResponse}', [JobController::class, 'deleteResponse']);
        Route::get('/favorite/users', [FavoritesController::class, 'indexUsers']);
        Route::get('/favorite/jobs', [FavoritesController::class, 'indexJobs']);
    });

