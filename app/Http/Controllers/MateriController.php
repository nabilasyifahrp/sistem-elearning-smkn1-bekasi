<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index()
    {
        $materi = Materi::with(['guru', 'kelas', 'mapel'])
            ->latest()
            ->paginate(10);

        return view('materi.index', compact('materi'));
    }

    public function create()
    {
        $guru = Guru::all();
        $kelas = Kelas::all();
        $mapel = Mapel::all();

        return view('materi.create', compact('guru', 'kelas', 'mapel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_guru' => 'required',
            'id_kelas' => 'required',
            'id_mapel' => 'required',
            'judul_materi' => 'required|max:150',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx',
        ]);

        $filePath = null;

        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('materi');
        }

        Materi::create([
            'id_guru' => $request->id_guru,
            'id_kelas' => $request->id_kelas,
            'id_mapel' => $request->id_mapel,
            'judul_materi' => $request->judul_materi,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
            'tanggal_upload' => now(),
        ]);

        return redirect()->route('materi.index')->with('success', 'Materi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $materi = Materi::findOrFail($id);
        $guru = Guru::all();
        $kelas = Kelas::all();
        $mapel = Mapel::all();

        return view('materi.edit', compact('materi', 'guru', 'kelas', 'mapel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_materi' => 'required|max:150',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx',
        ]);

        $materi = Materi::findOrFail($id);

        $filePath = $materi->file_path;

        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('materi');
        }

        $materi->update([
            'id_guru' => $request->id_guru,
            'id_kelas' => $request->id_kelas,
            'id_mapel' => $request->id_mapel,
            'judul_materi' => $request->judul_materi,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
        ]);

        return redirect()->route('materi.index')->with('success', 'Materi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $materi = Materi::findOrFail($id);
        $materi->delete();

        return redirect()->route('materi.index')->with('success', 'Materi berhasil dihapus');
    }
}
