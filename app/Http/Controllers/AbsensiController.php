<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\JadwalMapel;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensi = Absensi::with(['siswa', 'jadwal', 'pengajuan'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('absensi.index', compact('absensi'));
    }

    public function create()
    {
        $siswa = Siswa::all();
        $jadwal = JadwalMapel::all();

        return view('absensi.create', compact('siswa', 'jadwal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required',
            'id_jadwal' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alfa',
            'keterangan' => 'nullable|string',
        ]);

        Absensi::create([
            'id_siswa' => $request->id_siswa,
            'id_jadwal' => $request->id_jadwal,
            'id_pengajuan' => $request->id_pengajuan,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $absensi = Absensi::findOrFail($id);
        $siswa = Siswa::all();
        $jadwal = JadwalMapel::all();
        $pengajuan = PengajuanIzin::all();

        return view('absensi.edit', compact(
            'absensi',
            'siswa',
            'jadwal',
            'pengajuan'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alfa',
        ]);

        $absensi = Absensi::findOrFail($id);
        $absensi->update([
            'id_siswa' => $request->id_siswa,
            'id_jadwal' => $request->id_jadwal,
            'id_pengajuan' => $request->id_pengajuan,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil dihapus');
    }
}
