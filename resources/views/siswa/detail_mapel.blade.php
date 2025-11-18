@extends('partials.layouts-siswa')

@section('content')
<style>
    .materi-card, .tugas-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 15px;
    }

    .materi-card h5, .tugas-card h5 {
        color: #256343;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .btn-download, .btn-detail {
        background: #256343;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        transition: 0.2s;
        border: none;
    }

    .btn-download:hover, .btn-detail:hover {
        background: #1e4d32;
        color: white;
    }

    .btn-back {
        background: #6c757d;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
    }

    .badge-deadline {
        background: #dc3545;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    .badge-selesai {
        background: #28a745;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    .badge-belum {
        background: #ffc107;
        color: #333;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
</style>

<div>
    <a href="{{ route('siswa.dashboard') }}" class="btn-back mb-3">← Kembali</a>

    <div class="mb-4">
        <h2 class="fw-bold" style="color:#256343;">{{ $jadwal->guruMapel->mapel->nama_mapel ?? '-' }}</h2>
        <p class="mb-1"><strong>Guru:</strong> {{ $jadwal->guruMapel->guru->nama ?? '-' }}</p>
        <p class="mb-1"><strong>Kelas:</strong> {{ $jadwal->kelas->tingkat }} {{ $jadwal->kelas->jurusan }} {{ $jadwal->kelas->kelas }}</p>
        <p class="text-muted">{{ $jadwal->guruMapel->mapel->deskripsi ?? '' }}</p>
    </div>

    <h4 class="fw-bold mb-3">Materi Pembelajaran</h4>

    @if($materiList->count() === 0)
        <div class="alert alert-info">Belum ada materi yang diunggah.</div>
    @else
        @foreach($materiList as $materi)
            <div class="materi-card">
                <h5>{{ $materi->judul_materi }}</h5>
                <p class="text-muted small mb-2">Diunggah: {{ \Carbon\Carbon::parse($materi->tanggal_upload)->format('d M Y') }}</p>
                <p>{{ $materi->deskripsi }}</p>
                
                @if($materi->file_path)
                    <a href="{{ asset('storage/' . $materi->file_path) }}" target="_blank" class="btn-download">
                        <i class="bi bi-download"></i> Unduh Materi
                    </a>
                @endif
            </div>
        @endforeach
    @endif

    <h4 class="fw-bold mb-3 mt-5">Daftar Tugas</h4>

    @if($tugasList->count() === 0)
        <div class="alert alert-info">Belum ada tugas yang diberikan.</div>
    @else
        @foreach($tugasList as $tugas)
            <div class="tugas-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="mb-0">{{ $tugas->judul_tugas }}</h5>
                    @if($tugas->pengumpulan->count() > 0)
                        @php
                            $pengumpulan = $tugas->pengumpulan->first();
                        @endphp
                        @if($pengumpulan->nilai !== null)
                            <span class="badge-selesai">Dinilai ({{ $pengumpulan->nilai }})</span>
                        @else
                            <span class="badge-selesai">Sudah Dikumpulkan</span>
                        @endif
                    @else
                        <span class="badge-belum">Belum Dikumpulkan</span>
                    @endif
                </div>
                
                <p class="mb-1">
                    <strong>Deadline:</strong> 
                    <span class="badge-deadline">
                        {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}
                    </span>
                </p>
                <p>{{ Str::limit($tugas->deskripsi, 150) }}</p>
                
                <a href="{{ route('siswa.detail_tugas', $tugas->id_tugas) }}" class="btn-detail">
                    Lihat Detail & Kumpulkan
                </a>
            </div>
        @endforeach
    @endif
</div>
@endsection