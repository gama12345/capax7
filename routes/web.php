<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\AdminController;

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
Route::get('/reestablecercontraseña/{token}', [UserController::class, 'showResetPassword'])->name('showResetPassword');
Route::post('/reestablecerpassword', [UserController::class, 'updatePassword'])->name('updatePassword');

//Admin
Route::get('/registro', [AdminController::class, 'showRegisterClient'])->name('showRegisterClient');
Route::post('/registro/guardando', [AdminController::class, 'registerClient'])->name('registerClient');

//Client

