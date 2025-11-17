@extends('partials.layouts-admin')

@section('content')
    <style>
        .form-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #256343;
        }

        .btn-green {
            background: #256343;
            color: #ffffff;
            border: none;
        }

        .btn-green:hover {
            background: #1e4d32;
            color: #ffffff;
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
            background: #1e4d32;
            color: white;
        }

        .btn-green:disabled {
            background-color: #256343 !important;
            color: #ffffff !important;
            cursor: not-allowed !important;
            opacity: 0.6;
        }
    </style>

    <div class="container">

        <h2 class="fw-bold mb-4" style="color:#256343;">Edit Guru</h2>

        <a href="{{ route('admin.guru.index') }}" class="btn-back">
            Kembali
        </a>

        <div class="form-card mt-4">
            <form action="{{ route('admin.guru.update', $guru->id_guru) }}" method="POST" id="editGuruForm">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip"
                            class="form-control @error('nip') is-invalid @enderror"
                            value="{{ old('nip', $guru->nip) }}"
                            placeholder="Masukkan NIP (boleh kosong)">
                        @error('nip')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama"
                            class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $guru->nama) }}"
                            placeholder="Nama guru" required>
                        @error('nama')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin"
                            class="form-control @error('jenis_kelamin') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $guru->user->email) }}"
                            placeholder="Email guru" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password (Kosongkan jika tidak diubah)</label>

                        <div class="position-relative">
                            <input type="password" id="passwordInput" name="password"
                                class="form-control pe-5 @error('password') is-invalid @enderror"
                                placeholder="Isi jika ingin mengganti password">

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

                <button id="btnUpdate" class="btn btn-green px-4 py-2 mt-3" disabled>
                    Update Guru
                </button>

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
            const form = document.getElementById("editGuruForm");
            const btn = document.getElementById("btnUpdate");
            const initialData = new FormData(form);

            function checkForChanges() {
                const current = new FormData(form);
                let changed = false;

                for (let [key, value] of current.entries()) {
                    if (value !== initialData.get(key)) {
                        changed = true;
                        break;
                    }
                }

                btn.disabled = !changed;
            }

            form.addEventListener("input", checkForChanges);
        });
    </script>

@endsection
