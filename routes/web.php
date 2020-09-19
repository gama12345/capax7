<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;

//Main
Route::get('/', function () {
    return view('main');
})->name('main');
Route::post('/iniciarSesion', [UserController::class, 'login'])->name('login');
Route::get('/inicio/{user}', [UserController::class, 'goHome'])->name('home');
Route::get('/salir/{user}', [UserController::class, 'logout'])->name('logout');

//Reset password
Route::get('/recuperarcontraseña', [UserController::class, 'showRequestPassword'])->name('showRequestPassword');
Route::post('/recuperarcontraseña/validando', [UserController::class, 'validateRequestPassword'])->name('validateRequestPassword');
/*Route::get('/reestablecer/password/{token}', 'UsuarioController@verReestablecerPassword')->name('password.reset');
Route::post('/reestablecer/password', 'UsuarioController@actualizarPassword')->name('actualizar.password');
Route::post('reset_password_with_token', 'UsuarioController@reestablecerPassword')->name('reestablecerPassword');
*/

//Admin

//Client

