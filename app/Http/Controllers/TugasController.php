<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with(['guru', 'kelas', 'mapel'])
                      ->latest()
                      ->paginate(10);

        return view('tugas.index', compact('tugas'));
    }

    public function create()
    {
        $guru = Guru::all();
        $kelas = Kelas::all();
        $mapel = Mapel::all();

        return view('tugas.create', compact('guru', 'kelas', 'mapel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_guru' => 'required',
            'id_kelas' => 'required',
            'id_mapel' => 'required',
            'judul_tugas' => 'required|max:150',
            'deskripsi' => 'nullable',
            'deadline' => 'required|date',
        ]);

        Tugas::create($request->all());

        return redirect()->route('tugas.index')
                         ->with('success', 'Tugas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $tugas = Tugas::findOrFail($id);
        $guru = Guru::all();
        $kelas = Kelas::all();
        $mapel = Mapel::all();

        return view('tugas.edit', compact('tugas', 'guru', 'kelas', 'mapel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_tugas' => 'required|max:150',
            'deadline' => 'required|date',
        ]);

        $tugas = Tugas::findOrFail($id);
        $tugas->update($request->all());

        return redirect()->route('tugas.index')
                         ->with('success', 'Tugas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();

        return redirect()->route('tugas.index')
                         ->with('success', 'Tugas berhasil dihapus');
    }
}
