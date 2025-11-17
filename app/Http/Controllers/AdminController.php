<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JadwalMapel;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Pengumuman;
use App\Models\Siswa;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalGuru = Guru::count();
        $totalSiswa = Siswa::count();
        $totalMapel = Mapel::count();
        $totalKelas = Kelas::count();
        $totalJadwal = JadwalMapel::count();
        $totalPengumuman = Pengumuman::count();

        return view('admin.dashboard', compact('totalGuru', 'totalSiswa', 'totalMapel', 'totalKelas', 'totalJadwal', 'totalPengumuman'));
    }
}
