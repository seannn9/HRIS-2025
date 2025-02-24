<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// TODO: Do a massive refactor for Sanctum api guards. It should not rely on
// Web sesssion rather it should rely on API 

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/attendance/list', [AttendanceController::class, 'index'])
        ->name('attendance.list')
        ->middleware('ability:fetch-attendance');

    Route::post('/attendance/create', [AttendanceController::class, 'store'])
        ->name('attendance.create')
        ->middleware('ability:fetch-attendance,create-attendance');

    Route::get('/attendance/{attendance}', [AttendanceController::class, 'show'])
        ->name('attendance.view')
        ->middleware('ability:fetch-attendance');

    Route::patch('/attendance/{attendance}', [AttendanceController::class, 'update'])
        ->name('attendance.update')
        ->middleware('ability:update-attendance');

    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'delete'])
        ->name('attendance.delete')
        ->middleware('ability:delete-attendance');

    Route::get('/attendance/export', [AttendanceController::class, 'export'])
        ->name('attendance.export')
        ->middleware('ability:export-attendance');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});