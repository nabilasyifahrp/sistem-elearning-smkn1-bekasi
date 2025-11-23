<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CrudGuruController extends Controller
{
    public function index()
    {
        $guruList = Guru::with('user')->orderBy('nama')->get();
        return view('admin.guru.index', compact('guruList'));
    }


    public function create()
    {
        return view('admin.guru.create');
    }


    public function store(Request $request)
    {
        $request->validate(
            [
                'nama' => 'required',
                'nip' => [
                    'nullable',
                    'numeric',
                    'digits:18',
                    'unique:gurus,nip',
                ],
                'jenis_kelamin' => 'required|in:L,P',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ],
            [
                'nip.numeric' => 'NIP hanya boleh berisi angka.',
                'nip.digits' => 'NIP harus berjumlah 18 digit.',
                'nip.unique' => 'NIP sudah terdaftar.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.required' => 'Password minimal 6 karakter.',
            ]
        );

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'guru',
        ]);

        Guru::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'jenis_kelamin' => $request->jenis_kelamin,
            'user_id' => $user->id,
        ]);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Guru berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $guru = Guru::with('user')->findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }


    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate(
            [
                'nama' => 'required',
                'nip' => [
                    'nullable',
                    'numeric',
                    'digits:18',
                    'unique:gurus,nip,' . $id . ',id_guru',
                ],
                'jenis_kelamin' => 'required|in:L,P',
                'email' => 'required|email|unique:users,email,' . $guru->user_id . ',id',
                'password' => 'nullable|min:6',
            ],
            [
                'nip.numeric' => 'NIP hanya boleh berisi angka.',
                'nip.digits' => 'NIP harus berjumlah 18 digit.',
                'nip.unique' => 'NIP sudah terdaftar.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.min' => 'Password minimal 6 karakter.',
            ]
        );

        $guru->update([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        $user = $guru->user;
        if ($user) {
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
        }

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }


    public function show($id)
    {
        $guru = Guru::with([
            'user',
            'waliKelas.kelas',
            'guruMapel.kelas',
            'guruMapel.mapel'
        ])->where('id_guru', $id)->firstOrFail();

        return view('admin.guru.show', compact('guru'));
    }


    public function destroy($id)
    {
        $guru = Guru::with('user')->findOrFail($id);

        if ($guru->user) {
            $guru->user->delete();
        }

        $guru->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
