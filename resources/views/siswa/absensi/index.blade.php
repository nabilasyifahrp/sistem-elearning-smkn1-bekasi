@extends('partials.layouts-siswa')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0" style="color: #256343;">Absensi Saya</h1>
            <p class="text-muted">Lihat riwayat kehadiran dan rekap absensi Anda</p>
        </div>
    </div>

    <div class="row mb-4">
        @php
            $totalHadir = $absensiList->where('status', 'hadir')->count();
            $totalIzin = $absensiList->where('status', 'izin')->count();
            $totalSakit = $absensiList->where('status', 'sakit')->count();
            $totalAlfa = $absensiList->where('status', 'alfa')->count();
            $totalKeseluruhan = $absensiList->count();
            $persentaseKehadiran = $totalKeseluruhan > 0 ? round(($totalHadir / $totalKeseluruhan) * 100, 1) : 0;
        @endphp

        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 style="color: #28a745;">{{ $totalHadir }}</h3>
                    <p class="text-muted small mb-0">Hadir</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 style="color: #ffc107;">{{ $totalIzin }}</h3>
                    <p class="text-muted small mb-0">Izin</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 style="color: #ff9800;">{{ $totalSakit }}</h3>
                    <p class="text-muted small mb-0">Sakit</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 style="color: #dc3545;">{{ $totalAlfa }}</h3>
                    <p class="text-muted small mb-0">Alfa</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="mb-2">Persentase Kehadiran</h6>
            <div class="progress" style="height: 25px;">
                <div class="progress-bar" 
                     style="width: {{ $persentaseKehadiran }}%; background-color: 
                     {{ $persentaseKehadiran >= 80 ? '#28a745' : ($persentaseKehadiran >= 60 ? '#ffc107' : '#dc3545') }};">
                    {{ $persentaseKehadiran }}%
                </div>
            </div>
            <small class="text-muted">
                Total {{ $totalKeseluruhan }} hari dari semua mata pelajaran
            </small>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header" style="background-color: #256343; color: white;">
            <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Riwayat Absensi</h5>
        </div>
        <div class="card-body">
            @if($absensiList->isEmpty())
                <p class="text-muted text-center">Belum ada data absensi.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background-color: #f0f0f0;">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Mata Pelajaran</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensiList as $absensi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d M Y') }}</td>
                                <td>
                                    {{ $absensi->jadwal->guruMapel->mapel->nama_mapel ?? '-' }}
                                </td>
                                <td>
                                    @php
                                        $badgeColor = match($absensi->status) {
                                            'hadir' => '#28a745',
                                            'izin' => '#ffc107',
                                            'sakit' => '#ff9800',
                                            'alfa' => '#dc3545',
                                            default => '#6c757d'
                                        };
                                    @endphp
                                    <span class="badge" style="background-color: {{ $badgeColor }};">
                                        {{ ucfirst($absensi->status) }}
                                    </span>
                                </td>
                                <td>{{ $absensi->keterangan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $absensiList->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            font-size: 14px;
        }
        
        .card-body h3 {
            font-size: 1.5rem;
        }
    }
</style>
@endsection