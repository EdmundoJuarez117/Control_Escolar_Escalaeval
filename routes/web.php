<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\C_modalidadController;
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
    Route::get('/modalidades/{offset}/{data}',[C_modalidadController::class, 'index'])->name('/modalidades/{offset}/{data}');
    
    Route::resource('/modalidades', C_modalidadController::class)->name("*",'modalidades');
    Route::resource('carrera', 'App\Http\Controllers\C_carreraController');
    Route::resource('planestudio', 'App\Http\Controllers\C_planestudioController');
    Route::resource('escalaeval', 'App\Http\Controllers\C_escalaevalController');

});
