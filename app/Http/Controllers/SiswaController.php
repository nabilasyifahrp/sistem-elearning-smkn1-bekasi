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

        return view('siswa.kelas.detail', compact('siswa', 'guruMapel', 'materiList', 'tugasList'));
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

        $pengumpulan = null;
        if ($siswa) {
            $pengumpulan = PengumpulanTugas::where('id_tugas', $id_tugas)
                ->where('nis', $siswa->nis)
                ->first();
        }

        return view('siswa.tugas.detail', compact('tugas', 'pengumpulan', 'siswa'));
    }

    public function tugasKumpul(Request $request, $id_tugas)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $request->validate([
            'isi_tugas' => 'required|string',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:5120'
        ], [
            'isi_tugas.required' => 'Isi tugas wajib diisi.',
            'file_tugas.mimes' => 'Format file tidak didukung. Gunakan: PDF, DOC, DOCX, JPG, PNG, atau ZIP.',
            'file_tugas.max' => 'Ukuran file maksimal 5MB.'
        ]);

        $tugas = Tugas::findOrFail($id_tugas);

        if ($tugas->guruMapel->id_kelas !== $siswa->id_kelas) {
            abort(403, 'Anda tidak memiliki akses untuk mengumpulkan tugas ini.');
        }

        $existing = PengumpulanTugas::where('id_tugas', $id_tugas)
            ->where('nis', $siswa->nis)
            ->first();

        if ($existing) {
            return redirect()->route('siswa.tugas.detail', $id_tugas)
                ->with('error', 'Anda sudah mengumpulkan tugas ini sebelumnya.');
        }

        $filePath = null;
        if ($request->hasFile('file_tugas')) {
            $filePath = $request->file('file_tugas')->store('pengumpulan_tugas', 'public');
        }

        $status = now() > $tugas->deadline ? 'Terlambat' : 'Sudah Dikumpulkan';

        PengumpulanTugas::create([
            'id_tugas' => $id_tugas,
            'nis' => $siswa->nis,
            'isi_tugas' => $request->isi_tugas,
            'file_path' => $filePath,
            'status' => $status,
            'tanggal_pengumpulan' => now()
        ]);

        return redirect()->route('siswa.tugas.detail', $id_tugas)
            ->with('success', 'Tugas berhasil dikumpulkan!');
    }

    public function absensiIndex()
    {
        $user = Auth::user();
        $siswa = $user ? $user->siswa : null;

        $absensiList = collect();
        if ($siswa) {
            $absensiList = Absensi::where('nis', $siswa->nis)
                ->with(['jadwal.guruMapel.mapel'])
                ->orderBy('tanggal', 'desc')
                ->paginate(15);
        }

        return view('siswa.absensi.index', compact('absensiList', 'siswa'));
    }

    public function izinIndex()
    {
        $user = Auth::user();
        $siswa = $user ? $user->siswa : null;

        $izinList = collect();
        if ($siswa) {
            $izinList = PengajuanIzin::where('nis', $siswa->nis)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('siswa.izin.index', compact('izinList', 'siswa'));
    }

    public function izinCreate()
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        
        return view('siswa.izin.create', compact('siswa'));
    }

    public function izinStore(Request $request)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_izin' => 'required|in:sakit,izin',
            'alasan' => 'required|string|min:10',
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ], [
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'alasan.min' => 'Alasan minimal 10 karakter.',
            'bukti_file.max' => 'Ukuran file maksimal 2MB.'
        ]);

        $filePath = null;
        if ($request->hasFile('bukti_file')) {
            $filePath = $request->file('bukti_file')->store('izin_files', 'public');
        }

        PengajuanIzin::create([
            'nis' => $siswa->nis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jenis_izin' => $request->jenis_izin,
            'alasan' => $request->alasan,
            'bukti_file' => $filePath,
            'status' => 'pending'
        ]);

        return redirect()->route('siswa.izin.index')
            ->with('success', 'Pengajuan izin berhasil dikirim!');
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
        $user = Auth::user();
        $siswa = $user->siswa;
        return view('siswa.profile.index', compact('siswa'));
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

            return redirect()->route('siswa.profile.index')
                ->with('success', 'Password berhasil diperbarui!');
        }

        return redirect()->route('siswa.profile.index')
            ->with('info', 'Tidak ada perubahan.');
    }
}