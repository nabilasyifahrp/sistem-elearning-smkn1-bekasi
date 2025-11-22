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
use Illuminate\Support\Facades\DB;
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

        return view('guru.kelas.detail', compact(
            'guru',
            'guruMapel',
            'materiList',
            'tugasList',
            'isWali',
        ));
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

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan.');
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

    public function tugasDetail($id_tugas)
    {
        $tugas = Tugas::with(['guruMapel.mapel', 'guruMapel.kelas'])->findOrFail($id_tugas);

        $pengumpulan = PengumpulanTugas::where('id_tugas', $id_tugas)
            ->with('siswa')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.tugas.detail', compact('tugas', 'pengumpulan'));
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

        return redirect()->back()->with('success', 'Materi berhasil ditambahkan');
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

    public function pengumumanShow($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('guru.pengumuman.show', compact('pengumuman'));
    }

    public function detailTugasSiswa($id_tugas, $id_pengumpulan)
    {
        $tugas = Tugas::where('id_tugas', $id_tugas)->firstOrFail();
        $pengumpulan = PengumpulanTugas::where('id_pengumpulan', $id_pengumpulan)
            ->where('id_tugas', $id_tugas)
            ->with('siswa.user')
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
        $pengumpulan->feedback = $request->feedback ?? null;
        $pengumpulan->save();

        return back()->with('success', 'Nilai berhasil disimpan!');
    }

    public function submitNilai(Request $request, $id_tugas, $id_pengumpulan)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $pengumpulan = PengumpulanTugas::where('id_pengumpulan', $id_pengumpulan)->firstOrFail();

        $pengumpulan->nilai = $request->nilai;
        $pengumpulan->feedback = $request->feedback ?? null;
        $pengumpulan->save();

        return redirect()->back()->with('success', 'Nilai berhasil diberikan!');
    }


    public function detailSiswa($id_guru_mapel, $nis)
    {
        $guruMapel = GuruMapel::with(['mapel', 'kelas.siswa'])->findOrFail($id_guru_mapel);

        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        $tugasList = Tugas::where('id_guru_mapel', $id_guru_mapel)->get();

        $pengumpulan = PengumpulanTugas::where('nis', $nis)->get()->keyBy('id_tugas');

        return view('guru.kelas.siswa-detail', compact(
            'guruMapel',
            'siswa',
            'tugasList',
            'pengumpulan'
        ));
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

    public function absensiKelas($id_guru_mapel)
    {
        $user = Auth::user();
        $guru = $user ? $user->guru : null;

        $guruMapel = GuruMapel::with(['mapel', 'kelas.siswa'])
            ->findOrFail($id_guru_mapel);

        $today = now()->toDateString();

        $absensiHariIni = Absensi::whereHas('jadwal', function ($q) use ($id_guru_mapel) {
            $q->where('id_guru_mapel', $id_guru_mapel);
        })
            ->where('tanggal', $today)
            ->get()
            ->keyBy('nis');

        $belumAbsen = $absensiHariIni
            ->filter(fn($a) => $a->status === 'alfa' && $a->keterangan === null)
            ->count();

        $alfa = $absensiHariIni
            ->filter(fn($a) => $a->status === 'alfa' && $a->keterangan !== null)
            ->count();

        $siswaIzin = PengajuanIzin::where('status', 'disetujui')
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->whereIn('nis', $guruMapel->kelas->siswa->pluck('nis'))
            ->get()
            ->keyBy('nis');

        return view('guru.absensi.kelas', compact(
            'guru',
            'guruMapel',
            'absensiHariIni',
            'siswaIzin',
            'belumAbsen',
            'alfa'
        ));
    }

    public function bukaSesiAbsensi(Request $request, $id_guru_mapel)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $guruMapel = GuruMapel::with('kelas.siswa')
            ->findOrFail($id_guru_mapel);

        $tanggal = $request->tanggal;

        $jadwal = JadwalMapel::where('id_guru_mapel', $id_guru_mapel)->first();

        if (!$jadwal) {
            return back()->with('error', 'Jadwal tidak ditemukan untuk kelas ini!');
        }

        $sudahAda = Absensi::where('id_jadwal', $jadwal->id_jadwal)
            ->where('tanggal', $tanggal)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Sesi absensi untuk tanggal ini sudah dibuka!');
        }

        $siswaIzin = PengajuanIzin::where('status', 'disetujui')
            ->where('tanggal_mulai', '<=', $tanggal)
            ->where('tanggal_selesai', '>=', $tanggal)
            ->whereIn('nis', $guruMapel->kelas->siswa->pluck('nis'))
            ->get()
            ->keyBy('nis');

        DB::beginTransaction();

        try {
            foreach ($guruMapel->kelas->siswa as $siswa) {

                if (isset($siswaIzin[$siswa->nis])) {
                    $izin = $siswaIzin[$siswa->nis];

                    $status = $izin->jenis_izin === 'sakit' ? 'sakit' : 'izin';

                    Absensi::create([
                        'nis' => $siswa->nis,
                        'id_jadwal' => $jadwal->id_jadwal,
                        'id_pengajuan' => $izin->id_pengajuan,
                        'tanggal' => $tanggal,
                        'status' => $status,
                        'keterangan' => 'Otomatis: Izin disetujui wali kelas',
                    ]);
                } else {
                    Absensi::create([
                        'nis' => $siswa->nis,
                        'id_jadwal' => $jadwal->id_jadwal,
                        'tanggal' => $tanggal,
                        'status' => 'alfa',
                        'keterangan' => null,
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Sesi absensi berhasil dibuka!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with(
                'error',
                'Terjadi kesalahan saat membuka sesi absensi: ' . $e->getMessage()
            );
        }
    }

    public function tutupSesiAbsensi($id_guru_mapel, $tanggal)
    {
        Absensi::whereHas('jadwal', function ($q) use ($id_guru_mapel) {
            $q->where('id_guru_mapel', $id_guru_mapel);
        })
            ->where('tanggal', $tanggal)
            ->where('status', 'alfa')
            ->whereNull('id_pengajuan')
            ->update([
                'keterangan' => 'Tidak hadir dan tidak ada keterangan'
            ]);

        return back()->with('success', 'Sesi absensi ditutup. Siswa yang tidak hadir tetap alfa.');
    }

    public function rekapAbsensi(Request $request, $id_guru_mapel)
    {
        $guruMapel = GuruMapel::with(['mapel', 'kelas.siswa'])
            ->findOrFail($id_guru_mapel);

        $bulan = $request->input('bulan', now()->format('m'));
        $tahun = $request->input('tahun', now()->format('Y'));

        $absensiData = Absensi::whereHas('jadwal', function ($q) use ($id_guru_mapel) {
            $q->where('id_guru_mapel', $id_guru_mapel);
        })
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $rekap = $guruMapel->kelas->siswa->map(function ($siswa) use ($absensiData) {
            $absensiSiswa = $absensiData->where('nis', $siswa->nis);

            return [
                'siswa' => $siswa,
                'total' => $absensiSiswa->count(),
                'hadir' => $absensiSiswa->where('status', 'hadir')->count(),
                'izin'  => $absensiSiswa->where('status', 'izin')->count(),
                'sakit' => $absensiSiswa->where('status', 'sakit')->count(),
                'alfa'  => $absensiSiswa->where('status', 'alfa')->count(),
            ];
        });

        return view('guru.absensi.rekap', compact('guruMapel', 'rekap', 'bulan', 'tahun'));
    }
}
