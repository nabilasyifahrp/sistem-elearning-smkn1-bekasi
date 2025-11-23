<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\GuruMapel;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\PengajuanIzin;
use App\Models\Absensi;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $siswa = $user ? $user->siswa : null;

        if (!$siswa) {
            return view('siswa.dashboard', [
                'siswa' => null,
                'mapelList' => collect(),
                'tugasAktif' => 0,
                'pengumuman' => Pengumuman::orderBy('tanggal_upload', 'desc')->limit(5)->get()
            ]);
        }

        $mapelList = GuruMapel::with(['mapel', 'guru', 'kelas'])
            ->where('id_kelas', $siswa->id_kelas)
            ->where('tahun_ajaran', $siswa->tahun_ajaran)
            ->get();

        $tugasAktif = Tugas::whereHas('guruMapel', function ($q) use ($siswa) {
            $q->where('id_kelas', $siswa->id_kelas)
                ->where('tahun_ajaran', $siswa->tahun_ajaran);
        })->where('deadline', '>=', now())->count();

        $pengumuman = Pengumuman::orderBy('tanggal_upload', 'desc')->limit(5)->get();

        return view('siswa.dashboard', compact('siswa', 'mapelList', 'tugasAktif', 'pengumuman'));
    }

    public function detailMapel($id_guru_mapel)
    {
        $user = Auth::user();
        $siswa = $user ? $user->siswa : null;

        $guruMapel = GuruMapel::with(['mapel', 'kelas', 'guru'])->findOrFail($id_guru_mapel);

        if ($siswa && $guruMapel->id_kelas !== $siswa->id_kelas) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $materiList = Materi::where('id_guru_mapel', $id_guru_mapel)
            ->orderBy('tanggal_upload', 'desc')
            ->get();

        $tugasList = Tugas::where('id_guru_mapel', $id_guru_mapel)
            ->orderBy('deadline', 'asc')
            ->get();

        $today = now()->toDateString();
        $absensiHariIni = null;

        if ($siswa) {
            $absensiHariIni = Absensi::whereHas('jadwal', function ($q) use ($id_guru_mapel) {
                $q->where('id_guru_mapel', $id_guru_mapel);
            })
                ->where('nis', $siswa->nis)
                ->where('tanggal', $today)
                ->first();
        }

        $izinHariIni = null;
        if ($siswa) {
            $izinHariIni = PengajuanIzin::where('nis', $siswa->nis)
                ->where('tanggal_mulai', '<=', $today)
                ->where('tanggal_selesai', '>=', $today)
                ->whereIn('status', ['pending', 'disetujui'])
                ->first();
        }

        return view('siswa.kelas.detail', compact(
            'siswa',
            'guruMapel',
            'materiList',
            'tugasList',
            'absensiHariIni',
            'izinHariIni'
        ));
    }
    public function absenHadir($id_guru_mapel)
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $today = now()->toDateString();

        $absensi = Absensi::whereHas('jadwal', function ($q) use ($id_guru_mapel) {
            $q->where('id_guru_mapel', $id_guru_mapel);
        })
            ->where('nis', $siswa->nis)
            ->where('tanggal', $today)
            ->first();

        if (!$absensi) {
            return back()->with('error', 'Sesi absensi belum dibuka oleh guru!');
        }

        if ($absensi->id_pengajuan) {
            return back()->with('error', 'Anda sudah tercatat izin hari ini!');
        }

        if ($absensi->status === 'alfa' && $absensi->keterangan !== null) {
            return back()->with('error', 'Sesi absensi sudah ditutup!');
        }

        $absensi->update([
            'status' => 'hadir',
            'keterangan' => 'Hadir',
        ]);

        return back()->with('success', 'Absensi berhasil! Anda tercatat hadir.');
    }

    public function tugasIndex()
    {
        $user = Auth::user();
        $siswa = $user ? $user->siswa : null;

        $tugasList = collect();

        if ($siswa) {
            $tugasList = Tugas::whereHas('guruMapel', function ($q) use ($siswa) {
                $q->where('id_kelas', $siswa->id_kelas)
                    ->where('tahun_ajaran', $siswa->tahun_ajaran);
            })->with(['guruMapel.kelas', 'guruMapel.mapel'])
                ->orderBy('deadline', 'asc')
                ->get();
        }

        return view('siswa.tugas.index', compact('tugasList', 'siswa'));
    }

    public function tugasDetail($id_tugas)
    {
        $user = Auth::user();
        $siswa = $user ? $user->siswa : null;

        $tugas = Tugas::with(['guruMapel.mapel', 'guruMapel.kelas'])->findOrFail($id_tugas);

        if ($siswa && $tugas->guruMapel->id_kelas !== $siswa->id_kelas) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $pengumpulan = PengumpulanTugas::where('id_tugas', $id_tugas)
            ->where('nis', $siswa->nis)
            ->first();
        $pengumpulan = PengumpulanTugas::where('id_tugas', $id_tugas)
            ->where('nis', $siswa->nis)
            ->first();

        return view('siswa.tugas.detail', compact('tugas', 'pengumpulan', 'siswa'));
    }

    public function tugasKumpul(Request $request, $id_tugas)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $request->validate([
            'jawaban' => 'nullable|string',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar,ppt,pptx,xls,xlsx',
        ]);

        if (!$request->jawaban && !$request->hasFile('file_tugas')) {
            return redirect()->back()->with('error', 'Isi jawaban atau unggah file.');
        }
        $request->validate([
            'jawaban' => 'nullable|string',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar,ppt,pptx,xls,xlsx',
        ]);

        if (!$request->jawaban && !$request->hasFile('file_tugas')) {
            return redirect()->back()->with('error', 'Isi jawaban atau unggah file.');
        }

        $tugas = Tugas::findOrFail($id_tugas);

        if ($tugas->guruMapel->id_kelas !== $siswa->id_kelas) {
            abort(403);
            abort(403);
        }

        $existing = PengumpulanTugas::where('id_tugas', $id_tugas)
            ->where('nis', $siswa->nis)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengumpulkan tugas ini');
            return back()->with('error', 'Anda sudah mengumpulkan tugas ini');
        }

        $filePath = null;
        if ($request->hasFile('file_tugas')) {
            $file = $request->file('file_tugas');
            $fileName = time() . $siswa->nis . $file->getClientOriginalName();
            $filePath = $file->storeAs('pengumpulan_tugas', $fileName, 'public');
            $file = $request->file('file_tugas');
            $fileName = time() . $siswa->nis . $file->getClientOriginalName();
            $filePath = $file->storeAs('pengumpulan_tugas', $fileName, 'public');
        }

        $status = now() > $tugas->deadline ? 'Terlambat' : 'Sudah Dikumpulkan';

        PengumpulanTugas::create([
            'id_tugas' => $id_tugas,
            'nis' => $siswa->nis,
            'isi_tugas' => $request->jawaban,
            'jawaban' => $request->jawaban,
            'isi_tugas' => $request->jawaban,
            'jawaban' => $request->jawaban,
            'file_path' => $filePath,
            'file_pengumpulan' => $filePath,
            'file_pengumpulan' => $filePath,
            'status' => $status,
            'waktu_pengumpulan' => now(),
            'waktu_pengumpulan' => now(),
            'tanggal_pengumpulan' => now()
        ]);

        return redirect()->route('siswa.tugas.detail', $id_tugas)
            ->with('success', 'Tugas berhasil dikumpulkan!');
    }

    public function tugasBatalkan($id_tugas)
    {
        $siswa = Auth::user()->siswa;

        $pengumpulan = PengumpulanTugas::where('id_tugas', $id_tugas)
            ->where('nis', $siswa->nis)
            ->first();

        if (!$pengumpulan) {
            return back()->with('error', 'Pengumpulan tidak ditemukan.');
        }

        if ($pengumpulan->nilai !== null) {
            return back()->with('error', 'Tugas sudah dinilai, tidak bisa dibatalkan.');
        }

        if ($pengumpulan->file_pengumpulan && Storage::exists('public/' . $pengumpulan->file_pengumpulan)) {
            Storage::delete('public/' . $pengumpulan->file_pengumpulan);
        }

        $pengumpulan->delete();

        return back()->with('success', 'Pengumpulan tugas dibatalkan.');
    }

    public function absensiIndex()
    {
        $user = Auth::user();
        $siswa = $user ? $user->siswa : null;

        $absensiList = Absensi::where('nis', $siswa->nis)
            ->with(['jadwal.guruMapel.mapel'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);
        $absensiList = Absensi::where('nis', $siswa->nis)
            ->with(['jadwal.guruMapel.mapel'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('siswa.absensi.index', compact('absensiList', 'siswa'));
    }

    public function izinIndex()
    {
        $siswa = Auth::user()->siswa;
        $siswa = Auth::user()->siswa;

        $izinList = PengajuanIzin::where('nis', $siswa->nis)
            ->orderBy('created_at', 'desc')
            ->get();
        $izinList = PengajuanIzin::where('nis', $siswa->nis)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.izin.index', compact('izinList', 'siswa'));
    }

    public function izinCreate()
    {
        $siswa = Auth::user()->siswa;
        $siswa = Auth::user()->siswa;
        return view('siswa.izin.create', compact('siswa'));
    }

    public function izinStore(Request $request)
    {
        $siswa = Auth::user()->siswa;

        $request->validate([
            'jenis_izin' => 'required|in:izin,sakit',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string',
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        $filePath = null;
        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            $fileName = time() . $siswa->nis . $file->getClientOriginalName();
            $filePath = $file->storeAs('bukti_izin', $fileName, 'public');
        }

        PengajuanIzin::create([
            'nis' => $siswa->nis,
            'jenis_izin' => $request->jenis_izin,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'bukti_file' => $filePath,
            'status' => 'pending',
        ]);

        return redirect()->route('siswa.izin.index')
            ->with('success', 'Pengajuan izin berhasit dikirim.');
    }

    public function batalkanIzin($id)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $izin = PengajuanIzin::where('id_pengajuan', $id)
            ->where('nis', $siswa->nis)
            ->firstOrFail();

        if ($izin->status !== 'pending') {
            return back()->with('error', 'Pengajuan izin sudah diproses dan tidak dapat dibatalkan.');
        }

        $izin->delete();

        return back()->with('success', 'Pengajuan izin berhasil dibatalkan.');
    }
    public function pengumumanIndex()
    {
        $pengumumanList = Pengumuman::orderBy('tanggal_upload', 'desc')->paginate(10);
        return view('siswa.pengumuman.index', compact('pengumumanList'));
    }

    public function pengumumanShow($id)
    {
        $data = Pengumuman::findOrFail($id);
        return view('siswa.pengumuman.show', compact('data'));
    }

    public function profileIndex()
    {
        $siswa = Auth::user()->siswa;
        $siswa = Auth::user()->siswa;
        return view('siswa.profile.index', compact('siswa'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password_baru' => 'nullable|min:6|confirmed'
        ]);

        if ($request->filled('password_baru')) {
            $user->password = Hash::make($request->password_baru);
            $user->save();

            return redirect()->route('siswa.profile.index')
                ->with('success', 'Password berhasil diperbarui!');
        }

        return redirect()->route('siswa.profile.index')
            ->with('info', 'Tidak ada perubahan.');
    }

    public function rekapAbsensi(Request $request)
    {
        $siswa = Auth::user()->siswa;

        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $absensi = Absensi::where('nis', $siswa->nis)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where(function ($q) {
                $q->where('status', '!=', 'alfa')
                    ->orWhere(function ($sub) {
                        $sub->where('status', 'alfa')
                            ->whereNotNull('keterangan');
                    });
            })
            ->with(['jadwal.guruMapel.mapel'])
            ->get();

        $rekap = [
            'pertemuan' => $absensi->count(),
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'izin'  => $absensi->where('status', 'izin')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'alfa'  => $absensi->where('status', 'alfa')->count(),
        ];

        return view('siswa.absensi.rekap', compact('rekap', 'absensi', 'bulan', 'tahun', 'siswa'));
    }
}
