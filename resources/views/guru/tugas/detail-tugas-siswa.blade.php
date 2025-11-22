@extends('partials.layouts-guru')
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session('success') }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<style>
    .box {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
        border: 1px solid #e3e3e3;
    }

    .title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .label {
        font-weight: 600;
    }
</style>

<div class="box">
    <div class="title">Detail Pengumpulan Tugas Siswa</div>

    <p><span class="label">Nama Siswa:</span> {{ $pengumpulan->siswa->nama }}</p>
    <p>
        <span class="label">Dikumpulkan:</span>
        {{ $pengumpulan->created_at->translatedFormat('d F Y - H:i') }}
    </p>
</div>

<div class="box">
    <div class="label">Jawaban Teks</div>
    <hr>

    @if ($pengumpulan->isi_tugas)
    <p>{!! nl2br(e($pengumpulan->isi_tugas)) !!}</p>
    @else
    <p class="text-muted">Tidak ada jawaban teks.</p>
    @endif
</div>

<div class="box">
    <div class="label">File Terkumpul</div>
    <hr>

    @if ($pengumpulan->file_path)
    <a href="{{ asset('storage/' . $pengumpulan->file_path) }}" class="btn btn-primary btn-sm" target="_blank">
        Buka File
    </a>
    @else
    <p class="text-muted">Tidak ada file yang diupload.</p>
    @endif
</div>

<div class="box">
    <div class="label">Penilaian Guru</div>
    <hr>

    <form
        action="{{ route('guru.tugas.pengumpulan.nilai', [
                'id_tugas' => $tugas->id_tugas,
                'id_pengumpulan' => $pengumpulan->id_pengumpulan,
            ]) }}"
        method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nilai</label>
            <input type="number" name="nilai" class="form-control"
                value="{{ old('nilai', $pengumpulan->nilai) }}"
                required>
        </div>

        <div class="mb-3">
            <label class="form-label">Feedback (Opsional)</label>
            <textarea name="feedback" class="form-control" rows="4">{{ old('feedback', $pengumpulan->feedback) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">
            @if ($pengumpulan->nilai)
            Update Nilai
            @else
            Simpan Nilai
            @endif
        </button>
    </form>
</div>

@endsection