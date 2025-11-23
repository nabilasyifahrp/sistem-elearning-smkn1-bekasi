<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel;
use App\Models\JadwalMapel;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CrudJadwalMapelController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalMapel::with(['kelas', 'guruMapel.guru', 'guruMapel.mapel']);

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('id_guru')) {
            $query->whereHas('guruMapel', function ($q) use ($request) {
                $q->where('id_guru', $request->id_guru);
            });
        }

        if ($request->filled('id_mapel')) {
            $query->whereHas('guruMapel', function ($q) use ($request) {
                $q->where('id_mapel', $request->id_mapel);
            });
        }

        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        $jadwalList = $query->orderBy('hari')->get();

        $kelasList = Kelas::all();
        $guruList = \App\Models\Guru::all();
        $mapelList = \App\Models\Mapel::all();

        return view('admin.jadwal_mapel.index', compact('jadwalList', 'kelasList', 'guruList', 'mapelList'));
    }

    public function create()
    {
        $kelasList = Kelas::all();
        $guruList = \App\Models\Guru::all();
        $mapelList = \App\Models\Mapel::all();

        return view('admin.jadwal_mapel.create', compact('kelasList', 'guruList', 'mapelList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'tipe' => 'required|in:Teori,Tefa',
            'tahun_ajaran' => 'required',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_guru' => 'required|exists:gurus,id_guru',
            'id_mapel' => 'required|exists:mapels,id_mapel',
        ]);

        $validated['jam_mulai'] = date('H:i:s', strtotime($validated['jam_mulai']));
        $validated['jam_selesai'] = date('H:i:s', strtotime($validated['jam_selesai']));

        $guruMapel = GuruMapel::firstOrCreate([
            'id_guru' => $validated['id_guru'],
            'id_mapel' => $validated['id_mapel'],
            'id_kelas' => $validated['id_kelas'],
            'tahun_ajaran' => $validated['tahun_ajaran'],
        ]);

        $validated['id_guru_mapel'] = $guruMapel->id_guru_mapel;

        $cekKelas = JadwalMapel::where('hari', $validated['hari'])
            ->where('id_kelas', $validated['id_kelas'])
            ->where('tahun_ajaran', $validated['tahun_ajaran'])
            ->where(function ($q) use ($validated) {
                $q->where('jam_mulai', '<', $validated['jam_selesai'])
                    ->where('jam_selesai', '>', $validated['jam_mulai']);
            })
            ->exists();

        if ($cekKelas) {
            return back()->withInput()->withErrors([
                'error' => 'Jadwal bentrok! Waktu bertabrakan.'
            ]);
        }

        $cekGuru = JadwalMapel::where('hari', $validated['hari'])
            ->where('tahun_ajaran', $validated['tahun_ajaran'])
            ->whereHas('guruMapel', function ($q) use ($validated) {
                $q->where('id_guru', $validated['id_guru']);
            })
            ->where(function ($q) use ($validated) {
                $q->where('jam_mulai', '<', $validated['jam_selesai'])
                    ->where('jam_selesai', '>', $validated['jam_mulai']);
            })
            ->exists();

        if ($cekGuru) {
            return back()->withInput()->withErrors([
                'error' => 'Guru bentrok mengajar di kelas lain pada waktu ini.'
            ]);
        }

        JadwalMapel::create($validated);

        return redirect()->route('admin.jadwalmapel.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jadwal = JadwalMapel::findOrFail($id);
        $kelasList = Kelas::all();
        $guruList = \App\Models\Guru::all();
        $mapelList = \App\Models\Mapel::all();

        return view('admin.jadwal_mapel.edit', compact('jadwal', 'kelasList', 'guruList', 'mapelList'));
    }

    public function update(Request $request, $id_jadwal)
    {
        $jadwal = JadwalMapel::findOrFail($id_jadwal);

        $validator = Validator::make($request->all(), [
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'tipe' => 'required',
            'tahun_ajaran' => 'required',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_guru' => 'required|exists:gurus,id_guru',
            'id_mapel' => 'required|exists:mapels,id_mapel',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $guruMapel = GuruMapel::firstOrCreate([
            'id_guru' => $request->id_guru,
            'id_mapel' => $request->id_mapel,
            'id_kelas' => $request->id_kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        $request['id_guru_mapel'] = $guruMapel->id_guru_mapel;

        $cekKelas = JadwalMapel::where('hari', $request->hari)
            ->where('id_kelas', $request->id_kelas)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->where('id_jadwal', '!=', $id_jadwal)
            ->where(function ($q) use ($request) {
                $q->where('jam_mulai', '<', $request->jam_selesai)
                    ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($cekKelas) {
            return back()->withErrors(['error' => 'Jadwal bentrok! Waktu bertabrakan.'])->withInput();
        }

        $cekGuru = JadwalMapel::where('hari', $request->hari)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->where('id_jadwal', '!=', $id_jadwal)
            ->whereHas('guruMapel', function ($q) use ($request) {
                $q->where('id_guru', $request->id_guru);
            })
            ->where(function ($q) use ($request) {
                $q->where('jam_mulai', '<', $request->jam_selesai)
                    ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($cekGuru) {
            return back()->withErrors(['error' => 'Guru bentrok mengajar di kelas lain pada waktu ini.'])->withInput();
        }

        $jadwal->update([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tipe' => $request->tipe,
            'tahun_ajaran' => $request->tahun_ajaran,
            'id_kelas' => $request->id_kelas,
            'id_guru_mapel' => $guruMapel->id_guru_mapel,
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function show($id)
    {
        $jadwal = JadwalMapel::with(['kelas', 'guruMapel.guru', 'guruMapel.mapel'])
            ->findOrFail($id);

        return view('admin.jadwal_mapel.show', compact('jadwal'));
    }

    public function destroy($id)
    {
        $jadwal = JadwalMapel::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwalmapel.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }
}
