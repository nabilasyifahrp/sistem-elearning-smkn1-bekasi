@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <h3 class="mb-3" style="color: #256343;">
        Tugas - {{ $guruMapel->mapel->nama_mapel }} / {{ $guruMapel->kelas->tingkat }} {{ $guruMapel->kelas->jurusan }} {{ $guruMapel->kelas->kelas }}
    </h3>

    <a href="{{ route('guru.kelas.tugas.create', $guruMapel->id_guru_mapel) }}"
        class="btn mb-3"
        style="background-color: #256343; color: white;">
        <i class="bi bi-plus-circle"></i> Tambah Tugas
    </a>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if($tugas->count() == 0)
            <p class="text-muted">Belum ada tugas.</p>
            @else
            <table class="table table-bordered table-hover">
                <thead style="background:#256343; color:white;">
                    <tr>
                        <th>Judul</th>
                        <th>Deadline</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($tugas as $row)
                    <tr>
                        <td>{{ $row->judul_tugas }}</td>
                        <td>{{ $row->deadline->format('d M Y') }}</td>

                        <td width="190">
                            <a href="{{ route('guru.tugas.edit', $row->id_tugas) }}"
                                class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('guru.tugas.delete', $row->id_tugas) }}"
                                method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus tugas ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
            @endif

        </div>
    </div>

</div>
@endsection