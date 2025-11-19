@extends('partials.layouts-siswa')

@section('content')
<style>
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 30px;
        max-width: 600px;
        margin: 0 auto;
    }

    .form-card h4 {
        color: #256343;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #256343;
        margin-bottom: 8px;
    }

    .btn-submit {
        background: #256343;
        color: white;
        padding: 12px 30px;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        transition: 0.2s;
        width: 100%;
    }

    .btn-submit:hover {
        background: #1e4d32;
    }

    .btn-back {
        background: #6c757d;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
    }

    .info-box {
        background: #e7f0ec;
        border-left: 4px solid #256343;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .info-box p {
        margin: 0;
        color: #256343;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #256343;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 67, 0.25);
    }
</style>

<div>
    <a href="{{ route('siswa.dashboard') }}" class="btn-back mb-3">← Kembali</a>

    <div class="form-card">
        <h4>Ubah Password</h4>

        <div class="info-box">
            <p><i class="bi bi-info-circle"></i> Pastikan password baru Anda aman dan mudah diingat. Minimal 6 karakter.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan!</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('siswa.ubah_password_update') }}" method="POST" id="formPassword">
            @csrf

            <div class="mb-3">
                <label class="form-label">Password Lama <span class="text-danger">*</span></label>
                <div class="position-relative">
                    <input type="password" 
                           name="password_lama" 
                           id="passwordLama"
                           class="form-control @error('password_lama') is-invalid @enderror" 
                           required>
                    <button type="button" 
                            class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0"
                            onclick="togglePassword('passwordLama', 'eyeOld')">
                        <i id="eyeOld" class="bi bi-eye text-muted fs-5"></i>
                    </button>
                </div>
                @error('password_lama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                <div class="position-relative">
                    <input type="password" 
                           name="password_baru" 
                           id="passwordBaru"
                           class="form-control @error('password_baru') is-invalid @enderror" 
                           minlength="6"
                           required>
                    <button type="button" 
                            class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0"
                            onclick="togglePassword('passwordBaru', 'eyeNew')">
                        <i id="eyeNew" class="bi bi-eye text-muted fs-5"></i>
                    </button>
                </div>
                @error('password_baru')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                <div class="position-relative">
                    <input type="password" 
                           name="password_baru_confirmation" 
                           id="passwordKonfirmasi"
                           class="form-control" 
                           minlength="6"
                           required>
                    <button type="button" 
                            class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0"
                            onclick="togglePassword('passwordKonfirmasi', 'eyeConfirm')">
                        <i id="eyeConfirm" class="bi bi-eye text-muted fs-5"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-key"></i> Ubah Password
            </button>
        </form>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }
}

document.getElementById('formPassword').addEventListener('submit', function(e) {
    const passwordBaru = document.getElementById('passwordBaru').value;
    const passwordKonfirmasi = document.getElementById('passwordKonfirmasi').value;
    
    if (passwordBaru !== passwordKonfirmasi) {
        e.preventDefault();
        alert('Password baru dan konfirmasi password tidak cocok!');
    }
});
</script>

@if(session('success'))
    <div id="flash-message" style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:12px 16px; border-radius:6px; position:fixed; top:90px; right:20px; z-index:9999; transition: opacity 0.5s ease;">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            const msg = document.getElementById('flash-message');
            if(msg) {
                msg.style.opacity = "0";
                setTimeout(() => msg.remove(), 500);
            }
        }, 3000);
    </script>
@endif
@endsection