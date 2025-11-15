<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
