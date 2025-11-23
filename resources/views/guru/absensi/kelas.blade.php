@extends('partials.layouts-guru')

@section('content')
    <style>
        .green-header {
            background: #256343 !important;
            color: white !important;
            border-radius: 10px 10px 0 0 !important;
        }

        .soft-card {
            border: none !important;
            border-radius: 14px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }

        .btn-green {
            background: #256343 !important;
            border: none !important;
            color: white !important;
            border-radius: 10px !important;
            padding: 8px 18px !important;
        }

        .btn-green:hover {
            background: #1d4e37 !important;
        }

        .btn-outline-green {
            border: 2px solid #256343 !important;
            color: #256343 !important;
            border-radius: 10px !important;
        }

        .btn-outline-green:hover {
            background: #256343 !important;
            color: white !important;
        }

        .rekap-box {
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            background: white;
            font-weight: bold;
        }

        .table th,
        .table td {
            text-align: center !important;
            vertical-align: middle !important;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="h3 fw-bold" style="color: #256343;">Absensi Kelas</h1>
                <p class="text-muted">
                    {{ $guruMapel->mapel->nama_mapel }} -
                    {{ $guruMapel->kelas->tingkat }} {{ $guruMapel->kelas->jurusan }} {{ $guruMapel->kelas->kelas }}
                </p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('guru.kelas.detail', $guruMapel->id_guru_mapel) }}" class="btn btn-outline-green me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>

                <a href="{{ route('guru.absensi.rekap', $guruMapel->id_guru_mapel) }}" class="btn btn-green">
                    <i class="bi bi-file-earmark-bar-graph"></i> Lihat Rekap
                </a>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show soft-card">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show soft-card">
                <i class="bi bi-x-circle"></i> {{ session('error') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- PERINGATAN IZIN PENDING --}}
        @if ($siswaIzinPending->isNotEmpty())
            <div class="alert alert-warning soft-card mb-4">
                <h6><i class="bi bi-exclamation-triangle"></i> Perhatian!</h6>
                Ada <strong>{{ $siswaIzinPending->count() }} siswa</strong> yang mengajukan izin hari ini tetapi
                <strong>belum disetujui wali kelas</strong>.
            </div>
        @endif

        {{-- BUKA SESI --}}
        <div class="card soft-card mb-4">
            <div class="card-header green-header">
                <h5 class="mb-0"><i class="bi bi-calendar-plus"></i> Buka Sesi Absensi</h5>
            </div>
            <div class="card-body">

                <form action="{{ route('guru.absensi.buka', $guruMapel->id_guru_mapel) }}" method="POST"
                    class="row g-3 align-items-end">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Pilih Tanggal</label>
                        <input type="date" name="tanggal" class="form-control rounded-3"
                            value="{{ now()->toDateString() }}" required>
                    </div>

                    <div class="col-md-6 text-start text-md-end">
                        <button type="submit" class="btn btn-green">
                            <i class="bi bi-unlock"></i> Buka Sesi
                        </button>
                    </div>

                </form>

            </div>
        </div>

        {{-- DAFTAR ABSENSI --}}
        <div class="card soft-card">
            <div class="card-header green-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-check"></i>
                    Daftar Absensi - {{ now()->translatedFormat('l, d F Y') }}
                </h5>

                @if ($absensiHariIni->isNotEmpty())
                    <form action="{{ route('guru.absensi.tutup', [$guruMapel->id_guru_mapel, now()->toDateString()]) }}"
                        method="POST">
                        @csrf
                        <button class="btn btn-warning btn-sm" onclick="return confirm('Tutup sesi absensi?')">
                            <i class="bi bi-lock"></i> Tutup Sesi
                        </button>
                    </form>
                @endif

            </div>

            <div class="card-body">

                @if ($absensiHariIni->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">Belum ada sesi absensi hari ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead style="background: #f4f6f4;">
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Waktu Absen</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($guruMapel->kelas->siswa as $siswa)
                                    @php
                                        $absen = $absensiHariIni[$siswa->nis] ?? null;

                                        $izinDisetujui = $siswaIzinDisetujui[$siswa->nis] ?? null;
                                        $izinPending = $siswaIzinPending[$siswa->nis] ?? null;

                                        $isPending = $absen && $absen->status === 'alfa' && $absen->keterangan === null;

                                        $statusColor = match (true) {
                                            $absen?->status === 'hadir' => 'success',
                                            $absen?->status === 'izin' => 'warning',
                                            $absen?->status === 'sakit' => 'info',
                                            $isPending => 'secondary',
                                            $absen?->status === 'alfa' => 'danger',
                                            default => 'secondary',
                                        };

                                        $statusText = $isPending
                                            ? 'PENDING'
                                            : strtoupper($absen->status ?? 'Belum Absen');
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $siswa->nis }}</td>

                                        <td>
                                            {{ $siswa->nama }}

                                            @if ($izinDisetujui)
                                                <br>
                                                <small class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i>
                                                    {{ ucfirst($izinDisetujui->jenis_izin) }} Disetujui
                                                </small>
                                            @endif

                                            @if ($izinPending)
                                                <br>
                                                <small class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock"></i>
                                                    {{ ucfirst($izinPending->jenis_izin) }} Menunggu
                                                </small>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="badge bg-{{ $statusColor }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>

                                        <td>
                                            @if ($absen)
                                                {{-- Keterangan utama --}}
                                                {{ $absen->keterangan ?? '-' }}

                                                {{-- Tambahan teks: (Izin disetujui wali kelas) --}}
                                                @if ($absen->status === 'izin' && $absen->keterangan !== null)
                                                    <br>
                                                    <small class="text-muted">(Izin disetujui wali kelas)</small>
                                                @endif

                                                {{-- Tambahan teks: (Sakit disetujui wali kelas) --}}
                                                @if ($absen->status === 'sakit' && $absen->keterangan !== null)
                                                    <br>
                                                    <small class="text-muted">(Sakit disetujui wali kelas)</small>
                                                @endif
                                            @elseif ($izinPending)
                                                <span class="text-warning">
                                                    <i class="bi bi-hourglass-split"></i> Menunggu persetujuan
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>


                                        <td>
                                            @if ($absen && $absen->status === 'hadir')
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

                    {{-- Rekap --}}
                    <div class="row text-center mt-4">

                        <div class="col-md-2 mb-3">
                            <div class="rekap-box">
                                <div class="text-success mb-1">
                                    <i class="bi bi-check-circle" style="font-size: 1.7rem;"></i>
                                </div>
                                <h4 class="text-success">
                                    {{ $absensiHariIni->where('status', 'hadir')->count() }}
                                </h4>
                                <small>Hadir</small>
                            </div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <div class="rekap-box">
                                <div class="text-warning mb-1">
                                    <i class="bi bi-exclamation-circle" style="font-size: 1.7rem;"></i>
                                </div>
                                <h4 class="text-warning">
                                    {{ $absensiHariIni->where('status', 'izin')->count() }}
                                </h4>
                                <small>Izin</small>
                            </div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <div class="rekap-box">
                                <div class="text-info mb-1">
                                    <i class="bi bi-thermometer-half" style="font-size: 1.7rem;"></i>
                                </div>
                                <h4 class="text-info">
                                    {{ $absensiHariIni->where('status', 'sakit')->count() }}
                                </h4>
                                <small>Sakit</small>
                            </div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <div class="rekap-box">
                                <div class="text-danger mb-1">
                                    <i class="bi bi-x-circle" style="font-size: 1.7rem;"></i>
                                </div>
                                <h4 class="text-danger">
                                    {{ $absensiHariIni->filter(fn($a) => $a->status === 'alfa' && $a->keterangan !== null)->count() }}
                                </h4>
                                <small>Alfa</small>
                            </div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <div class="rekap-box">
                                <div class="text-secondary mb-1">
                                    <i class="bi bi-hourglass-split" style="font-size: 1.7rem;"></i>
                                </div>
                                <h4 class="text-secondary">
                                    {{ $absensiHariIni->filter(fn($a) => $a->status === 'alfa' && $a->keterangan === null)->count() }}
                                </h4>
                                <small>Pending</small>
                            </div>
                        </div>

                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection
