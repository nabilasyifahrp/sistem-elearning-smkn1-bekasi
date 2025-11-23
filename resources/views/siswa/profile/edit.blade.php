@extends('partials.layouts-siswa')
@section('content')
<div class="container-fluid py-4">
    <h3 class="mb-4" style="color: #256343;">Edit Profil Siswa</h3>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('siswa.profil.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $siswa->nama) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $siswa->user->email) }}" required>
                </div>
                <button type="submit" class="btn" style="background-color: #256343; color: white;">Perbarui Profil</button>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>

    <h3 class="mt-5 mb-4" style="color: #256343;">Ganti Password</h3>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('siswa.profil.change-password') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password_baru" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_baru_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn" style="background-color: #256343; color: white;">Ganti Password</button>
            </form>
        </div>
    </div>
</div>
@endsection