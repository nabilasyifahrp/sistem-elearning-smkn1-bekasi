@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <h3 class="fw-bold mb-4" style="color:#256343;">Edit Tugas</h3>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" id="alertSuccess">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <form action="{{ route('guru.tugas.update', $tugas->id_tugas) }}"
                method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Tugas</label>
                    <input type="text" name="judul_tugas" class="form-control rounded-3"
                        value="{{ old('judul_tugas', $tugas->judul_tugas) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control rounded-3" rows="4" required>{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">File Tugas (Upload untuk mengganti)</label>

                    @if ($tugas->file_path)
                    <p class="mb-1">
                        <strong>File saat ini:</strong>
                        <a href="{{ asset('storage/'.$tugas->file_path) }}" target="_blank" class="text-success fw-semibold">
                            Klik untuk lihat
                        </a>
                    </p>
                    @endif

                    <input type="file" name="file_tugas" class="form-control rounded-3"
                        accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deadline</label>
                    <input type="date" name="deadline" class="form-control rounded-3"
                        value="{{ old('deadline', $tugas->deadline->format('Y-m-d')) }}" required>
                </div>

                <div class="mt-4 d-flex gap-2">

                    <button type="submit"
                        class="btn btn-success px-4"
                        style="background:#256343; border:none;">
                        Perbarui Tugas
                    </button>

                    <a href="{{ route('guru.kelas.detail', $tugas->id_guru_mapel) }}"
                        class="btn btn-success px-4"
                        style="background:#256343; border:none;">
                        Kembali
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection