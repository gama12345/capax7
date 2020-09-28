<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\AdminController;
use \App\Http\Controllers\ClientController;

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
Route::get('/registro/cliente', [AdminController::class, 'showRegisterClient'])->name('showRegisterClient');
Route::post('/registro/guardando', [AdminController::class, 'registerClient'])->name('registerClient');

//Client
Route::get('/donantes', [ClientController::class, 'showDonorsMenu'])->name('showDonorsMenu');
Route::get('/donantes/nuevo', [ClientController::class, 'showRegisterDonor'])->name('showRegisterDonor');
Route::post('/donantes/nuevo/validando', [ClientController::class, 'registerDonor'])->name('registerDonor');
Route::get('/donantes/registros', [ClientController::class, 'showDonors'])->name('showDonors');
Route::post('/donantes/registros/validando', [ClientController::class, 'updateDonors'])->name('updateDonors');
Route::get('/donaciones/nuevo', [ClientController::class, 'showRegisterDonation'])->name('showRegisterDonation');
Route::post('/donaciones/nuevo/validando', [ClientController::class, 'registerDonation'])->name('registerDonation');
Route::get('/donaciones/registros', [ClientController::class, 'showDonations'])->name('showDonations');
Route::post('/actualizar/documento/{orgType}/{doc}', [ClientController::class, 'updateDocument'])->name('updateDocument');
Route::post('/actualizar/informacion/administrativa', [ClientController::class, 'updateAdministrativeInformation'])->name('updateAdministrativeInformation');
Route::post('/actualizar/informacion/general', [ClientController::class, 'updateGeneralInformation'])->name('updateGeneralInformation');

