<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::apiResource('attendance', AttendanceController::class)
        ->except(['index']);

    Route::get('/attendance', [AttendanceController::class, 'index']);

    Route::apiResource('leave', LeaveRequestController::class)
        ->except(['index']);

    Route::get('/leave', [LeaveRequestController::class, 'index']);

    Route::patch('/leave/{id}/status', [LeaveRequestController::class, 'updateStatus']);
});