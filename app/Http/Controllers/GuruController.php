<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\JadwalMapel;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\PengajuanIzin;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $guru = $user ? $user->guru : null;

        if (!$guru) {
            return view('guru.dashboard', [
                'guru' => null,
                'mapelList' => collect(),
                'totalMateri' => 0,
                'totalTugas' => 0,
                'pengumuman' => Pengumuman::orderBy('tanggal_upload', 'desc')->limit(5)->get()
            ]);
        }

        $mapelList = GuruMapel::with(['mapel', 'kelas'])
            ->where('id_guru', $guru->id_guru)
            ->get();

        $totalMateri = Materi::where('id_guru', $guru->id_guru)->count();
        $totalTugas = Tugas::where('id_guru', $guru->id_guru)->count();
        $pengumuman = Pengumuman::orderBy('tanggal_upload', 'desc')->limit(5)->get();

        return view('guru.dashboard', compact('guru', 'mapelList', 'totalMateri', 'totalTugas', 'pengumuman'));
    }

    public function detailMapel($id_guru_mapel)
    {
        $user = Auth::user();
        $guru = $user ? $user->guru : null;

        $guruMapel = GuruMapel::with(['mapel', 'kelas', 'jadwalMapel'])
            ->findOrFail($id_guru_mapel);

        $materiList = Materi::where('id_guru', $guruMapel->id_guru)
            ->where('id_mapel', $guruMapel->id_mapel)
            ->where('id_kelas', $guruMapel->id_kelas)
            ->orderBy('tanggal_upload', 'desc')
            ->get();

        $tugasList = Tugas::where('id_guru', $guruMapel->id_guru)
            ->where('id_mapel', $guruMapel->id_mapel)
            ->where('id_kelas', $guruMapel->id_kelas)
            ->orderBy('deadline', 'asc')
            ->get();

        $isWali = $guru ? $guru->waliKelas()->exists() : false;

        return view('guru.kelas.detail', compact('guru', 'guruMapel', 'materiList', 'tugasList', 'isWali'));
    }

    public function tugasIndex()
    {
        $user = Auth::user();
        $guru = $user ? $user->guru : null;

        $tugasByKelas = collect();

        if ($guru) {
            $tugas = Tugas::where('id_guru', $guru->id_guru)->with(['kelas', 'mapel'])->get();

            $tugasByKelas = $tugas->groupBy(function ($item) {
                return optional($item->kelas)->tingkat . ' ' .
                    optional($item->kelas)->jurusan . ' ' .
                    optional($item->kelas)->kelas;
            });
        }

        return view('guru.tugas.index', ['tugas' => $tugasByKelas]);
    }

    public function tugasDetail($id_tugas)
    {
        $tugas = Tugas::with(['mapel', 'kelas', 'guru'])->findOrFail($id_tugas);

        $pengumpulan = PengumpulanTugas::where('id_tugas', $id_tugas)
            ->with('siswa')
            ->orderBy('created_at', 'desc') // ✔ fix
            ->get();

        return view('guru.tugas.detail', compact('tugas', 'pengumpulan'));
    }

    public function kelasTugasIndex($id_guru_mapel)
    {
        $guruMapel = GuruMapel::with(['mapel', 'kelas'])->findOrFail($id_guru_mapel);

        $tugas = Tugas::where([
            'id_guru'  => $guruMapel->id_guru,
            'id_kelas' => $guruMapel->id_kelas,
            'id_mapel' => $guruMapel->id_mapel,
        ])->orderBy('deadline')->get();

        return view('guru.tugas.index', compact('guruMapel', 'tugas'));
    }

    public function kelasTugasCreate($id_guru_mapel)
    {
        $guruMapel = GuruMapel::with(['mapel', 'kelas'])->findOrFail($id_guru_mapel);
        return view('guru.tugas.create', compact('guruMapel'));
    }

    public function kelasTugasStore(Request $request, $id_guru_mapel)
    {
        $guruMapel = GuruMapel::findOrFail($id_guru_mapel);

        $request->validate([
            'judul_tugas' => 'required|max:150',
            'deskripsi'   => 'nullable',
            'deadline'    => 'required|date'
        ]);

        Tugas::create([
            'id_guru'  => $guruMapel->id_guru,
            'id_kelas' => $guruMapel->id_kelas,
            'id_mapel' => $guruMapel->id_mapel,
            'judul_tugas' => $request->judul_tugas,
            'deskripsi'   => $request->deskripsi,
            'deadline'    => $request->deadline,
        ]);

        return redirect()->route('guru.kelas.detail', $id_guru_mapel)
            ->with('success', 'Tugas berhasil dibuat.');
    }

    public function tugasEdit($id_tugas)
    {
        $tugas = Tugas::findOrFail($id_tugas);
        return view('guru.tugas.edit', compact('tugas'));
    }

    public function tugasUpdate(Request $request, $id_tugas)
    {
        $request->validate([
            'judul_tugas' => 'required|max:150',
            'deskripsi'   => 'nullable',
            'deadline'    => 'required|date'
        ]);

        $tugas = Tugas::findOrFail($id_tugas);
        $tugas->update($request->all());

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui');
    }

    public function tugasDelete($id_tugas)
    {
        Tugas::findOrFail($id_tugas)->delete();
        return redirect()->back()->with('success', 'Tugas berhasil dihapus');
    }

    public function absensiIndex()
    { /* ... */
    }
    public function absensiKelola(Request $request)
    { /* ... */
    }
    public function absensiStore(Request $request, $tanggal)
    { /* ... */
    }

    public function izinIndex()
    { /* ... */
    }
    public function izinSetujui($id)
    { /* ... */
    }
    public function izinTolak($id)
    { /* ... */
    }

    public function pengumumanIndex()
    { /* ... */
    }

    public function beriNilai(Request $request, $id_tugas, $id_pengumpulan)
    { /* ... */
    }
}
