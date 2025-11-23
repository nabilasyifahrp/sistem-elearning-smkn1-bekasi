@extends('partials.layouts-guru')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid py-4">

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-1" style="color:#256343;">Profil Saya</h1>
            <p class="text-muted">Kelola informasi akun Anda dengan mudah.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show shadow-sm">
        <i class="bi bi-info-circle"></i> {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row gy-4">

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 rounded-top-4"
                    style="background-color:#256343; color:white;">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle me-1"></i> Informasi Pribadi
                    </h5>
                </div>
                <div class="card-body">

                    <div class="text-center mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="
                                width:110px; 
                                height:110px; 
                                background:#e7f0ec;
                            ">
                            <i class="bi bi-person-fill" style="font-size:3.2rem; color:#256343;"></i>
                        </div>
                    </div>

                    <table class="table table-borderless mb-3">
                        <tr>
                            <td width="45%" class="fw-bold text-secondary">NIP</td>
                            <td class="text-dark">: {{ $guru->nip }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-secondary">Nama Lengkap</td>
                            <td class="text-dark">: {{ $guru->nama }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-secondary">Jenis Kelamin</td>
                            <td class="text-dark">: {{ $guru->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-secondary">Email</td>
                            <td class="text-dark">: {{ Auth::user()->email }}</td>
                        </tr>
                    </table>

                    <div class="alert alert-info small py-2 px-3 rounded-3 shadow-sm">
                        <i class="bi bi-info-circle me-1"></i>
                        Jika ada kesalahan data, silakan hubungi admin sekolah.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header py-3 rounded-top-4"
                    style="background-color:#256343; color:white;">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-lock me-1"></i> Keamanan Akun
                    </h5>
                </div>

                <div class="card-body">

                    <p class="text-muted">
                        Disarankan untuk mengganti password secara berkala.
                    </p>

                    @if($errors->any())
                    <div class="alert alert-danger rounded-3 shadow-sm">
                        <strong>Terjadi Kesalahan:</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('guru.profile.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Baru</label>
                            <div class="position-relative">
                                <input type="password"
                                    id="passwordBaru"
                                    name="password_baru"
                                    class="form-control rounded-3 shadow-sm pe-5"
                                    placeholder="Masukkan password baru">

                                <button type="button"
                                    class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0"
                                    onclick="togglePassword('passwordBaru','eyeBaru')">
                                    <i id="eyeBaru" class="bi bi-eye text-muted fs-5"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                            <div class="position-relative">
                                <input type="password"
                                    id="passwordConfirm"
                                    name="password_baru_confirmation"
                                    class="form-control rounded-3 shadow-sm pe-5"
                                    placeholder="Ketik ulang password baru">

                                <button type="button"
                                    class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0"
                                    onclick="togglePassword('passwordConfirm','eyeConfirm')">
                                    <i id="eyeConfirm" class="bi bi-eye text-muted fs-5"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="btn text-white w-100 py-2 rounded-3 shadow-sm"
                            style="background-color:#256343;">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        input.type = input.type === "password" ? "text" : "password";
        icon.classList.toggle("bi-eye");
        icon.classList.toggle("bi-eye-slash");
    }
</script>

@endsection