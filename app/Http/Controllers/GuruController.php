<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\PengajuanIzin;
use App\Models\Absensi;
use App\Models\JadwalMapel;
use App\Models\Siswa;
use App\Models\Pengumuman;
use App\Models\WaliKelas;
use Illuminate\Support\Facades\Hash;

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

        $totalMateri = Materi::whereHas('guruMapel', function ($q) use ($guru) {
            $q->where('id_guru', $guru->id_guru);
        })->count();

        $totalTugas = Tugas::whereHas('guruMapel', function ($q) use ($guru) {
            $q->where('id_guru', $guru->id_guru);
        })->count();

        $pengumuman = Pengumuman::orderBy('tanggal_upload', 'desc')->limit(5)->get();

        return view('guru.dashboard', compact('guru', 'mapelList', 'totalMateri', 'totalTugas', 'pengumuman'));
    }

    public function detailMapel($id_guru_mapel)
    {
        $user = Auth::user();
        $guru = $user ? $user->guru : null;

        $guruMapel = GuruMapel::with(['mapel', 'kelas'])->findOrFail($id_guru_mapel);

        $materiList = Materi::where('id_guru_mapel', $id_guru_mapel)
            ->orderBy('tanggal_upload', 'desc')
            ->get();

        $tugasList = Tugas::where('id_guru_mapel', $id_guru_mapel)
            ->orderBy('deadline', 'asc')
            ->get();

        $isWali = $guru ? $guru->waliKelas()->exists() : false;

        $rekapAbsensi = Absensi::where('id_jadwal', $id_guru_mapel)
            ->with('siswa')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('guru.kelas.detail', compact(
            'guru',
            'guruMapel',
            'materiList',
            'tugasList',
            'isWali',
            'rekapAbsensi'
        ));
    }

    public function tugasIndex()
    {
        $user = Auth::user();
        $guru = $user ? $user->guru : null;

        $tugasByKelas = collect();

        if ($guru) {
            $tugas = Tugas::whereHas('guruMapel', function ($q) use ($guru) {
                $q->where('id_guru', $guru->id_guru);
            })->with(['guruMapel.kelas', 'guruMapel.mapel'])->get();

            $tugasByKelas = $tugas->groupBy(function ($item) {
                $k = $item->guruMapel->kelas;
                return $k->tingkat . ' ' . $k->jurusan . ' ' . $k->kelas;
            });
        }

        return view('guru.tugas.index', ['tugas' => $tugasByKelas]);
    }

    public function tugasDetail($id_tugas)
    {
        $tugas = Tugas::with(['guruMapel.mapel', 'guruMapel.kelas'])->findOrFail($id_tugas);

        $pengumpulan = PengumpulanTugas::where('id_tugas', $id_tugas)
            ->with('siswa')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.tugas.detail', compact('tugas', 'pengumpulan'));
    }

    public function kelasTugasIndex($id_guru_mapel)
    {
        $guruMapel = GuruMapel::with(['mapel', 'kelas'])->findOrFail($id_guru_mapel);

        $tugas = Tugas::where('id_guru_mapel', $id_guru_mapel)
            ->orderBy('deadline')
            ->get();

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
            'deadline'    => 'required|date',
            'file_tugas'  => 'nullable|mimes:pdf,doc,docx,jpg,png,zip|max:2048'
        ]);

        $filePath = null;

        if ($request->hasFile('file_tugas')) {
            $filePath = $request->file('file_tugas')->store('tugas_files', 'public');
        }

        Tugas::create([
            'id_guru_mapel' => $guruMapel->id_guru_mapel,
            'judul_tugas'   => $request->judul_tugas,
            'deskripsi'     => $request->deskripsi,
            'deadline'      => $request->deadline,
            'file_path'     => $filePath,
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
            'deadline'    => 'required|date',
            'file_tugas'  => 'nullable|mimes:pdf,doc,docx,jpg,png,zip|max:2048'
        ]);

        $tugas = Tugas::findOrFail($id_tugas);

        if ($request->hasFile('file_tugas')) {
            $path = $request->file('file_tugas')->store('tugas_files', 'public');
            $tugas->file_path = $path;
        }

        $tugas->judul_tugas = $request->judul_tugas;
        $tugas->deskripsi = $request->deskripsi;
        $tugas->deadline = $request->deadline;
        $tugas->save();

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui');
    }

    public function tugasDelete($id_tugas)
    {
        $tugas = Tugas::findOrFail($id_tugas);

        if ($tugas->file_path && file_exists(storage_path('app/public/' . $tugas->file_path))) {
            unlink(storage_path('app/public/' . $tugas->file_path));
        }

        $tugas->delete();

        return redirect()->back()->with('success', 'Tugas berhasil dihapus');
    }

    public function kelasMateriIndex($id_guru_mapel)
    {
        $guruMapel = GuruMapel::with(['mapel', 'kelas'])->findOrFail($id_guru_mapel);

        $materi = Materi::where('id_guru_mapel', $id_guru_mapel)
            ->orderBy('tanggal_upload', 'desc')
            ->get();

        return view('guru.materi.index', compact('guruMapel', 'materi'));
    }

    public function kelasMateriCreate($id_guru_mapel)
    {
        $guruMapel = GuruMapel::with(['mapel', 'kelas'])->findOrFail($id_guru_mapel);

        return view('guru.materi.create', compact('guruMapel'));
    }

    public function kelasMateriStore(Request $request, $id_guru_mapel)
    {
        $guruMapel = GuruMapel::findOrFail($id_guru_mapel);

        $request->validate([
            'judul_materi' => 'required|max:150',
            'deskripsi'    => 'nullable',
            'file_materi'  => 'nullable|mimes:pdf,doc,docx,ppt,pptx,zip,jpg,png|max:20480',
        ]);

        $path = null;
        if ($request->hasFile('file_materi')) {
            $path = $request->file('file_materi')->store('materi_files', 'public');
        }

        Materi::create([
            'id_guru_mapel' => $id_guru_mapel,
            'judul_materi'  => $request->judul_materi,
            'deskripsi'     => $request->deskripsi,
            'file_path'     => $path,
            'tanggal_upload' => date('Y-m-d'),
        ]);

        return redirect()->route('guru.kelas.detail', $id_guru_mapel)
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    public function materiDetail($id_materi)
    {
        $materi = Materi::with(['guruMapel.mapel', 'guruMapel.kelas'])->findOrFail($id_materi);

        return view('guru.materi.detail', compact('materi'));
    }

    public function materiEdit($id_materi)
    {
        $materi = Materi::findOrFail($id_materi);

        return view('guru.materi.edit', compact('materi'));
    }

    public function materiUpdate(Request $request, $id_materi)
    {
        $materi = Materi::findOrFail($id_materi);

        $request->validate([
            'judul_materi' => 'required|max:150',
            'deskripsi'    => 'nullable',
            'file_materi'  => 'nullable|mimes:pdf,doc,docx,ppt,pptx,zip,jpg,png|max:4096',
        ]);

        if ($request->hasFile('file_materi')) {
            $path = $request->file('file_materi')->store('materi_files', 'public');
            $materi->file_path = $path;
        }

        $materi->judul_materi = $request->judul_materi;
        $materi->deskripsi    = $request->deskripsi;
        $materi->save();

        return redirect()->back()->with('success', 'Materi berhasil diperbarui.');
    }

    public function materiDelete($id_materi)
    {
        $materi = Materi::findOrFail($id_materi);

        if ($materi->file_path && file_exists(storage_path('app/public/' . $materi->file_path))) {
            unlink(storage_path('app/public/' . $materi->file_path));
        }

        $materi->delete();

        return redirect()->back()->with('success', 'Materi berhasil dihapus.');
    }

    public function pengumumanIndex()
    {
        $pengumuman = Pengumuman::orderBy('tanggal_upload', 'desc')->get();

        return view('guru.pengumuman.index', compact('pengumuman'));
    }

    public function detailTugasSiswa($id_tugas, $id_pengumpulan)
    {
        $tugas = Tugas::where('id_tugas', $id_tugas)->firstOrFail();
        $pengumpulan = PengumpulanTugas::where('id_pengumpulan', $id_pengumpulan)
            ->where('id_tugas', $id_tugas)
            ->with('siswa')
            ->firstOrFail();

        return view('guru.tugas.detail-tugas-siswa', compact('tugas', 'pengumpulan'));
    }

    public function nilaiPengumpulan(Request $request, $id_tugas, $id_pengumpulan)
    {
        $request->validate([
            'nilai' => ['required', 'regex:/^\d+([,.]\d+)?$/'],
            'feedback' => 'nullable|string'
        ]);

        $nilai = str_replace(',', '.', $request->nilai);

        $pengumpulan = PengumpulanTugas::findOrFail($id_pengumpulan);

        $pengumpulan->nilai = $nilai;
        $pengumpulan->feedback = $request->feedback;
        $pengumpulan->save();

        return back()->with('success', 'Nilai & feedback berhasil disimpan!');
    }

    public function submitNilai(Request $request, $id_tugas, $id_pengumpulan)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $pengumpulan = PengumpulanTugas::where('id_pengumpulan', $id_pengumpulan)->firstOrFail();

        $pengumpulan->nilai = $request->nilai;
        $pengumpulan->feedback = $request->feedback;
        $pengumpulan->save();

        return redirect()->back()->with('success', 'Grade submitted successfully.');
    }

    public function profileIndex()
    {
        $user = Auth::user();
        $guru = $user->guru;
        return view('guru.profile.index', compact('guru'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'password_baru' => 'nullable|min:6|confirmed'
        ], [
            'password_baru.min' => 'Password minimal 6 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        if ($request->filled('password_baru')) {
            $user->password = Hash::make($request->password_baru);
            $user->save();

            return redirect()->route('guru.profile.index')->with('success', 'Password berhasil diperbarui!');
        }

        return redirect()->route('guru.profile.index')->with('info', 'Tidak ada perubahan pada akun Anda.');
    }

    public function izinIndex()
    {
        $guru = Auth::user()->guru;
        $waliKelas = $guru->waliKelas;

        if (!$waliKelas) {
            $pengajuanIzin = collect();
        } else {
            $pengajuanIzin = PengajuanIzin::whereIn('nis', function ($query) use ($waliKelas) {
                $query->select('nis')
                    ->from('siswas')
                    ->where('id_kelas', $waliKelas->id_kelas);
            })->orderBy('tanggal_mulai', 'desc')->get();
        }

        return view('guru.izin.index', compact('pengajuanIzin'));
    }

    public function izinSetujui($id)
    {
        $izin = PengajuanIzin::findOrFail($id);
        $izin->status = 'disetujui';
        $izin->save();

        return redirect()->route('guru.izin.index')->with('success', 'Pengajuan izin disetujui.');
    }

    public function izinTolak($id)
    {
        $izin = PengajuanIzin::findOrFail($id);
        $izin->status = 'ditolak';
        $izin->save();

        return redirect()->route('guru.izin.index')->with('success', 'Pengajuan izin ditolak.');
    }

    public function bukaAbsensi($id_guru_mapel)
    {
        $jadwal = JadwalMapel::where('id_guru_mapel', $id_guru_mapel)->first();

        $kelas = $jadwal->kelas;

        $siswaList = Siswa::where('id_kelas', $kelas->id_kelas)->get();

        foreach ($siswaList as $siswa) {
            Absensi::create([
                'nis' => $siswa->nis,
                'id_jadwal' => $jadwal->id_jadwal,
                'tanggal' => now()->toDateString(),
                'status' => 'alfa',
            ]);
        }

        return back()->with('success', 'Sesi absensi dibuka untuk semua siswa.');
    }

    public function siswaHadir($id_jadwal)
    {
        Absensi::create([
            'nis' => auth()->user()->nis,
            'id_jadwal' => $id_jadwal,
            'tanggal' => now()->toDateString(),
            'status' => 'hadir',
        ]);

        return back()->with('success', 'Terima kasih, absensi berhasil!');
    }

    public function rekapAbsensiKelas($id_kelas)
    {
        $rekap = Absensi::whereHas('siswa', function ($q) use ($id_kelas) {
            $q->where('id_kelas', $id_kelas);
        })->orderBy('tanggal', 'desc')->get();

        return view('guru.rekap-absensi', compact('rekap'));
    }
}
