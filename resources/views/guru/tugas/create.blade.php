@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <h3 class="mb-3" style="color:#256343;">
        Tambah Tugas — {{ $guruMapel->mapel->nama_mapel }}
        ({{ $guruMapel->kelas->tingkat }} {{ $guruMapel->kelas->jurusan }} {{ $guruMapel->kelas->kelas }})
    </h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('guru.kelas.tugas.store', $guruMapel->id_guru_mapel) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Judul Tugas</label>
                    <input type="text" name="judul_tugas" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deadline</label>
                    <input type="date" name="deadline" class="form-control" required>
                </div>

                <button class="btn btn-success" style="background:#256343;">
                    Simpan Tugas
                </button>

                <a href="{{ route('guru.kelas.tugas.index', $guruMapel->id_guru_mapel) }}"
                    class="btn btn-secondary">
                    Kembali
                </a>
            </form>

        </div>
    </div>

</div>
@endsection