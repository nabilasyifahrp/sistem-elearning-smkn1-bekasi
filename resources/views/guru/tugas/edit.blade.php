@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <h3 class="mb-3" style="color:#256343;">Edit Tugas</h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('guru.tugas.update', $tugas->id_tugas) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Judul Tugas</label>
                    <input type="text" name="judul_tugas" class="form-control" value="{{ $tugas->judul_tugas }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4">{{ $tugas->deskripsi }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deadline</label>
                    <input type="date" name="deadline"
                        class="form-control"
                        value="{{ $tugas->deadline->format('Y-m-d') }}"
                        required>
                </div>

                <button class="btn btn-success" style="background:#256343;">
                    Update Tugas
                </button>

                <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
            </form>

        </div>
    </div>

</div>
@endsection