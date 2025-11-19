@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <h3 class="mb-3" style="color:#256343;">
        Tambah Tugas — {{ $guruMapel->mapel->nama_mapel }}
        ({{ $guruMapel->kelas->tingkat }} {{ $guruMapel->kelas->jurusan }} {{ $guruMapel->kelas->kelas }})
    </h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('guru.kelas.tugas.store', $guruMapel->id_guru_mapel) }}"
                  method="POST" enctype="multipart/form-data">

                @csrf

                <div class="mb-3">
                    <label class="form-label">Judul Tugas</label>
                    <input type="text" name="judul_tugas" class="form-control"
                           value="{{ old('judul_tugas') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">File Tugas (Opsional)</label>
                    <input type="file" name="file_tugas" class="form-control"
                           accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                </div>

                <div class="mb-3">
                    <label class="form-label">Deadline</label>
                    <input type="date" name="deadline" class="form-control"
                           value="{{ old('deadline') }}" required>
                </div>

                <button class="btn btn-success" style="background:#256343;">Simpan Tugas</button>
                <a href="{{ route('guru.kelas.detail', $guruMapel->id_guru_mapel) }}" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>
@endsection
