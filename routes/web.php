<?php


use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\TugasController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::get('/', function () {
    return view('welcome');
});

// Materi
Route::get('/materi', [MateriController::class, 'index'])->name('materi.index');
Route::get('/materi/create', [MateriController::class, 'create'])->name('materi.create');
Route::post('/materi', [MateriController::class, 'store'])->name('materi.store');
Route::get('/materi/{id}/edit', [MateriController::class, 'edit'])->name('materi.edit');
Route::put('/materi/{id}', [MateriController::class, 'update'])->name('materi.update');
Route::delete('/materi/{id}', [MateriController::class, 'destroy'])->name('materi.destroy');

// Tugas
Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
Route::get('/tugas/create', [TugasController::class, 'create'])->name('tugas.create');
Route::post('/tugas/store', [TugasController::class, 'store'])->name('tugas.store');
Route::get('/tugas/edit/{id}', [TugasController::class, 'edit'])->name('tugas.edit');
Route::post('/tugas/update/{id}', [TugasController::class, 'update'])->name('tugas.update');
Route::get('/tugas/delete/{id}', [TugasController::class, 'destroy'])->name('tugas.delete');

// Absensi
Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
Route::get('/absensi/edit/{id}', [AbsensiController::class, 'edit'])->name('absensi.edit');
Route::post('/absensi/update/{id}', [AbsensiController::class, 'update'])->name('absensi.update');
Route::get('/absensi/delete/{id}', [AbsensiController::class, 'destroy'])->name('absensi.delete');
