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
use App\Http\Controllers\SiswaController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::get('/', function () {
    return redirect()->route('login');
});
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

    Route::get('/kelas/{id_guru_mapel}/materi', [GuruController::class, 'kelasMateriIndex'])->name('kelas.materi.index');
    Route::get('/kelas/{id_guru_mapel}/materi/create', [GuruController::class, 'kelasMateriCreate'])->name('kelas.materi.create');
    Route::post('/kelas/{id_guru_mapel}/materi/store', [GuruController::class, 'kelasMateriStore'])->name('kelas.materi.store');

    Route::get('/materi/{id_materi}', [GuruController::class, 'materiDetail'])->name('materi.detail');
    Route::get('/materi/{id_materi}/edit', [GuruController::class, 'materiEdit'])->name('materi.edit');
    Route::put('/materi/{id_materi}/update', [GuruController::class, 'materiUpdate'])->name('materi.update');
    Route::delete('/materi/{id_materi}', [GuruController::class, 'materiDelete'])->name('materi.delete');

    Route::get('/tugas', [GuruController::class, 'tugasIndex'])->name('tugas.index');
    Route::get('/tugas/{id_tugas}/edit', [GuruController::class, 'tugasEdit'])->name('tugas.edit');
    Route::put('/tugas/{id_tugas}/update', [GuruController::class, 'tugasUpdate'])->name('tugas.update');
    Route::delete('/tugas/{id_tugas}', [GuruController::class, 'tugasDelete'])->name('tugas.delete');
    Route::get('/kelas/{id_guru_mapel}/tugas/create', [GuruController::class, 'kelasTugasCreate'])->name('kelas.tugas.create');
    Route::post('/kelas/{id_guru_mapel}/tugas/store', [GuruController::class, 'kelasTugasStore'])->name('kelas.tugas.store');

    Route::post('/tugas/{id_tugas}/pengumpulan/{id_pengumpulan}/nilai', [GuruController::class, 'submitNilai'])->name('tugas.pengumpulan.nilai');
    Route::get('/tugas/{id_tugas}/pengumpulan/{id_pengumpulan}', [GuruController::class, 'detailTugasSiswa'])->name('tugas.pengumpulan.detail');
    Route::get('/tugas/{id_tugas}/pengumpulan', [GuruController::class, 'tugasDetail'])->name('tugas.pengumpulan');
    Route::get('/tugas/{id_tugas}', [GuruController::class, 'tugasDetail'])->name('tugas.detail');

    Route::get('/kelas/{id_guru_mapel}/siswa/{nis}', [GuruController::class, 'detailSiswa'])->name('kelas.siswa.detail');

    Route::get('/izin', [GuruController::class, 'izinIndex'])->name('izin.index');
    Route::post('/izin/{id}/setujui', [GuruController::class, 'izinSetujui'])->name('izin.setujui');
    Route::post('/izin/{id}/tolak', [GuruController::class, 'izinTolak'])->name('izin.tolak');
    Route::get('/pengumuman', [GuruController::class, 'pengumumanIndex'])->name('pengumuman.index');
    Route::get('/show/{id}', [GuruController::class, 'pengumumanShow'])->name('pengumuman.show');

    Route::get('/kelas/{id}/absensi', [GuruController::class, 'absensiKelas'])->name('absensi.kelas');
    Route::post('/absensi/{id}/buka', [GuruController::class, 'bukaSesiAbsensi'])->name('absensi.buka');
    Route::post('/absensi/{id}/tutup/{tanggal}', [GuruController::class, 'tutupSesiAbsensi'])->name('absensi.tutup');
    Route::get('/absensi/{id}/rekap', [GuruController::class, 'rekapAbsensi'])->name('absensi.rekap');

    Route::get('/profile', [GuruController::class, 'profileIndex'])->name('profile.index');
    Route::post('/profile/update', [GuruController::class, 'profileUpdate'])->name('profile.update');
});

Route::prefix('siswa')->middleware([RoleMiddleware::class . ':siswa'])->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/kelas/{id_guru_mapel}', [SiswaController::class, 'detailMapel'])->name('kelas.detail');

    Route::get('/tugas', [SiswaController::class, 'tugasIndex'])->name('tugas.index');
    Route::get('/tugas/{id_tugas}', [SiswaController::class, 'tugasDetail'])->name('tugas.detail');
    Route::post('/tugas/{id_tugas}/kumpul', [SiswaController::class, 'tugasKumpul'])->name('tugas.kumpul');
    Route::delete('/tugas/{id_tugas}/batalkan', [SiswaController::class, 'tugasBatalkan'])->name('tugas.batalkan');

    Route::get('/izin', [SiswaController::class, 'izinIndex'])->name('izin.index');
    Route::get('/izin/create', [SiswaController::class, 'izinCreate'])->name('izin.create');
    Route::post('/izin/store', [SiswaController::class, 'izinStore'])->name('izin.store');

    Route::get('/pengumuman', [SiswaController::class, 'pengumumanIndex'])->name('pengumuman.index');
    Route::get('/pengumuman/{id}', [SiswaController::class, 'pengumumanShow'])->name('pengumuman.show');

    Route::get('/profile', [SiswaController::class, 'profileIndex'])->name('profile.index');
    Route::post('/profile/update', [SiswaController::class, 'profileUpdate'])->name('profile.update');

    Route::post('/absensi/hadir/{id_guru_mapel}', [SiswaController::class, 'absenHadir'])->name('absensi.hadir');
    Route::get('/siswa/absensi', [SiswaController::class, 'absensiIndex'])->name('absensi.index');
});
