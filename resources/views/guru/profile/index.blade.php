@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0" style="color: #256343;">Profil Saya</h1>
            <p class="text-muted">Kelola informasi akun Anda</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bi bi-info-circle"></i> {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header" style="background-color: #256343; color: white;">
                    <h5 class="mb-0"><i class="bi bi-person-circle"></i> Informasi Pribadi</h5>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 100px; height: 100px; background-color: #e7f0ec;">
                            <i class="bi bi-person-fill" style="font-size: 3rem; color: #256343;"></i>
                        </div>
                    </div>

                    <table class="table table-borderless">
                        <tr>
                            <td width="40%" class="fw-bold">NIP</td>
                            <td>: {{ $guru->nip }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Nama Lengkap</td>
                            <td>: {{ $guru->nama }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Jenis Kelamin</td>
                            <td>: {{ $guru->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email</td>
                            <td>: {{ Auth::user()->email }}</td>
                        </tr>
                    </table>

                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle"></i>
                        Jika ada kesalahan data, silakan hubungi admin sekolah.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header" style="background-color: #256343; color: white;">
                    <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Keamanan Akun</h5>
                </div>

                <div class="card-body">
                    <p class="text-muted">Ubah password Anda secara berkala untuk menjaga keamanan akun.</p>

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi Kesalahan!</strong>
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
                                <input type="password" id="passwordBaru"
                                    name="password_baru"
                                    class="form-control pe-5"
                                    placeholder="Masukkan password baru">
                                <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0"
                                    onclick="togglePassword('passwordBaru','eyeBaru')">
                                    <i id="eyeBaru" class="bi bi-eye text-muted fs-5"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                            <div class="position-relative">
                                <input type="password" id="passwordConfirm"
                                    name="password_baru_confirmation"
                                    class="form-control pe-5"
                                    placeholder="Ketik ulang password baru">
                                <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0"
                                    onclick="togglePassword('passwordConfirm','eyeConfirm')">
                                    <i id="eyeConfirm" class="bi bi-eye text-muted fs-5"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn text-white w-100" style="background-color:#256343;">
                            <i class="bi bi-save"></i> Simpan Perubahan
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

        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace("bi-eye", "bi-eye-slash");
        } else {
            input.type = "password";
            icon.classList.replace("bi-eye-slash", "bi-eye");
        }
    }
</script>
@endsection