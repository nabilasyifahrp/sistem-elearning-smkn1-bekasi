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
        background-color: rgba(37, 99, 67, 0.6);
        color: #fff;
        border: none;
        padding: 8px 18px;
        border-radius: 6px;
        cursor: not-allowed;
        transition: 0.3s;
    }

    .btn-green.active {
        background-color: #256343;
        cursor: pointer;
    }

    .btn-green.active:hover {
        background-color: #1e4d32;
    }

    .btn-back {
        background: #256343;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        transition: 0.2s;
        display: inline-block;
        margin-bottom: 20px;
    }

    .btn-back:hover {
        background: #1d4c31;
    }
</style>

<div class="container">
    <h2 class="fw-bold mb-4" style="color:#256343;">Edit Siswa</h2>

    <a href="{{ route('admin.siswa.index') }}" class="btn-back">Kembali</a>

    <div class="form-card mt-2">
        <form id="siswaForm" action="{{ route('admin.siswa.update', $siswa->nis) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}"
                        class="form-control @error('nis') is-invalid @enderror" required>
                    @error('nis')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $siswa->nama) }}"
                        class="form-control @error('nama') is-invalid @enderror" required>
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
                        <option value="L" {{ $siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran"
                        value="{{ old('tahun_ajaran', $siswa->kelas->tahun_ajaran ?? '') }}"
                        class="form-control @error('tahun_ajaran') is-invalid @enderror" required>
                    @error('tahun_ajaran')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kelas</label>
                    <select name="id_kelas" class="form-control @error('id_kelas') is-invalid @enderror" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelasList as $k)
                        <option value="{{ $k->id_kelas }}"
                            {{ $k->id_kelas == $siswa->id_kelas ? 'selected' : '' }}>
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
                    <input type="email" name="email" value="{{ old('email', $siswa->user->email ?? '') }}"
                        class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password (kosongkan jika tidak diganti)</label>
                    <div class="position-relative">
                        <input type="password" id="passwordInput" name="password"
                            class="form-control pe-5 @error('password') is-invalid @enderror"
                            placeholder="Isi hanya jika ingin mengganti">
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

            <button type="submit" id="btnUpdate" class="btn-green mt-3">Update Siswa</button>
        </form>
    </div>
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

    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("siswaForm");
        const btn = document.getElementById("btnUpdate");
        const initialValues = {};

        Array.from(form.elements).forEach(el => {
            if (!el.name) return;
            initialValues[el.name] = el.value ?? '';
        });

        function checkForChanges() {
            let changed = false;
            Array.from(form.elements).forEach(el => {
                if (!el.name) return;
                let current = el.value ?? '';
                if (current !== initialValues[el.name]) changed = true;
            });

            if (changed) {
                btn.classList.add('active');
                btn.disabled = false;
            } else {
                btn.classList.remove('active');
                btn.disabled = true;
            }
        }

        form.addEventListener('input', checkForChanges);
        form.addEventListener('change', checkForChanges);
        checkForChanges();
    });
</script>
@endsection