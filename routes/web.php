<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cabinet\JobVacancyController;
use App\Http\Controllers\Cabinet\JobVacancyResponseController;
use \App\Http\Controllers\Cabinet\FavoritesController;
use App\Http\Controllers\MainController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/',[MainController::class, 'index'])->name('index');
Route::get('/show/{jobVacancy}',[MainController::class, 'show'])->name('job.show');

Route::group(['middleware' => ['auth']],
    function () {
        Route::get('/response/{jobVacancy}',[MainController::class, 'responseForm'])->name('job.response');
        Route::post('/response/{jobVacancy}',[MainController::class, 'response'])->name('job.response');
        Route::post('/favorite/user/{user}',[FavoritesController::class, 'addFavoriteUser'])->name('favorite.user');
        Route::post('/favorite/user/delete/{favorite}',[FavoritesController::class, 'delete'])->name('favorite.delete');
        Route::post('/favorite/job/{jobVacancy}',[FavoritesController::class, 'addFavoriteJob'])->name('favorite.job');
    });

Route::group(
    [
        'prefix' => 'cabinet',
        'as' => 'cabinet.',
        'middleware' => ['auth'],
    ],
    function () {
        Route::get('/dashboard', function () { return view('dashboard');})->name('dashboard');
        Route::get('/vacancies',[JobVacancyController::class, 'index'])->name('vacancies');
        Route::get('/vacancies/create',[JobVacancyController::class, 'createForm'])->name('vacancies.create');
        Route::post('/vacancies/create',[JobVacancyController::class, 'store'])->name('vacancies.create');
        Route::get('/vacancies/edit/{jobVacancy}',[JobVacancyController::class, 'editForm'])->name('vacancies.edit');
        Route::get('/vacancies/{jobVacancy}/responses/',[JobVacancyController::class, 'responses'])->name('vacancies.responses');
        Route::get('/responses/',[JobVacancyResponseController::class, 'index'])->name('responses');
        Route::post('/responses/delete/{jobVacancyResponse}',[JobVacancyResponseController::class, 'delete'])->name('responses.delete');
        Route::post('/vacancies/edit/{jobVacancy}',[JobVacancyController::class, 'edit'])->name('vacancies.edit');
        Route::post('/vacancies/delete/{jobVacancy}',[JobVacancyController::class, 'delete'])->name('vacancies.delete');
        Route::post('/vacancies/publish/{jobVacancy}',[JobVacancyController::class, 'publish'])->name('vacancies.publish');
});


require __DIR__.'/auth.php';
