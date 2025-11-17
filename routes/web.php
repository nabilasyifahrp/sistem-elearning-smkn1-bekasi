<?php


use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrudGuruController;
use App\Http\Controllers\CrudJadwalMapel;
use App\Http\Controllers\CrudKelasController;
use App\Http\Controllers\CrudMapelController;
use App\Http\Controllers\CrudSiswaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\TugasController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');

// Route::post('/guru/dashboard', [GuruController::class, 'authenticate'])->name('authenticate');

Route::prefix('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::prefix('kelas')->name('admin.kelas.')->group(function () {
        Route::get('/', [CrudKelasController::class, 'index'])->name('index');
        Route::get('/create', [CrudKelasController::class, 'create'])->name('create');
        Route::post('/store', [CrudKelasController::class, 'store'])->name('store');
        Route::get('/show/{id}', [CrudKelasController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [CrudKelasController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CrudKelasController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CrudKelasController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('siswa')->name('admin.siswa.')->group(function () {
        Route::get('/', [CrudSiswaController::class, 'index'])->name('index');
        Route::get('/create', [CrudSiswaController::class, 'create'])->name('create');
        Route::post('/store', [CrudSiswaController::class, 'store'])->name('store');
        Route::get('/show/{nis}', [CrudSiswaController::class, 'show'])->name('show');
        Route::get('/edit/{nis}', [CrudSiswaController::class, 'edit'])->name('edit');
        Route::put('/update/{nis}', [CrudSiswaController::class, 'update'])->name('update');
        Route::delete('/delete/{nis}', [CrudSiswaController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/mapel', [CrudMapelController::class, 'index'])->name('mapel.index');
        Route::get('/mapel/create', [CrudMapelController::class, 'create'])->name('mapel.create');
        Route::post('/mapel', [CrudMapelController::class, 'store'])->name('mapel.store');
        Route::get('/mapel/{id}/edit', [CrudMapelController::class, 'edit'])->name('mapel.edit');
        Route::put('/mapel/{id}', [CrudMapelController::class, 'update'])->name('mapel.update');
        Route::delete('/mapel/{id}', [CrudMapelController::class, 'destroy'])->name('mapel.destroy');
    });

    Route::prefix('guru')->name('admin.guru.')->group(function () {
        Route::get('/', [CrudGuruController::class, 'index'])->name('index');
        Route::get('/create', [CrudGuruController::class, 'create'])->name('create');
        Route::post('/store', [CrudGuruController::class, 'store'])->name('store');
        Route::get('/show/{id}', [CrudGuruController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [CrudGuruController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CrudGuruController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CrudGuruController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('jadwal-mapel')->name('admin.jadwalmapel.')->group(function () {
        Route::get('/', [CrudJadwalMapel::class, 'index'])->name('index');
        Route::get('/create', [CrudJadwalMapel::class, 'create'])->name('create');
        Route::post('/store', [CrudJadwalMapel::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CrudJadwalMapel::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CrudJadwalMapel::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CrudJadwalMapel::class, 'destroy'])->name('destroy');
    });
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

