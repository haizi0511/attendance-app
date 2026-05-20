<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceRequestController;

Route::get('/register', [UserController::class, 'showRegisterForm']);
Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'showloginForm'])->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');

    Route::post('/clock_in', [AttendanceController::class, 'clockIn'])
        ->name('clock_in');

    Route::post('/clock_out', [AttendanceController::class, 'clockOut'])
        ->name('clock_out');

    Route::post('/break_in', [AttendanceController::class, 'breakIn'])
        ->name('break_in');

    Route::post('/break_out', [AttendanceController::class, 'breakOut'])
        ->name('break_out');
});
