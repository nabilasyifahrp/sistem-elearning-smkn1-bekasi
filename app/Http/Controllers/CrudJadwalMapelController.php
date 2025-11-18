<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel;
use App\Models\JadwalMapel;
use App\Models\Kelas;
use Illuminate\Http\Request;

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
        $messages = [
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_mulai.after_or_equal' => 'Jam mulai harus 06:30 atau lebih.',
            'jam_mulai.before_or_equal' => 'Jam mulai maksimal 17:00.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'jam_selesai.before_or_equal' => 'Jam selesai maksimal jam 17:00.',
            'hari.required' => 'Hari wajib dipilih.',
            'tipe.required' => 'Tipe wajib dipilih.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'id_kelas.required' => 'Kelas wajib dipilih.',
            'id_guru.required' => 'Guru wajib dipilih.',
            'id_mapel.required' => 'Mapel wajib dipilih.',
        ];

        $validated = $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai' => 'required|after_or_equal:06:30|before_or_equal:17:00',
            'jam_selesai' => 'required|after:jam_mulai|before_or_equal:17:00',
            'tipe' => 'required|in:Teori,Tefa',
            'tahun_ajaran' => 'required|string|max:9',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_guru' => 'required|exists:gurus,id_guru',
            'id_mapel' => 'required|exists:mapels,id_mapel',
        ], $messages);

        try {
            $guruMapel = GuruMapel::firstOrCreate([
                'id_guru' => $validated['id_guru'],
                'id_mapel' => $validated['id_mapel'],
                'id_kelas' => $validated['id_kelas'],
                'tahun_ajaran' => $validated['tahun_ajaran'],
            ]);

            $validated['id_guru_mapel'] = $guruMapel->id_guru_mapel;

            JadwalMapel::create($validated);

            return redirect()->route('admin.jadwalmapel.index')
                ->with('success', 'Jadwal berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'error' => 'Jadwal bentrok (hari, jam, kelas, dan tahun ajaran harus unik).'
            ]);
        }
    }

    public function edit($id)
    {
        $jadwal = JadwalMapel::findOrFail($id);
        $kelasList = Kelas::all();
        $guruList = \App\Models\Guru::all();
        $mapelList = \App\Models\Mapel::all();

        return view('admin.jadwal_mapel.edit', compact('jadwal', 'kelasList', 'guruList', 'mapelList'));
    }


    public function update(Request $request, $id)
    {
        $jadwal = JadwalMapel::findOrFail($id);

        $messages = [
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_mulai.after_or_equal' => 'Jam mulai harus 06:30 atau lebih.',
            'jam_mulai.before_or_equal' => 'Jam mulai maksimal 17:00.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'jam_selesai.before_or_equal' => 'Jam selesai maksimal jam 17:00.',
            'hari.required' => 'Hari wajib dipilih.',
            'tipe.required' => 'Tipe wajib dipilih.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'id_kelas.required' => 'Kelas wajib dipilih.',
            'id_guru.required' => 'Guru wajib dipilih.',
            'id_mapel.required' => 'Mapel wajib dipilih.',
        ];

        $validated = $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai' => 'required|after_or_equal:06:30|before_or_equal:17:00',
            'jam_selesai' => 'required|after:jam_mulai|before_or_equal:17:00',
            'tipe' => 'required|in:Teori,Tefa',
            'tahun_ajaran' => 'required|string|max:9',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_guru' => 'required|exists:gurus,id_guru',
            'id_mapel' => 'required|exists:mapels,id_mapel',
        ], $messages);

        try {
            $guruMapel = GuruMapel::firstOrCreate([
                'id_guru' => $validated['id_guru'],
                'id_mapel' => $validated['id_mapel'],
                'id_kelas' => $validated['id_kelas'],
                'tahun_ajaran' => $validated['tahun_ajaran'],
            ]);

            $validated['id_guru_mapel'] = $guruMapel->id_guru_mapel;

            $jadwal->update($validated);

            return redirect()->route('admin.jadwalmapel.index')
                ->with('success', 'Jadwal berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'error' => 'Jadwal bentrok (hari, jam, kelas, dan tahun ajaran harus unik).'
            ]);
        }
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
