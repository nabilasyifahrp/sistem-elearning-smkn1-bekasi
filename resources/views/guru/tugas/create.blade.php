@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold" style="color:#256343;">
            Tambah Tugas
        </h3>

    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" id="alertSuccess">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4" style="padding: 28px;">
        <div class="card-body">

            @if ($errors->any())
            <div class="alert alert-danger rounded-3">
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

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Judul Tugas</label>
                        <input type="text" name="judul_tugas" class="form-control rounded-3"
                            value="{{ old('judul_tugas') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Deadline</label>
                        <input type="date" name="deadline" class="form-control rounded-3"
                            value="{{ old('deadline') }}" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="4" required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold">File Tugas (opsional)</label>
                        <input type="file" name="file_tugas" class="form-control rounded-3"
                            accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                    </div>

                </div>

                <button type="submit"
                    class="btn btn-success px-4"
                    style="background:#256343; border:none;">
                    Simpan Tugas
                </button>

                <a href="{{ route('guru.kelas.detail', $guruMapel->id_guru_mapel) }}"
                    class="btn btn-success px-4"
                    style="background:#256343; border:none;">
                    Kembali
                </a>
            </form>

        </div>
    </div>

</div>
@endsection