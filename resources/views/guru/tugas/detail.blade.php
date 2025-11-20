@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0" style="color:#256343;">Detail Tugas</h3>
            <p class="text-muted mb-0">
                {{ $tugas->guruMapel->mapel->nama_mapel }}
                {{ $tugas->guruMapel->kelas->tingkat }}
                {{ $tugas->guruMapel->kelas->jurusan }}
                {{ $tugas->guruMapel->kelas->kelas }}
            </p>
        </div>

        <a href="{{ route('guru.kelas.detail', $tugas->id_guru_mapel) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header text-white" style="background:#256343;">
            <h5 class="mb-0">Informasi Tugas</h5>
        </div>

        <div class="card-body">

            <p><strong>Judul:</strong> {{ $tugas->judul_tugas }}</p>

            <p><strong>Deadline:</strong>
                {{ $tugas->deadline->format('d M Y') }}
            </p>

            @if($tugas->file_path)
            <p><strong>File Tugas:</strong>
                <a href="{{ asset('storage/'.$tugas->file_path) }}"
                    target="_blank"
                    class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-file-earmark"></i> Lihat File
                </a>
            </p>
            @else
            <p><strong>File Tugas:</strong> - Tidak ada file -</p>
            @endif

            <hr>

            <p><strong>Deskripsi:</strong></p>
            <p class="text-muted">{!! nl2br(e($tugas->deskripsi)) !!}</p>

        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header text-white" style="background:#256343;">
            <h5 class="mb-0">Daftar Pengumpulan</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-hover">
                    <thead style="background:#f0f0f0;">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Tanggal Upload</th>
                            <th>File</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pengumpulan as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->siswa->nama_siswa }}</td>
                            <td>{{ $item->created_at->format('d M Y H:i') }}</td>

                            <td>
                                <a href="{{ asset('storage/'.$item->file_path) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark"></i> Lihat
                                </a>
                            </td>

                            <td>
                                @if($item->nilai)
                                <span class="badge bg-success">{{ $item->nilai }}</span>
                                @else
                                <span class="badge bg-warning text-dark">Belum dinilai</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('guru.tugas.pengumpulan.nilai', [$tugas->id_tugas, $item->id_pengumpulan_tugas]) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="bi bi-pen"></i> Nilai
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Belum ada siswa yang mengumpulkan tugas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</div>
@endsection