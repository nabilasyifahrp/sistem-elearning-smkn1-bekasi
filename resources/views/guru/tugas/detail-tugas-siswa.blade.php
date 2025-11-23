@extends('partials.layouts-guru')
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session('success') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Terjadi kesalahan:</strong>
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<style>
    .box {
        background: white;
        border-radius: 14px;
        padding: 22px;
        margin-bottom: 25px;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #256343;
    }

    .label {
        font-weight: 600;
        color: #256343;
    }

    .btn-green {
        background: #256343 !important;
        border: none !important;
        color: white !important;
        border-radius: 10px !important;
        padding: 8px 18px !important;
    }

    .btn-green:hover {
        background: #1d4e37 !important;
    }

    .btn-outline-green {
        border: 2px solid #256343 !important;
        color: #256343 !important;
        border-radius: 10px !important;
        padding: 8px 18px !important;
    }

    .btn-outline-green:hover {
        background: #256343 !important;
        color: white !important;
    }
</style>

<div class="row mb-4">
    <div class="col-md-6">
        <h3 class="fw-bold mb-1" style="color:#256343;">Detail Pengumpulan Tugas</h3>
    </div>

    <div class="col-md-6 text-end">
        <a href="{{ route('guru.tugas.detail', $tugas->id_tugas) }}"
            class="btn btn-outline-green">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="box">
    <div class="title">
        <i class="bi bi-person-lines-fill me-2"></i> Detail Pengumpulan Tugas Siswa
    </div>

    <p><span class="label">Nama Siswa:</span> {{ $pengumpulan->siswa->nama }}</p>
    <p>
        <span class="label">Dikumpulkan:</span>
        {{ $pengumpulan->created_at->translatedFormat('d F Y - H:i') }}
    </p>
</div>

<div class="box">
    <div class="label">
        <i class="bi bi-card-text me-2"></i> Jawaban Teks
    </div>
    <hr>

    @if ($pengumpulan->isi_tugas)
    <p class="mt-2">{!! nl2br(e($pengumpulan->isi_tugas)) !!}</p>
    @else
    <p class="text-muted">Tidak ada jawaban teks.</p>
    @endif
</div>

<div class="box">
    <div class="label">
        <i class="bi bi-file-earmark-arrow-down me-2"></i> File Terkumpul
    </div>
    <hr>

    @if ($pengumpulan->file_path)
    <a href="{{ asset('storage/' . $pengumpulan->file_path) }}"
        class="btn btn-green btn-sm"
        target="_blank">
        <i class="bi bi-folder2-open"></i> Buka File
    </a>
    @else
    <p class="text-muted">Tidak ada file yang diupload.</p>
    @endif
</div>

<div class="box">
    <div class="label">
        <i class="bi bi-pencil-square me-2"></i> Penilaian Guru
    </div>
    <hr>

    <form
        action="{{ route('guru.tugas.pengumpulan.nilai', [
            'id_tugas' => $tugas->id_tugas,
            'id_pengumpulan' => $pengumpulan->id_pengumpulan,
        ]) }}"
        method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Nilai</label>
            <input type="number" name="nilai" class="form-control"
                value="{{ old('nilai', $pengumpulan->nilai) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Feedback (Opsional)</label>
            <textarea name="feedback" class="form-control" rows="4">{{ old('feedback', $pengumpulan->feedback) }}</textarea>
        </div>

        <button type="submit" class="btn btn-green px-4">
            @if ($pengumpulan->nilai)
            <i class="bi bi-check2-circle me-1"></i> Update Nilai
            @else
            <i class="bi bi-check2-circle me-1"></i> Simpan Nilai
            @endif
        </button>
    </form>
</div>

@endsection