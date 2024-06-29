<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');



// Forgot Password Routes
Route::get('forgot-password', 'App\Http\Controllers\Auth\ForgotPasswordController@showForgotPasswordForm')->name('password.request');
Route::post('forgot-password', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

// Reset Password Routes
Route::get('reset-password/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetPasswordForm')->name('password.reset');
Route::post('reset-password', 'App\Http\Controllers\Auth\ResetPasswordController@resetPassword')->name('password.update');
