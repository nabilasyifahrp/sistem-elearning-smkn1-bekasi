<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;

class CrudMapelController extends Controller
{
    public function index()
    {
        $mapelList = Mapel::all();
        return view('admin.mapel.index', compact('mapelList'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:150',
            'deskripsi'  => 'nullable|string|max:200', 
        ]);

        Mapel::create([
            'nama_mapel' => $request->nama_mapel,
            'deskripsi'  => $request->deskripsi,
        ]);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mapel berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $mapel = Mapel::findOrFail($id);
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:150',
            'deskripsi'  => 'nullable|string|max:150',
        ]);

        $mapel = Mapel::findOrFail($id);

        $mapel->update([
            'nama_mapel' => $request->nama_mapel,
            'deskripsi'  => $request->deskripsi,
        ]);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mapel berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mapel berhasil dihapus!');
    }
}
