<?php

use App\Http\Controllers\Auth\{LoginController, LogoutController, RegisterController};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json('Bem vindo a API Hey-Professor');
});

Route::post('login', LoginController::class)->name('login');
Route::post('register', RegisterController::class)->name('register');

Route::post('logout', LogoutController::class)->middleware(['auth'])->name('logout');
