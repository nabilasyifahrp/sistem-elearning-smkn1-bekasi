@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold" style="color:#256343;">Edit Materi</h3>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" id="alertSuccess">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4" style="padding: 28px;">
        <div class="card-body">

            @if($errors->any())
            <div class="alert alert-danger rounded-3">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('guru.materi.update', $materi->id_materi) }}"
                method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Judul Materi</label>
                        <input type="text" name="judul_materi" class="form-control rounded-3"
                            value="{{ old('judul_materi', $materi->judul_materi) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Ganti File (opsional)</label>

                        @if($materi->file_path)
                        <div class="p-2 mb-2 bg-light rounded-3 border">
                            <small class="text-muted">File saat ini:</small><br>
                            <a href="{{ asset('storage/'.$materi->file_path) }}"
                                target="_blank"
                                class="fw-semibold">
                                Klik untuk lihat
                            </a>
                        </div>
                        @endif

                        <input type="file" name="file_materi" class="form-control rounded-3"
                            accept=".pdf,.ppt,.pptx,.doc,.docx,.jpg,.jpeg,.png,.zip">
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="4">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                    </div>

                </div>

                <button type="submit"
                    class="btn btn-success px-4"
                    style="background:#256343; border:none;">
                    Perbarui Materi
                </button>

                <a href="{{ route('guru.kelas.detail', $materi->id_guru_mapel) }}"
                    class="btn btn-success px-4"
                    style="background:#256343; border:none;">
                    Kembali
                </a>
            </form>
        </div>
    </div>

</div>
@endsection