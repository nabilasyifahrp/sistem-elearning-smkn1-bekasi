<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CrudSiswaController extends Controller
{
    public function index()
    {
        $siswaList = Siswa::with('user', 'kelas')->orderBy('nis')->get();
        return view('admin.siswa.index', compact('siswaList'));
    }


    public function create()
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('jurusan')->orderBy('kelas')->get();
        return view('admin.siswa.create', compact('kelasList'));
    }



    public function store(Request $request)
    {
        $request->validate(
            [
                'nis' => [
                    'required',
                    'digits:9',
                    'numeric',
                    'unique:siswas,nis',
                ],
                'nama' => 'required',
                'jenis_kelamin' => 'required|in:L,P',
                'tahun_ajaran' => [
                    'required',
                    'regex:/^\d{4}\/\d{4}$/'
                ],
                'id_kelas' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ],
            [
                'nis.digits' => 'Masukkan 9 digit angka.',
                'nis.numeric' => 'Masukkan 9 digit angka.',
                'nis.unique' => 'NIS sudah terdaftar.',
                'email.unique' => 'Email sudah terdaftar.',
                'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
                'tahun_ajaran.regex' => 'Format tahun ajaran tidak valid. Gunakan format seperti contoh.',
                'password.required' => 'Password minimal 6 karakter.',
            ]
        );

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'siswa',
        ]);

        Siswa::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tahun_ajaran' => $request->tahun_ajaran,
            'id_kelas' => $request->id_kelas,
            'user_id' => $user->id,
        ]);
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan');
    }



    public function edit($id){
        $siswa = Siswa::with('kelas', 'user')->findOrFail($id);
        $kelasList = Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelasList'));
    }



    public function update(Request $request, $id){
        $request->validate(
            [
                'nis' => [
                    'required',
                    'digits:9',
                    'numeric',
                    'unique:siswas,nis',
                ],
                'nama' => 'required',
                'jenis_kelamin' => 'required|in:L,P',
                'tahun_ajaran' => [
                    'required',
                    'regex:/^\d{4}\/\d{4}$/'
                ],
                'id_kelas' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ],
            [
                'nis.digits' => 'Masukkan 9 digit angka.',
                'nis.numeric' => 'Masukkan 9 digit angka.',
                'nis.unique' => 'NIS sudah terdaftar.',
                'email.unique' => 'Email sudah terdaftar.',
                'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
                'tahun_ajaran.regex' => 'Format tahun ajaran tidak valid. Gunakan format seperti contoh.',
                'password.required' => 'Password minimal 6 karakter.',
            ]
        );

        $siswa = Siswa::findOrFail($id);

        $siswa->update([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_kelas' => $request->id_kelas,
        ]);

        $kelas = Kelas::find($request->id_kelas);
        if ($kelas) {
            $kelas->update([
                'tahun_ajaran' => $request->tahun_ajaran
            ]);
        }

        $user = $siswa->user;

        if ($user) {
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
        }

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }




    public function show($nis){
        $siswa = Siswa::where('nis', $nis)->firstOrFail();
        return view('admin.siswa.show', compact('siswa'));
    }



    public function destroy($nis){
        $siswa = Siswa::with('user')->findOrFail($nis);
        if ($siswa->user) {
            $siswa->user->delete();
        }
        $siswa->delete();
        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
