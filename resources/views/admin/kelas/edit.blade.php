@extends('partials.layouts-admin')

@section('content')
<style>
    .form-card {
        background: #ffffff;
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

    .btn-green:disabled {
        background-color: #256343 !important;
        color: #ffffff !important;
        cursor: not-allowed !important;
    }

    .btn-back {
        background: #256343;
        color: #ffffff;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-back:hover {
        background: #1e4d32;
        color: #ffffff;
    }

    @media(max-width:768px) {
        .form-card {
            padding: 18px;
        }
    }
</style>

<div class="container">
    <h2 class="fw-bold mb-4" style="color:#256343;">Edit Kelas</h2>
    <a href="{{ route('admin.kelas.index') }}" class="btn-back">
        Kembali
    </a>

    <div class="form-card mt-6">
        <form id="formEditKelas" action="{{ route('admin.kelas.update', $kelas->id_kelas) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Tingkat</label>
                    <select name="tingkat" class="form-control @error('tingkat') is-invalid @enderror" required>
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="X" {{ old('tingkat', $kelas->tingkat) == 'X' ? 'selected' : '' }}>X</option>
                        <option value="XI" {{ old('tingkat', $kelas->tingkat) == 'XI' ? 'selected' : '' }}>XI</option>
                        <option value="XII" {{ old('tingkat', $kelas->tingkat) == 'XII' ? 'selected' : '' }}>XII</option>
                    </select>
                    @error('tingkat')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Jurusan</label>
                    <select name="jurusan" class="form-control @error('jurusan') is-invalid @enderror" required>
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach (['RPL', 'DKV', 'TKJ', 'AK', 'BB', 'TP', 'TKR', 'TPL'] as $jur)
                        <option value="{{ $jur }}" {{ old('jurusan', $kelas->jurusan) == $jur ? 'selected' : '' }}>
                            {{ $jur }}
                        </option>
                        @endforeach
                    </select>
                    @error('jurusan')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-control @error('kelas') is-invalid @enderror" required>
                        <option value="">-- Pilih Kelas --</option>
                        <option value="A" {{ old('kelas', $kelas->kelas) == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('kelas', $kelas->kelas) == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ old('kelas', $kelas->kelas) == 'C' ? 'selected' : '' }}>C</option>
                    </select>
                    @error('kelas')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Jumlah Siswa</label>
                    <input type="number" name="jumlah_siswa" min="0"
                        class="form-control @error('jumlah_siswa') is-invalid @enderror"
                        value="{{ old('jumlah_siswa', $kelas->jumlah_siswa) }}" placeholder="Contoh: 36">
                    @error('jumlah_siswa')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran"
                        class="form-control @error('tahun_ajaran') is-invalid @enderror"
                        value="{{ old('tahun_ajaran', $kelas->tahun_ajaran) }}" placeholder="Contoh: 2024/2025"
                        required>
                    @error('tahun_ajaran')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <button id="btnUpdate" class="btn btn-green px-4 py-2 mt-3" disabled>Update Kelas</button>

        </form>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const form = document.getElementById("formEditKelas");
        const btn = document.getElementById("btnUpdate");

        const initial = {};
        [...form.elements].forEach(el => {
            if (el.name && el.type !== "hidden") {
                initial[el.name] = el.value;
            }
        });

        function detectChange() {
            let changed = false;

            [...form.elements].forEach(el => {
                if (el.name && el.type !== "hidden") {
                    if (el.value !== initial[el.name]) {
                        changed = true;
                    }
                }
            });

            btn.disabled = !changed;
        }

        form.addEventListener("input", detectChange);
    });
</script>

@endsection