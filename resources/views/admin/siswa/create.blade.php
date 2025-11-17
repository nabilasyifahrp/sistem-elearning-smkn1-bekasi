@extends('partials.layouts-admin')

@section('content')
    <style>
        .form-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #256343;
        }

        .btn-green {
            background: #256343;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
        }

        .btn-green:hover {
            background: #1d4c31;
            color: #fff;
        }

        .btn-back {
            background: #256343;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-back:hover {
            background: #1d4c31;
        }
    </style>

    <div class="container">

        <h2 class="fw-bold mb-4" style="color:#256343;">Tambah Siswa</h2>

        <a href="{{ route('admin.siswa.index') }}" class="btn-back">
            Kembali
        </a>

        <div class="form-card mt-4">
            <form action="{{ route('admin.siswa.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">NIS</label>
                        <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror"
                            placeholder="Masukkan 9 digit angka" required>
                        @error('nis')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                            placeholder="Nama lengkap siswa" required>
                        @error('nama')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran"
                            class="form-control @error('tahun_ajaran') is-invalid @enderror" placeholder="Contoh: 2024/2025"
                            required>
                        @error('tahun_ajaran')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kelas</label>
                        <select name="id_kelas" class="form-control @error('id_kelas') is-invalid @enderror" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id_kelas }}">
                                    {{ $k->tingkat }} {{ $k->jurusan }} {{ $k->kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kelas')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>


                <hr class="my-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Email siswa" required>

                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password</label>

                        <div class="position-relative">
                            <input type="password" id="passwordInput" name="password"
                                class="form-control pe-5 @error('password') is-invalid @enderror"
                                placeholder="Password minimal 6 karakter" required>
                            <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0"
                                onclick="togglePassword()">
                                <i id="eyeOpen" class="bi bi-eye text-muted fs-5"></i>
                                <i id="eyeClosed" class="bi bi-eye-slash text-muted fs-5 d-none"></i>
                            </button>
                        </div>

                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <button class="btn-green mt-3">Simpan Siswa</button>
            </form>
        </div>
        <script>
            function togglePassword() {
                const input = document.getElementById("passwordInput");
                const eyeOpen = document.getElementById("eyeOpen");
                const eyeClosed = document.getElementById("eyeClosed");

                if (input.type === "password") {
                    input.type = "text";
                    eyeOpen.classList.add("d-none");
                    eyeClosed.classList.remove("d-none");
                } else {
                    input.type = "password";
                    eyeOpen.classList.remove("d-none");
                    eyeClosed.classList.add("d-none");
                }
            }
        </script>

    </div>
@endsection
