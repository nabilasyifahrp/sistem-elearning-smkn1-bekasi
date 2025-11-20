@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <h3 class="mb-3" style="color:#256343;">Edit Materi</h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if($errors->any())
            <div class="alert alert-danger">
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

                <div class="mb-3">
                    <label class="form-label">Judul Materi</label>
                    <input type="text" name="judul_materi" class="form-control"
                        value="{{ old('judul_materi', $materi->judul_materi) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">File Materi (Upload untuk mengganti)</label>

                    @if($materi->file_path)
                    <p>File saat ini:
                        <a href="{{ asset('storage/'.$materi->file_path) }}" target="_blank">
                            Klik untuk lihat
                        </a>
                    </p>
                    @endif

                    <input type="file" name="file_materi" class="form-control"
                        accept=".pdf,.ppt,.pptx,.doc,.docx,.jpg,.jpeg,.png,.zip">
                </div>

                <button type="submit" class="btn btn-success" style="background:#256343;">
                    Perbarui Materi
                </button>

                <a href="{{ route('guru.kelas.detail', $materi->id_guru_mapel) }}"
                    class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>

</div>
@endsection