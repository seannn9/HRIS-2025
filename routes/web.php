<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::post(
        '/authenticate', [LoginController::class, 'authenticate']
    )->name('authenticate');
});


Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get("/", function () {
        return view('dashboard.home');
    })->name("home");

    Route::get("/logout", [LoginController::class, 'logout'])->name("logout");
});