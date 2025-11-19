@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <h3 class="mb-3">Edit Tugas</h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('guru.tugas.update', $tugas->id_tugas) }}"
                method="POST" enctype="multipart/form-data">

                @csrf
                @method('POST')
                <div class="mb-3">
                    <label class="form-label">Judul Tugas</label>
                    <input type="text" name="judul_tugas" class="form-control"
                        value="{{ old('judul_tugas', $tugas->judul_tugas) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">File Tugas (Opsional)</label>

                    @if ($tugas->file_path)
                    <p>
                        File saat ini:
                        <a href="{{ asset('storage/'.$tugas->file_path) }}" target="_blank">Lihat File</a>
                    </p>
                    @endif

                    <input type="file" name="file_tugas" class="form-control"
                        accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                </div>

                <div class="mb-3">
                    <label class="form-label">Deadline</label>
                    <input type="date" name="deadline" class="form-control"
                        value="{{ old('deadline', $tugas->deadline->format('Y-m-d')) }}" required>
                </div>

                <button type="submit" class="btn btn-success">Perbarui Tugas</button>

                <a href="{{ route('guru.tugas.detail', $tugas->id_tugas) }}"
                    class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>

</div>
@endsection