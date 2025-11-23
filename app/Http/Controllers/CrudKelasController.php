<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CrudKelasController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => ['nullable', 'regex:/^\d{4}\/\d{4}$/']
        ], [
            'tahun_ajaran.regex' => 'Format Tahun Ajaran harus seperti 2024/2025'
        ]);

        $query = Kelas::query();

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', 'like', '%' . $request->tahun_ajaran . '%');
        }

        $kelasList = $query->get();
        $jurusanList = Kelas::select('jurusan')->distinct()->pluck('jurusan');

        return view('admin.kelas.index', compact('kelasList', 'jurusanList'));
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
            'tahun_ajaran.regex' => 'Format Tahun Ajaran harus seperti 2024/2025'
        ]);

        Kelas::create($request->all());

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
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
                'regex:/^\d{4}\/\d{4}$/',
                Rule::unique('kelas')->where(function ($query) use ($request) {
                    return $query->where('tingkat', $request->tingkat)
                        ->where('jurusan', $request->jurusan)
                        ->where('kelas', $request->kelas)
                        ->where('tahun_ajaran', $request->tahun_ajaran);
                })->ignore($kelas->id_kelas, 'id_kelas')
            ],
        ], [
            'tahun_ajaran.unique' => 'Data kelas tersebut sudah ada!',
            'tahun_ajaran.regex' => 'Format Tahun Ajaran harus seperti 2024/2025'
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

    public function createWali($id)
    {
        $kelas = Kelas::with('waliKelas')->findOrFail($id);
        $guruList = Guru::all();
        return view('admin.kelas.create-wali', compact('kelas', 'guruList'));
    }

    public function storeWali(Request $request, $id)
    {
        $request->validate([
            'id_guru' => 'required|exists:gurus,id_guru',
            'tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
        ], [
            'tahun_ajaran.regex' => 'Format Tahun Ajaran harus seperti 2024/2025'
        ]);

        $kelas = Kelas::findOrFail($id);

        if ($kelas->waliKelas) {
            $kelas->waliKelas->update([
                'id_guru' => $request->id_guru,
                'tahun_ajaran' => $request->tahun_ajaran,
            ]);
        } else {
            WaliKelas::create([
                'id_kelas' => $kelas->id_kelas,
                'id_guru' => $request->id_guru,
                'tahun_ajaran' => $request->tahun_ajaran,
            ]);
        }

        return redirect()->route('admin.kelas.index')->with('success', 'Wali kelas berhasil disimpan');
    }
}
