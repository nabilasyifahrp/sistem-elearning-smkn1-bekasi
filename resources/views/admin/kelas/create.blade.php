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

    @media(max-width:768px) {
        .form-card {
            padding: 18px;
        }
    }
</style>

<div class="container">

    <h2 class="fw-bold mb-4" style="color:#256343;">Tambah Kelas</h2>
    <a href="{{ route('admin.kelas.index') }}" class="btn-back">
        Kembali
    </a>
    <div class="form-card mt-6">
        <form action="{{ route('admin.kelas.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Tingkat</label>
                    <select name="tingkat" class="form-control @error('tingkat') is-invalid @enderror" required>
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="X">X</option>
                        <option value="XI">XI</option>
                        <option value="XII">XII</option>
                    </select>
                    @error('tingkat')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Jurusan</label>
                    <select name="jurusan" class="form-control @error('jurusan') is-invalid @enderror" required>
                        <option value="">-- Pilih Jurusan --</option>
                        <option value="RPL">RPL</option>
                        <option value="DKV">DKV</option>
                        <option value="TKJ">TKJ</option>
                        <option value="AK">AK</option>
                        <option value="BB">BB</option>
                        <option value="TP">TP</option>
                        <option value="TKR">TKR</option>
                        <option value="TPL">TPL</option>
                    </select>
                    @error('jurusan')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-control @error('kelas') is-invalid @enderror" required>
                        <option value="">-- Pilih Kelas --</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                    @error('kelas')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Jumlah Siswa</label>
                    <input type="number" name="jumlah_siswa"
                        class="form-control @error('jumlah_siswa') is-invalid @enderror" min="20"
                        placeholder="Contoh: 36">
                    @error('jumlah_siswa')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran"
                        class="form-control @error('tahun_ajaran') is-invalid @enderror" placeholder="Contoh: 2024/2025"
                        required>
                    @error('tahun_ajaran')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <button class="btn btn-green px-4 py-2 mt-3">Simpan Kelas</button>

        </form>

    </div>

</div>
@endsection