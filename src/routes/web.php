<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceRequestController;
use App\Http\Controllers\Admin\AuthController;

Route::get('/login', [UserController::class, 'showloginForm'])
    ->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    Route::post('/clock_in', [AttendanceController::class, 'clockIn'])
        ->name('clock_in');

    Route::post('/clock_out', [AttendanceController::class, 'clockOut'])
        ->name('clock_out');

    Route::post('/break_in', [AttendanceController::class, 'breakIn'])
        ->name('break_in');

    Route::post('/break_out', [AttendanceController::class, 'breakOut'])
        ->name('break_out');

    Route::get('/attendance/list', [AttendanceController::class, 'list'])
        ->name('attendance.list');

    Route::get('/attendance/{id}', [AttendanceController::class, 'detail'])
        ->name('attendance.detail');

    Route::post('/attendance/{id}', [AttendanceController::class, 'update'])
        ->name('attendance.update');

    Route::get('/attendance_request/list', [AttendanceRequestController::class, 'index'])
        ->name('attendance_request.index');

    Route::get('/attendance_request/approved', [AttendanceRequestController::class, 'approved'])
        ->name('request.approved');
});

//管理者用
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])
    ->name('admin.login.form');
Route::post('/admin/login', [AuthController::class, 'login'])
    ->name('admin.login');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/attendance/list', [AttendanceController::class, 'admin_index'])
        ->name('admin.index');
    Route::get('admin/attendance/{id}', [AttendanceController::class, 'admin_detail'])
        ->name('admin.detail');
    Route::post('admin/attendance/{id}', [AttendanceController::class, 'admin_update'])
        ->name('admin.update');
    Route::get('/admin/staff/list', [UserController::class, 'index'])
        ->name('admin.staff');
    Route::get('/admin/attendance/staff/{id}', [UserController::class, 'detail'])
        ->name('admin.staff.detail');
    Route::get('/admin/staff/{id}/csv', [UserController::class, 'StaffCsv'])
        ->name('admin.staff.csv');
    Route::get('/admin/attendance_request', [AttendanceRequestController::class, 'admin_index'])
        ->name('admin.request.index');
    Route::get('/admin/attendance_request/approved', [AttendanceRequestController::class, 'admin_tab'])
        ->name('admin.request.tab');
    Route::get('/admin/attendance_request/{id}', [AttendanceRequestController::class, 'show'])
        ->name('admin.request.show');
    Route::post('/admin/attendance_request/{id}', [AttendanceRequestController::class, 'update'])
        ->name('admin.request.update');
});