<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\AdminController;
use \App\Http\Controllers\ClientController;

//Main
Route::get('/', [UserController::class, 'showMain'])->name('main');
Route::post('/iniciarSesion', [UserController::class, 'login'])->name('login');
Route::get('/inicio/{user}', [UserController::class, 'goHome'])->name('home');
Route::get('/salir/{user}', [UserController::class, 'logout'])->name('logout');

//Reset password
Route::get('/recuperarcontraseña', [UserController::class, 'showRequestPassword'])->name('showRequestPassword');
Route::post('/recuperarcontraseña/validando', [UserController::class, 'validateRequestPassword'])->name('validateRequestPassword');
Route::get('/reestablecercontraseña/{token}', [UserController::class, 'showResetPassword'])->name('showResetPassword');
Route::post('/reestablecerpassword', [UserController::class, 'updatePassword'])->name('updatePassword');

//Admin
Route::post('/admin/actualizar/informacion/general', [AdminController::class, 'updateGeneralInformation'])->name('updateGeneralInformationAdmin');
Route::post('/admin/actualizar/informacion/administrativa', [AdminController::class, 'updateAdministrativeInformation'])->name('updateAdministrativeInformationAdmin');


Route::get('/registro/cliente', [AdminController::class, 'showRegisterClient'])->name('showRegisterClient');
Route::post('/registro/guardando', [AdminController::class, 'registerClient'])->name('registerClient');
Route::get('/admin/estadisticas', [AdminController::class, 'showStatistics'])->name('showStatisticsAdmin');


//Client
Route::get('/estadisticas/donaciones', [ClientController::class, 'showDetailedDonations'])->name('showDetailedDonations');
Route::post('/estadisticas/donaciones/grafica', [ClientController::class, 'showDetailedDonationsMonthYear'])->name('showDetailedDonationsMonthYear');
Route::get('/estadisticas/donantes', [ClientController::class, 'showDetailedDonors'])->name('showDetailedDonors');
Route::post('/estadisticas/donantes/grafica', [ClientController::class, 'showDetailedDonorsMonthYear'])->name('showDetailedDonorsMonthYear');
Route::get('/estadisticas', [ClientController::class, 'showStatistics'])->name('showStatistics');
Route::get('/datos', [ClientController::class, 'showDataMenu'])->name('showDataMenu');
Route::get('/ingresos/nuevo', [ClientController::class, 'showRegisterRevenue'])->name('showRegisterRevenue');
Route::get('/ingresos/registros', [ClientController::class, 'showRevenues'])->name('showRevenues');
Route::post('/ingresos/registros/grafica', [ClientController::class, 'showDetailedRevenuesMonthYear'])->name('showDetailedRevenuesMonthYear');
Route::post('/ingresos/nuevo/validando', [ClientController::class, 'registerRevenue'])->name('registerRevenue');
Route::get('/gastos/nuevo', [ClientController::class, 'showRegisterExpense'])->name('showRegisterExpense');
Route::get('/gastos/registros', [ClientController::class, 'showExpenses'])->name('showExpenses');
Route::post('/gastos/registros/grafica', [ClientController::class, 'showDetailedExpensesMonthYear'])->name('showDetailedExpensesMonthYear');
Route::post('/gastos/nuevo/validando', [ClientController::class, 'registerExpense'])->name('registerExpense');
Route::get('/donantes', [ClientController::class, 'showDonorsMenu'])->name('showDonorsMenu');
Route::get('/donantes/nuevo', [ClientController::class, 'showRegisterDonor'])->name('showRegisterDonor');
Route::post('/donantes/nuevo/validando', [ClientController::class, 'registerDonor'])->name('registerDonor');
Route::get('/donantes/registros', [ClientController::class, 'showDonors'])->name('showDonors');
Route::post('/donantes/registros/validando', [ClientController::class, 'updateDonors'])->name('updateDonors');
Route::get('/donaciones/nuevo', [ClientController::class, 'showRegisterDonation'])->name('showRegisterDonation');
Route::post('/donaciones/nuevo/validando', [ClientController::class, 'registerDonation'])->name('registerDonation');

Route::post('/actualizar/documento/{orgType}/{doc}', [ClientController::class, 'updateDocument'])->name('updateDocument');
Route::post('/actualizar/informacion/administrativa', [ClientController::class, 'updateAdministrativeInformation'])->name('updateAdministrativeInformation');
Route::post('/actualizar/informacion/general', [ClientController::class, 'updateGeneralInformation'])->name('updateGeneralInformation');

