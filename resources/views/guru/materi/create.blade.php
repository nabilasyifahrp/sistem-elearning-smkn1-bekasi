@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <h3 class="mb-3" style="color:#256343;">
        Tambah Materi — {{ $guruMapel->mapel->nama_mapel }}
        ({{ $guruMapel->kelas->tingkat }} {{ $guruMapel->kelas->jurusan }} {{ $guruMapel->kelas->kelas }})
    </h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('guru.kelas.materi.store', $guruMapel->id_guru_mapel) }}"
                method="POST" enctype="multipart/form-data">

                @csrf

                <div class="mb-3">
                    <label class="form-label">Judul Materi</label>
                    <input type="text" name="judul_materi" class="form-control"
                        value="{{ old('judul_materi') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi (opsional)</label>
                    <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">File Materi (PDF, PPT, DOC, JPG, PNG, atau ZIP)</label>
                    <input type="file" name="file_materi" class="form-control"
                        accept=".pdf,.ppt,.pptx,.doc,.docx,.jpg,.jpeg,.png,.zip">
                </div>

                <button class="btn btn-success" style="background:#256343;">Simpan Materi</button>

                <a href="{{ route('guru.kelas.detail', $guruMapel->id_guru_mapel) }}"
                    class="btn btn-secondary">
                    Kembali
                </a>
            </form>

        </div>
    </div>

</div>
@endsection