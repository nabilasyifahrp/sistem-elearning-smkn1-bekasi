@extends('partials.layouts-siswa')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0" style="color: #256343;">
                {{ $guruMapel->mapel->nama_mapel }}
            </h1>
            <p class="text-muted">
                Guru: {{ $guruMapel->guru->nama }} | 
                Kelas: {{ $guruMapel->kelas->tingkat }} {{ $guruMapel->kelas->jurusan }} {{ $guruMapel->kelas->kelas }}
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <ul class="nav nav-tabs" id="kelasDetailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="materi-tab" data-bs-toggle="tab" 
                            data-bs-target="#materi" type="button" style="color: #256343;">
                        <i class="bi bi-file-earmark-pdf"></i> Materi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tugas-tab" data-bs-toggle="tab" 
                            data-bs-target="#tugas" type="button" style="color: #256343;">
                        <i class="bi bi-clipboard-check"></i> Tugas
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="kelasDetailContent">
        <div class="tab-pane fade show active" id="materi" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background-color: #256343; color: white;">
                    <h5 class="mb-0">Daftar Materi</h5>
                </div>
                <div class="card-body">
                    @if($materiList->isEmpty())
                        <p class="text-muted text-center">Belum ada materi yang diunggah.</p>
                    @else
                        <div class="list-group">
                            @foreach($materiList as $materi)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="bi bi-file-earmark-pdf"></i> 
                                        {{ $materi->judul_materi }}
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        Diupload {{ $materi->tanggal_upload->format('d M Y') }}
                                    </p>
                                    @if($materi->deskripsi)
                                        <p class="small mb-0 mt-2">{{ $materi->deskripsi }}</p>
                                    @endif
                                </div>
                                <div>
                                    @if($materi->file_path)
                                        <a href="{{ asset('storage/' . $materi->file_path) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download"></i> Unduh
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tugas" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background-color: #256343; color: white;">
                    <h5 class="mb-0">Daftar Tugas</h5>
                </div>
                <div class="card-body">
                    @if($tugasList->isEmpty())
                        <p class="text-muted text-center">Belum ada tugas untuk mata pelajaran ini.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead style="background-color: #f0f0f0;">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Tugas</th>
                                        <th>Tenggat Waktu</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tugasList as $tugas)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tugas->judul_tugas }}</td>
                                        <td>{{ $tugas->deadline->format('d M Y') }}</td>
                                        <td>
                                            @php
                                                $pengumpulan = \App\Models\PengumpulanTugas::where('id_tugas', $tugas->id_tugas)
                                                    ->where('nis', $siswa->nis)
                                                    ->first();
                                            @endphp
                                            
                                            @if($pengumpulan)
                                                @if($pengumpulan->nilai)
                                                    <span class="badge" style="background-color: #28a745;">
                                                        Dinilai ({{ $pengumpulan->nilai }})
                                                    </span>
                                                @else
                                                    <span class="badge" style="background-color: #ffc107;">
                                                        Sudah Dikumpulkan
                                                    </span>
                                                @endif
                                            @else
                                                @if(now() > $tugas->deadline)
                                                    <span class="badge" style="background-color: #dc3545;">
                                                        Terlambat
                                                    </span>
                                                @else
                                                    <span class="badge" style="background-color: #6c757d;">
                                                        Belum Dikumpulkan
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('siswa.tugas.detail', $tugas->id_tugas) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection