@extends('partials.layouts-siswa')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0" style="color: #256343;">Tugas Saya</h1>
            <p class="text-muted">Kelola dan pantau semua tugas dari berbagai mata pelajaran</p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <ul class="nav nav-tabs" id="tugasTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="aktif-tab" data-bs-toggle="tab"
                        data-bs-target="#aktif" type="button" style="color: #256343;">
                        <i class="bi bi-clock"></i> Tugas Aktif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="selesai-tab" data-bs-toggle="tab"
                        data-bs-target="#selesai" type="button" style="color: #256343;">
                        <i class="bi bi-check-circle"></i> Tugas Selesai
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="terlambat-tab" data-bs-toggle="tab"
                        data-bs-target="#terlambat" type="button" style="color: #256343;">
                        <i class="bi bi-exclamation-circle"></i> Terlambat
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="tugasTabContent">
        <div class="tab-pane fade show active" id="aktif" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @php
                    $tugasAktif = $tugasList->filter(function($tugas) use ($siswa) {
                    $pengumpulan = \App\Models\PengumpulanTugas::where('id_tugas', $tugas->id_tugas)
                    ->where('nis', $siswa->nis)
                    ->first();
                    return !$pengumpulan && now() <= $tugas->deadline;
                        });
                        @endphp

                        @if($tugasAktif->isEmpty())
                        <p class="text-muted text-center">Tidak ada tugas aktif.</p>
                        @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead style="background-color: #f0f0f0;">
                                    <tr>
                                        <th>No</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Judul Tugas</th>
                                        <th>Tenggat Waktu</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tugasAktif as $tugas)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tugas->guruMapel->mapel->nama_mapel }}</td>
                                        <td>{{ $tugas->judul_tugas }}</td>
                                        <td>
                                            {{ $tugas->deadline->format('d M Y') }}
                                            <br>

                                        </td>
                                        <td>
                                            <a href="{{ route('siswa.tugas.detail', $tugas->id_tugas) }}"
                                                class="btn btn-sm" style="background-color: #256343; color: white;">
                                                <i class="bi bi-eye"></i> Lihat & Kumpulkan
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

        <div class="tab-pane fade" id="selesai" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @php
                    $tugasSelesai = $tugasList->filter(function($tugas) use ($siswa) {
                    $pengumpulan = \App\Models\PengumpulanTugas::where('id_tugas', $tugas->id_tugas)
                    ->where('nis', $siswa->nis)
                    ->first();
                    return $pengumpulan && $pengumpulan->status !== 'Terlambat';
                    });
                    @endphp

                    @if($tugasSelesai->isEmpty())
                    <p class="text-muted text-center">Belum ada tugas yang dikumpulkan.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: #f0f0f0;">
                                <tr>
                                    <th>No</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Judul Tugas</th>
                                    <th>Tanggal Kumpul</th>
                                    <th>Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tugasSelesai as $tugas)
                                @php
                                $pengumpulan = \App\Models\PengumpulanTugas::where('id_tugas', $tugas->id_tugas)
                                ->where('nis', $siswa->nis)
                                ->first();
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $tugas->guruMapel->mapel->nama_mapel }}</td>
                                    <td>{{ $tugas->judul_tugas }}</td>
                                    <td>{{ $pengumpulan->tanggal_pengumpulan->format('d M Y') }}</td>
                                    <td>
                                        @if($pengumpulan->nilai)
                                        <span class="badge" style="background-color: #28a745; font-size: 14px;">
                                            {{ $pengumpulan->nilai }}
                                        </span>
                                        @else
                                        <span class="badge" style="background-color: #ffc107;">
                                            Belum Dinilai
                                        </span>
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

        <div class="tab-pane fade" id="terlambat" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @php
                    $tugasTerlambat = $tugasList->filter(function($tugas) use ($siswa) {
                    $pengumpulan = \App\Models\PengumpulanTugas::where('id_tugas', $tugas->id_tugas)
                    ->where('nis', $siswa->nis)
                    ->first();
                    return (!$pengumpulan && now() > $tugas->deadline) ||
                    ($pengumpulan && $pengumpulan->status === 'Terlambat');
                    });
                    @endphp

                    @if($tugasTerlambat->isEmpty())
                    <p class="text-muted text-center">Tidak ada tugas yang terlambat.</p>
                    @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Anda memiliki {{ $tugasTerlambat->count() }} tugas yang terlambat atau belum dikumpulkan.
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: #f0f0f0;">
                                <tr>
                                    <th>No</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Judul Tugas</th>
                                    <th>Tenggat Waktu</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tugasTerlambat as $tugas)
                                @php
                                $pengumpulan = \App\Models\PengumpulanTugas::where('id_tugas', $tugas->id_tugas)
                                ->where('nis', $siswa->nis)
                                ->first();
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $tugas->guruMapel->mapel->nama_mapel }}</td>
                                    <td>{{ $tugas->judul_tugas }}</td>
                                    <td>{{ $tugas->deadline->format('d M Y') }}</td>
                                    <td>
                                        @if($pengumpulan)
                                        <span class="badge" style="background-color: #ff9800;">
                                            Dikumpulkan Terlambat
                                        </span>
                                        @else
                                        <span class="badge" style="background-color: #dc3545;">
                                            Belum Dikumpulkan
                                        </span>
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