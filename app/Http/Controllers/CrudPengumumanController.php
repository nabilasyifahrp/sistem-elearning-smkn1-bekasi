<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class CrudPengumumanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pengumumanList = $search
            ? Pengumuman::where('judul', 'like', "%{$search}%")->orderBy('tanggal_upload', 'desc')->get()
            : Pengumuman::orderBy('tanggal_upload', 'desc')->get();

        return view('admin.pengumuman.index', compact('pengumumanList'));
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'file_path' => 'nullable|file|max:2048',
            'tanggal_upload' => 'required|date'
        ], [
            'file_path.max' => 'Ukuran lampiran terlalu besar. Maksimal 2MB.',
            'file_path.file' => 'Lampiran harus berupa file yang valid.'
        ]);


        $filePath = $request->hasFile('file_path')
            ? $request->file('file_path')->store('pengumuman_files', 'public')
            : null;

        Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'file_path' => $filePath,
            'tanggal_upload' => $request->tanggal_upload,
        ]);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil ditambahkan!');
    }


    public function show($id)
    {
        $data = Pengumuman::findOrFail($id);
        return view('admin.pengumuman.show', compact('data'));
    }


    public function edit($id)
    {
        $data = Pengumuman::findOrFail($id);
        return view('admin.pengumuman.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'file_path' => 'nullable|file|max:2048',
            'tanggal_upload' => 'required|date'
        ], [
            'file_path.max' => 'Ukuran lampiran terlalu besar. Maksimal 2MB.',
            'file_path.file' => 'Lampiran harus berupa file yang valid.'
        ]);


        $filePath = $pengumuman->file_path;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('pengumuman_files', 'public');
        }

        $pengumuman->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'file_path' => $filePath,
            'tanggal_upload' => $request->tanggal_upload,
        ]);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus!');
    }
}