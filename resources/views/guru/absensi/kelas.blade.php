@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0" style="color: #256343;">Absensi Kelas</h1>
            <p class="text-muted">
                {{ $guruMapel->mapel->nama_mapel }} -
                {{ $guruMapel->kelas->tingkat }} {{ $guruMapel->kelas->jurusan }} {{ $guruMapel->kelas->kelas }}
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('guru.kelas.detail', $guruMapel->id_guru_mapel) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('guru.absensi.rekap', $guruMapel->id_guru_mapel) }}" class="btn btn-info">
                <i class="bi bi-file-earmark-bar-graph"></i> Lihat Rekap
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-x-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header text-white" style="background-color: #256343;">
            <h5 class="mb-0"><i class="bi bi-calendar-plus"></i> Buka Sesi Absensi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('guru.absensi.buka', $guruMapel->id_guru_mapel) }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-6">
                    <label class="form-label fw-bold">Pilih Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ now()->toDateString() }}" required>
                    <small class="text-muted">Sesi absensi akan dibuka untuk tanggal ini</small>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn text-white" style="background-color: #256343;">
                        <i class="bi bi-unlock"></i> Buka Sesi Absensi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header text-white" style="background-color: #256343;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-check"></i>
                    Daftar Absensi - {{ now()->translatedFormat('l, d F Y') }}
                </h5>

                @if($absensiHariIni->isNotEmpty())
                <form action="{{ route('guru.absensi.tutup', [$guruMapel->id_guru_mapel, now()->toDateString()]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Tutup sesi absensi? Siswa yang belum absen akan ditandai alfa.')">
                        <i class="bi bi-lock"></i> Tutup Sesi
                    </button>
                </form>
                @endif
            </div>
        </div>

        <div class="card-body">

            @if($absensiHariIni->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-calendar-x" style="font-size: 3rem; color: #ccc;"></i>
                <p class="text-muted mt-3">
                    Belum ada sesi absensi hari ini. Silakan buka sesi terlebih dahulu.
                </p>
            </div>

            @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f0f0f0;">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Waktu Absen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($guruMapel->kelas->siswa as $siswa)
                        @php
                        $absen = $absensiHariIni[$siswa->nis] ?? null;

                        $isPending = ($absen
                        && $absen->status === 'alfa'
                        && $absen->keterangan === null);

                        $statusColor = match(true) {
                        $absen?->status === 'hadir' => 'success',
                        $absen?->status === 'izin' => 'warning',
                        $absen?->status === 'sakit' => 'info',
                        $isPending => 'secondary',
                        $absen?->status === 'alfa' => 'danger',
                        default => 'secondary'
                        };

                        $statusText = $isPending
                        ? 'PENDING'
                        : strtoupper($absen->status ?? 'Belum Absen');
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $siswa->nis }}</td>
                            <td>{{ $siswa->nama }}</td>

                            <td>
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ $statusText }}
                                </span>
                            </td>

                            <td>
                                @if($absen)
                                {{ $absen->keterangan ?? '-' }}

                                @if($absen->id_pengajuan)
                                <br>
                                <small class="text-muted">(Izin disetujui wali kelas)</small>
                                @endif
                                @else
                                -
                                @endif
                            </td>

                            <td>
                                @if($absen && $absen->status === 'hadir')
                                {{ $absen->updated_at->format('H:i') }}
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">

                <div class="col-md-2">
                    <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                        <h3 class="text-success">
                            {{ $absensiHariIni->where('status', 'hadir')->count() }}
                        </h3>
                        <small>Hadir</small>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                        <h3 class="text-warning">
                            {{ $absensiHariIni->where('status', 'izin')->count() }}
                        </h3>
                        <small>Izin</small>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                        <h3 class="text-info">
                            {{ $absensiHariIni->where('status', 'sakit')->count() }}
                        </h3>
                        <small>Sakit</small>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                        <h3 class="text-danger">
                            {{ $absensiHariIni->filter(fn($a) => $a->status === 'alfa' && $a->keterangan !== null)->count() }}
                        </h3>
                        <small>Alfa</small>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="text-center p-3 bg-secondary bg-opacity-10 rounded">
                        <h3 class="text-secondary">{{ $belumAbsen }}</h3>
                        <small>Belum Absen</small>
                    </div>
                </div>

            </div>

            @endif
        </div>
    </div>
</div>
@endsection