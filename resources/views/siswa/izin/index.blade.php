@extends('partials.layouts-siswa')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0" style="color: #256343;">Pengajuan Izin</h1>
            <p class="text-muted">Ajukan izin sakit atau keperluan lainnya</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('siswa.izin.create') }}" 
               class="btn text-white" 
               style="background-color: #256343;">
                <i class="bi bi-plus-circle"></i> Ajukan Izin Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        @php
            $pending = $izinList->where('status', 'pending')->count();
            $disetujui = $izinList->where('status', 'disetujui')->count();
            $ditolak = $izinList->where('status', 'ditolak')->count();
        @endphp

        <div class="col-md-4 col-sm-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 style="color: #ffc107;">{{ $pending }}</h3>
                    <p class="text-muted small mb-0">Menunggu Persetujuan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 style="color: #28a745;">{{ $disetujui }}</h3>
                    <p class="text-muted small mb-0">Disetujui</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 style="color: #dc3545;">{{ $ditolak }}</h3>
                    <p class="text-muted small mb-0">Ditolak</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header" style="background-color: #256343; color: white;">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Riwayat Pengajuan Izin</h5>
        </div>
        <div class="card-body">
            @if($izinList->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Belum ada pengajuan izin.</p>
                    <a href="{{ route('siswa.izin.create') }}" 
                       class="btn btn-sm text-white" 
                       style="background-color: #256343;">
                        Ajukan Izin Sekarang
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background-color: #f0f0f0;">
                            <tr>
                                <th>No</th>
                                <th>Jenis Izin</th>
                                <th>Tanggal</th>
                                <th>Alasan</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Diajukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($izinList as $izin)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge" style="background-color: 
                                        {{ $izin->jenis_izin === 'sakit' ? '#ff9800' : '#17a2b8' }};">
                                        {{ ucfirst($izin->jenis_izin) }}
                                    </span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M Y') }}
                                    @if($izin->tanggal_mulai != $izin->tanggal_selesai)
                                        - {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d M Y') }}
                                    @endif
                                </td>
                                <td>
                                    <small>{{ Str::limit($izin->alasan, 50) }}</small>
                                </td>
                                <td>
                                    @if($izin->bukti_file)
                                        <a href="{{ asset('storage/' . $izin->bukti_file) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColor = match($izin->status) {
                                            'pending' => '#ffc107',
                                            'disetujui' => '#28a745',
                                            'ditolak' => '#dc3545',
                                            default => '#6c757d'
                                        };
                                    @endphp
                                    <span class="badge" style="background-color: {{ $statusColor }};">
                                        {{ ucfirst($izin->status) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $izin->created_at->diffForHumans() }}
                                    </small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="alert alert-info mt-4">
        <h6><i class="bi bi-info-circle"></i> Informasi Penting:</h6>
        <ul class="mb-0">
            <li>Pengajuan izin harus diajukan <strong>sebelum atau pada hari yang bersangkutan</strong></li>
            <li>Untuk izin sakit lebih dari 3 hari, wajib melampirkan surat keterangan dokter</li>
            <li>Pengajuan izin akan diproses oleh wali kelas</li>
            <li>Status pengajuan dapat dilihat pada halaman ini</li>
        </ul>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .table-responsive table {
            font-size: 13px;
        }
        
        .card-body h3 {
            font-size: 1.5rem;
        }
    }
</style>
@endsection