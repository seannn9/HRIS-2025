<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Protected attendance routes
Route::middleware(['auth:sanctum'])->group(function () {
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

Route::get('/token/create', function (Request $request) {
    $user = $request->user();
    
    $roles = ['fetch-attendance', 'create-attendance', 'export-attendance'];
    if (!$user->role->isEmployee()) {
        $roles = ['fetch-attendance', 'create-attendance', 'update-attendance', 'delete-attendance', 'export-attendance'];
    }

    $user->tokens()->delete();
    $token = $user->createToken('api-key', $roles);

    return response()->json(['token' => $token->plainTextToken]);
})->name('token.create')->middleware(['auth', 'auth.session']);

Route::get('/token/revoke', function (Request $request) {
    $result = 'success';
    $message = 'OK';

    try {
        $request->user()->tokens()->delete();
    } catch (\Throwable $th) {
        $result = 'error';
        $message = $th->getMessage();
    }

    return response()->json([
        'result' => $result,
        'message' => $message,
    ]);
})->name('token.create')->middleware(['auth', 'auth.session']);