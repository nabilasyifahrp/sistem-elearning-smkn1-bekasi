<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrudGuruController;
use App\Http\Controllers\CrudKelasController;
use App\Http\Controllers\CrudMapelController;
use App\Http\Controllers\CrudSiswaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\CrudJadwalMapelController;
use App\Http\Controllers\CrudPengumumanController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\MateriController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::get('/', function () {return redirect()->route('login');});
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->middleware(RoleMiddleware::class . ':admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::prefix('kelas')->name('admin.kelas.')->group(function () {
        Route::get('/', [CrudKelasController::class, 'index'])->name('index');
        Route::get('/create', [CrudKelasController::class, 'create'])->name('create');
        Route::post('/store', [CrudKelasController::class, 'store'])->name('store');
        Route::get('/show/{id}', [CrudKelasController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [CrudKelasController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CrudKelasController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CrudKelasController::class, 'destroy'])->name('destroy');

        Route::get('wali-kelas/{id}/', [CrudKelasController::class, 'createWali'])->name('createWali');
        Route::post('wali-kelas/{id}/', [CrudKelasController::class, 'storeWali'])->name('storeWali');
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

    Route::prefix('mapel')->name('admin.mapel.')->group(function () {
        Route::get('/', [CrudMapelController::class, 'index'])->name('index');
        Route::get('/create', [CrudMapelController::class, 'create'])->name('create');
        Route::post('/store', [CrudMapelController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CrudMapelController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CrudMapelController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CrudMapelController::class, 'destroy'])->name('destroy');
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
        Route::get('/', [CrudJadwalMapelController::class, 'index'])->name('index');
        Route::get('/create', [CrudJadwalMapelController::class, 'create'])->name('create');
        Route::post('/store', [CrudJadwalMapelController::class, 'store'])->name('store');
        Route::get('/show/{id}', [CrudJadwalMapelController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [CrudJadwalMapelController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CrudJadwalMapelController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CrudJadwalMapelController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('pengumuman')->name('admin.pengumuman.')->group(function () {
        Route::get('/', [CrudPengumumanController::class, 'index'])->name('index');
        Route::get('/create', [CrudPengumumanController::class, 'create'])->name('create');
        Route::post('/store', [CrudPengumumanController::class, 'store'])->name('store');
        Route::get('/show/{id}', [CrudPengumumanController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [CrudPengumumanController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CrudPengumumanController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CrudPengumumanController::class, 'destroy'])->name('destroy');
    });
});

Route::prefix('guru')->middleware([RoleMiddleware::class . ':guru'])->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
    Route::get('/kelas/{id_guru_mapel}', [GuruController::class, 'detailMapel'])->name('kelas.detail');
    Route::get('/tugas', [GuruController::class, 'tugasIndex'])->name('tugas.index');
    Route::get('/tugas/{id_tugas}', [GuruController::class, 'tugasDetail'])->name('tugas.detail');
    Route::get('/tugas/{id_tugas}/edit', [GuruController::class, 'tugasEdit'])->name('tugas.edit');
    Route::post('/tugas/{id_tugas}/update', [GuruController::class, 'tugasUpdate'])->name('tugas.update');
    Route::delete('/tugas/{id_tugas}', [GuruController::class, 'tugasDelete'])->name('tugas.delete');
    Route::get('/kelas/{id_guru_mapel}/tugas', [GuruController::class, 'kelasTugasIndex'])->name('kelas.tugas.index');
    Route::get('/kelas/{id_guru_mapel}/tugas/create', [GuruController::class, 'kelasTugasCreate'])->name('kelas.tugas.create');
    Route::post('/kelas/{id_guru_mapel}/tugas/store', [GuruController::class, 'kelasTugasStore'])->name('kelas.tugas.store');
    Route::get('/tugas/{id_tugas}/pengumpulan', [GuruController::class, 'tugasDetail'])->name('tugas.pengumpulan');
    Route::post('/tugas/{id_tugas}/pengumpulan/{id_pengumpulan}/nilai', [GuruController::class, 'beriNilai'])->name('tugas.pengumpulan.nilai');
    Route::get('/absensi', [GuruController::class, 'absensiIndex'])->name('absensi.index');
    Route::post('/absensi/kelola', [GuruController::class, 'absensiKelola'])->name('absensi.kelola');
    Route::post('/absensi/{tanggal}/store', [GuruController::class, 'absensiStore'])->name('absensi.store');
    Route::get('/izin', [GuruController::class, 'izinIndex'])->name('izin.index');
    Route::post('/izin/{id}/setujui', [GuruController::class, 'izinSetujui'])->name('izin.setujui');
    Route::post('/izin/{id}/tolak', [GuruController::class, 'izinTolak'])->name('izin.tolak');
    Route::get('/pengumuman', [GuruController::class, 'pengumumanIndex'])->name('pengumuman.index');
});


Route::middleware(RoleMiddleware::class . ':siswa')->group(function () {
    Route::prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');

        Route::get('/mapel/{id_guru_mapel}', [SiswaController::class, 'detailMapel'])->name('detail_mapel');

        Route::get('/tugas/{id_tugas}', [SiswaController::class, 'detailTugas'])->name('detail_tugas');
        Route::post('/tugas/{id_tugas}/kumpulkan', [SiswaController::class, 'kumpulkanTugas'])->name('kumpulkan_tugas');

        Route::get('/absensi', [SiswaController::class, 'absensi'])->name('absensi');

        Route::get('/pengajuan-izin', [SiswaController::class, 'pengajuanIzin'])->name('pengajuan_izin');
        Route::get('/pengajuan-izin/create', [SiswaController::class, 'createPengajuanIzin'])->name('create_pengajuan_izin');
        Route::post('/pengajuan-izin/store', [SiswaController::class, 'storePengajuanIzin'])->name('store_pengajuan_izin');

        Route::get('/pengumuman', [SiswaController::class, 'pengumuman'])->name('pengumuman');
        Route::get('/pengumuman/{id}', [SiswaController::class, 'detailPengumuman'])->name('detail_pengumuman');
    });
});
