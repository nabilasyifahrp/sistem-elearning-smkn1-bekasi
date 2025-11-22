@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <h4 class="fw-bold mb-3" style="color:#256343;">
        Detail Siswa
    </h4>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header" style="background:#256343; color:white;">
            <h6 class="mb-0">Tugas Siswa</h6>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover text-center align-middle mb-0">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th>No</th>
                        <th>Judul Tugas</th>
                        <th>Status</th>
                        <th>File</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($tugasList as $tugas)
                    @php
                    $kumpul = $pengumpulan[$tugas->id_tugas] ?? null;
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tugas->judul_tugas }}</td>

                        <td>
                            @if($kumpul)
                            <span class="badge bg-success">Sudah Dikumpulkan</span>
                            @else
                            <span class="badge bg-danger">Belum</span>
                            @endif
                        </td>

                        <td>
                            @if($kumpul && $kumpul->file_path)
                            <a href="{{ asset('storage/'.$kumpul->file_path) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-download"></i>
                            </a>
                            @else
                            -
                            @endif
                        </td>

                        <td>
                            @if($kumpul && $kumpul->nilai)
                            <strong>{{ $kumpul->nilai }}</strong>
                            @else
                            -
                            @endif
                        </td>

                        <td>
                            @if($kumpul)
                            <a href="{{ route('guru.tugas.detail', $tugas->id_tugas) }}"
                                class="btn btn-sm btn-outline-primary">
                                Lihat Pengumpulan
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('guru.kelas.detail', $guruMapel->id_guru_mapel) }}"
            class="btn btn-success px-4"
            style="background:#256343; border:none;">
            Kembali
        </a>
    </div>

</div>
@endsection