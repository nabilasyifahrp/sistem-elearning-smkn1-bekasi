<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\JadwalMapel;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\PengajuanIzin;
use App\Models\Absensi;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        
        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan.');
        }

        $kelas = $siswa->kelas;

        $jadwalMapel = JadwalMapel::where('id_kelas', $kelas->id_kelas)
            ->with(['guruMapel.mapel', 'guruMapel.guru'])
            ->get()
            ->groupBy('id_guru_mapel');

        $totalMateri = Materi::where('id_kelas', $kelas->id_kelas)->count();
        $totalTugas = Tugas::where('id_kelas', $kelas->id_kelas)->count();
        
        $tugasSelesai = PengumpulanTugas::where('nis', $siswa->nis)
            ->whereNotNull('tanggal_pengumpulan')
            ->count();
        
        $tugasBelumSelesai = $totalTugas - $tugasSelesai;

        $pengumuman = Pengumuman::orderBy('tanggal_upload', 'desc')
            ->limit(5)
            ->get();

        return view('siswa.dashboard', compact(
            'siswa',
            'kelas',
            'jadwalMapel',
            'totalMateri',
            'totalTugas',
            'tugasSelesai',
            'tugasBelumSelesai',
            'pengumuman'
        ));
    }

    public function detailMapel($id_guru_mapel)
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        
        $jadwal = JadwalMapel::where('id_guru_mapel', $id_guru_mapel)
            ->where('id_kelas', $siswa->id_kelas)
            ->with(['guruMapel.mapel', 'guruMapel.guru', 'kelas'])
            ->first();

        if (!$jadwal) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Mata pelajaran tidak ditemukan.');
        }

        $materiList = Materi::where('id_kelas', $siswa->id_kelas)
            ->where('id_mapel', $jadwal->guruMapel->id_mapel)
            ->where('id_guru', $jadwal->guruMapel->id_guru)
            ->orderBy('tanggal_upload', 'desc')
            ->get();

        $tugasList = Tugas::where('id_kelas', $siswa->id_kelas)
            ->where('id_mapel', $jadwal->guruMapel->id_mapel)
            ->where('id_guru', $jadwal->guruMapel->id_guru)
            ->with(['pengumpulan' => function($query) use ($siswa) {
                $query->where('nis', $siswa->nis);
            }])
            ->orderBy('deadline', 'asc')
            ->get();

        return view('siswa.detail_mapel', compact('jadwal', 'materiList', 'tugasList', 'siswa'));
    }

    public function detailTugas($id_tugas)
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        
        $tugas = Tugas::with(['guru', 'mapel', 'kelas'])
            ->findOrFail($id_tugas);

        $pengumpulan = PengumpulanTugas::where('id_tugas', $id_tugas)
            ->where('nis', $siswa->nis)
            ->first();

        return view('siswa.detail_tugas', compact('tugas', 'pengumpulan', 'siswa'));
    }

    public function kumpulkanTugas(Request $request, $id_tugas)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $request->validate([
            'isi_tugas' => 'required',
            'file_path' => 'nullable|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,zip,rar',
        ], [
            'isi_tugas.required' => 'Isi tugas wajib diisi.',
            'file_path.max' => 'Ukuran file maksimal 10MB.',
            'file_path.mimes' => 'Format file tidak didukung.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('pengumpulan_tugas', 'public');
        }

        $pengumpulan = PengumpulanTugas::updateOrCreate(
            [
                'id_tugas' => $id_tugas,
                'nis' => $siswa->nis,
            ],
            [
                'isi_tugas' => $request->isi_tugas,
                'file_path' => $filePath ?? PengumpulanTugas::where('id_tugas', $id_tugas)
                    ->where('nis', $siswa->nis)
                    ->value('file_path'),
                'tanggal_pengumpulan' => now(),
            ]
        );

        return redirect()->route('siswa.detail_tugas', $id_tugas)
            ->with('success', 'Tugas berhasil dikumpulkan!');
    }

    public function absensi()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $absensiList = Absensi::where('nis', $siswa->nis)
            ->with(['jadwal.guruMapel.mapel', 'pengajuan'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        $totalHadir = Absensi::where('nis', $siswa->nis)
            ->where('status', 'hadir')
            ->count();
        
        $totalIzin = Absensi::where('nis', $siswa->nis)
            ->where('status', 'izin')
            ->count();
        
        $totalSakit = Absensi::where('nis', $siswa->nis)
            ->where('status', 'sakit')
            ->count();
        
        $totalAlfa = Absensi::where('nis', $siswa->nis)
            ->where('status', 'alfa')
            ->count();

        return view('siswa.absensi', compact(
            'absensiList',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlfa',
            'siswa'
        ));
    }

    public function pengajuanIzin()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $pengajuanList = PengajuanIzin::where('nis', $siswa->nis)
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(10);

        return view('siswa.pengajuan_izin', compact('pengajuanList', 'siswa'));
    }

    public function createPengajuanIzin()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        return view('siswa.create_pengajuan_izin', compact('siswa'));
    }

    public function storePengajuanIzin(Request $request)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_izin' => 'required|in:sakit,izin',
            'alasan' => 'required|string|max:500',
            'bukti_file' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png',
        ], [
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'jenis_izin.required' => 'Jenis izin wajib dipilih.',
            'alasan.required' => 'Alasan wajib diisi.',
            'alasan.max' => 'Alasan maksimal 500 karakter.',
            'bukti_file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('bukti_file')) {
            $filePath = $request->file('bukti_file')->store('bukti_izin', 'public');
        }

        PengajuanIzin::create([
            'nis' => $siswa->nis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jenis_izin' => $request->jenis_izin,
            'alasan' => $request->alasan,
            'bukti_file' => $filePath,
            'status' => 'pending',
        ]);

        return redirect()->route('siswa.pengajuan_izin')
            ->with('success', 'Pengajuan izin berhasil dikirim. Menunggu persetujuan wali kelas.');
    }


public function editPengajuanIzin($id)
{
    $user = Auth::user();
    $siswa = $user->siswa;

    $pengajuan = PengajuanIzin::where('id_pengajuan', $id)
        ->where('nis', $siswa->nis)
        ->firstOrFail();

    if ($pengajuan->status !== 'pending') {
        return redirect()->route('siswa.pengajuan_izin')
            ->with('error', 'Pengajuan ini tidak dapat diedit karena sudah diproses.');
    }

    return view('siswa.edit_pengajuan_izin', compact('pengajuan', 'siswa'));
}

/**
 * Update pengajuan izin
 */
public function updatePengajuanIzin(Request $request, $id)
{
    $user = Auth::user();
    $siswa = $user->siswa;

    $pengajuan = PengajuanIzin::where('id_pengajuan', $id)
        ->where('nis', $siswa->nis)
        ->firstOrFail();

    if ($pengajuan->status !== 'pending') {
        return redirect()->route('siswa.pengajuan_izin')
            ->with('error', 'Pengajuan ini tidak dapat diedit karena sudah diproses.');
    }

    $request->validate([
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'jenis_izin' => 'required|in:sakit,izin',
        'alasan' => 'required|string|max:500',
        'bukti_file' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png',
    ], [
        'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
        'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
        'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        'jenis_izin.required' => 'Jenis izin wajib dipilih.',
        'alasan.required' => 'Alasan wajib diisi.',
        'alasan.max' => 'Alasan maksimal 500 karakter.',
        'bukti_file.max' => 'Ukuran file maksimal 5MB.',
    ]);

    $filePath = $pengajuan->bukti_file;

    if ($request->hasFile('bukti_file')) {

        if ($pengajuan->bukti_file) {
            Storage::disk('public')->delete($pengajuan->bukti_file);
        }
        $filePath = $request->file('bukti_file')->store('bukti_izin', 'public');
    }

    $pengajuan->update([
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
        'jenis_izin' => $request->jenis_izin,
        'alasan' => $request->alasan,
        'bukti_file' => $filePath,
    ]);

    return redirect()->route('siswa.pengajuan_izin')
        ->with('success', 'Pengajuan izin berhasil diperbarui!');
}

/**
 * Batalkan pengajuan izin
 */
public function cancelPengajuanIzin($id)
{
    $user = Auth::user();
    $siswa = $user->siswa;

    $pengajuan = PengajuanIzin::where('id_pengajuan', $id)
        ->where('nis', $siswa->nis)
        ->firstOrFail();

    if ($pengajuan->status !== 'pending') {
        return redirect()->route('siswa.pengajuan_izin')
            ->with('error', 'Pengajuan ini tidak dapat dibatalkan karena sudah diproses.');
    }

    if ($pengajuan->bukti_file) {
        Storage::disk('public')->delete($pengajuan->bukti_file);
    }

    $pengajuan->delete();

    return redirect()->route('siswa.pengajuan_izin')
        ->with('success', 'Pengajuan izin berhasil dibatalkan.');
}

    public function pengumuman()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $pengumumanList = Pengumuman::orderBy('tanggal_upload', 'desc')
            ->paginate(10);

        return view('siswa.pengumuman', compact('pengumumanList', 'siswa'));
    }

    public function detailPengumuman($id)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $pengumuman = Pengumuman::findOrFail($id);

        return view('siswa.detail_pengumuman', compact('pengumuman', 'siswa'));
    }

    public function ubahPasswordForm()
{
    $user = Auth::user();
    $siswa = $user->siswa;
    
    return view('siswa.ubah_password', compact('siswa'));
}

public function ubahPasswordUpdate(Request $request)
{
    $request->validate([
        'password_lama' => 'required',
        'password_baru' => 'required|min:6|confirmed',
    ], [
        'password_lama.required' => 'Password lama wajib diisi.',
        'password_baru.required' => 'Password baru wajib diisi.',
        'password_baru.min' => 'Password baru minimal 6 karakter.',
        'password_baru.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->password_lama, $user->password)) {
        return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
    }

    $user->password = Hash::make($request->password_baru);
    $user->save();

    return redirect()->route('siswa.dashboard')
        ->with('success', 'Password berhasil diubah!');
}
}