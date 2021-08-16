<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware'=>['role:administrador|docente|alumno']],function(){
    Route::resource('modalidad', 'App\Http\Controllers\C_modalidadController');
    Route::resource('carrera', 'App\Http\Controllers\C_carreraController');
    Route::resource('planestudio', 'App\Http\Controllers\C_planestudioController');
});
