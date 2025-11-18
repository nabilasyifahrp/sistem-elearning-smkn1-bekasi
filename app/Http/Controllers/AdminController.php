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

        $activities = collect();

        Siswa::latest()->take(5)->get()->each(function ($item) use ($activities) {
            $activities->push([
                'title' => 'Akun siswa baru dibuat',
                'sub' => 'Nama: ' . $item->nama . ', Email: ' . ($item->user ? $item->user->email : '-'),
                'created_at' => $item->created_at,
            ]);
        });

        Guru::latest()->take(5)->get()->each(function ($item) use ($activities) {
            $activities->push([
                'title' => 'Akun guru baru dibuat',
                'sub' => 'Nama: ' . $item->nama . ', Email: ' . ($item->user ? $item->user->email : '-'),
                'created_at' => $item->created_at,
            ]);
        });


        Mapel::latest()->take(5)->get()->each(function ($item) use ($activities) {
            $activities->push([
                'title' => 'Mapel baru ditambahkan',
                'sub' => 'Nama Mapel: ' . ($item->nama_mapel ?? '-'),
                'created_at' => $item->created_at,
            ]);
        });

        Kelas::latest()->take(5)->get()->each(function ($item) use ($activities) {
            $activities->push([
                'title' => 'Kelas baru ditambahkan',
                'sub' => ($item->tingkat ?? '-') . ' ' . ($item->jurusan ?? '-') . ' ' . ($item->kelas ?? '-'),
                'created_at' => $item->created_at,
            ]);
        });

        JadwalMapel::latest()->take(5)->get()->each(function ($item) use ($activities) {
            $mapelNama = $item->guruMapel && $item->guruMapel->mapel ? $item->guruMapel->mapel->nama_mapel : '-';
            $kelasNama = $item->kelas ? $item->kelas->tingkat . ' ' . $item->kelas->jurusan . ' ' . $item->kelas->kelas : '-';
            $activities->push([
                'title' => 'Jadwal mapel baru ditambahkan',
                'sub' => ($item->hari ?? '-') . ', ' . $mapelNama . ' - ' . $kelasNama,
                'created_at' => $item->created_at,
            ]);
        });

        Pengumuman::latest()->take(5)->get()->each(function ($item) use ($activities) {
            $activities->push([
                'title' => 'Pengumuman baru dipublikasikan',
                'sub' => $item->judul ?? '-',
                'created_at' => $item->created_at,
            ]);
        });

        $latestActivities = $activities->sortByDesc('created_at')->take(10);

        return view('admin.dashboard', compact(
            'totalGuru',
            'totalSiswa',
            'totalMapel',
            'totalKelas',
            'totalJadwal',
            'totalPengumuman',
            'latestActivities'
        ));
    }
}
