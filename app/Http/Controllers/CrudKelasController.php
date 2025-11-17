<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CrudKelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::all();
        return view('admin.kelas.index', compact('kelasList'));
    }


    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tingkat' => 'required',
            'jurusan' => 'required',
            'kelas' => 'required',
            'jumlah_siswa' => 'nullable|numeric|min:0',
            'tahun_ajaran' => [
                'required',
                'regex:/^\d{4}\/\d{4}$/',
                Rule::unique('kelas')->where(function ($query) use ($request) {
                    return $query->where('tingkat', $request->tingkat)
                        ->where('jurusan', $request->jurusan)
                        ->where('kelas', $request->kelas)
                        ->where('tahun_ajaran', $request->tahun_ajaran);
                })
            ],
        ], [
            'tahun_ajaran.unique' => 'Kelas dengan kombinasi ini sudah terdaftar!',
        ]);

        Kelas::create($request->all());

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }



    public function show($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('admin.kelas.show', compact('kelas'));
    }


    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('admin.kelas.edit', compact('kelas'));
    }


    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'tingkat' => 'required',
            'jurusan' => 'required',
            'kelas' => 'required',
            'jumlah_siswa' => 'nullable|integer|min:0',
            'tahun_ajaran' => [
                'required',
                Rule::unique('kelas')->where(function ($query) use ($request) {
                    return $query->where('tingkat', $request->tingkat)
                        ->where('jurusan', $request->jurusan)
                        ->where('kelas', $request->kelas)
                        ->where('tahun_ajaran', $request->tahun_ajaran);
                })->ignore($kelas->id_kelas, 'id_kelas')
            ],
        ], [
            'tahun_ajaran.unique' => 'Data kelas tersebut sudah ada!',
        ]);

        $kelas->update($request->all());

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui!');
    }


    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }
}
