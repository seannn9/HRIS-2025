<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/attendance/list', [AttendanceController::class, 'index'])
        ->name('attendance.list');

    Route::post('/attendance/create', [AttendanceController::class, 'store'])
        ->name('attendance.create');

    Route::get('/attendance/{id}', [AttendanceController::class, 'show'])
        ->name('attendance.view');

    Route::patch('/attendance/{id}', [AttendanceController::class, 'update'])
        ->name('attendance.update');

    Route::delete('/attendance/{id}', [AttendanceController::class, 'delete'])
        ->name('attendance.delete');

    Route::get('/attendance/export', [AttendanceController::class, 'export'])
        ->name('attendance.export');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});