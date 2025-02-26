<?php

use App\Http\Controllers\AttendanceExistsController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::post('attendance/exists', [AttendanceExistsController::class, 'check'])
        ->name('attendance.exists');
});