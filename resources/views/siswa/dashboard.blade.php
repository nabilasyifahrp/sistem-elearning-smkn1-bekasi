@extends('partials.layouts-siswa')

@section('content')
<style>
    .card-mapel {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        transition: 0.2s;
        cursor: pointer;
        border-left: 6px solid #256343;
    }

    .card-mapel:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
    }

    .card-mapel h5 {
        color: #256343;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .info-box {
        background: #e7f0ec;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
    }

    .info-box h3 {
        font-size: 32px;
        font-weight: 700;
        color: #256343;
        margin-bottom: 5px;
    }

    .info-box p {
        color: #666;
        margin: 0;
    }

    .pengumuman-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 12px;
        border-left: 4px solid #256343;
    }
</style>

<div>
    <h2 class="fw-bold mb-4" style="color:#256343;">Selamat datang, {{ $siswa->nama }}!</h2>
    <p class="text-muted">Kelas: {{ $kelas->tingkat }} {{ $kelas->jurusan }} {{ $kelas->kelas }}</p>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="info-box">
                <h3>{{ $totalMateri }}</h3>
                <p>Total Materi</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="info-box">
                <h3>{{ $totalTugas }}</h3>
                <p>Total Tugas</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="info-box">
                <h3>{{ $tugasSelesai }}</h3>
                <p>Tugas Selesai</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="info-box">
                <h3>{{ $tugasBelumSelesai }}</h3>
                <p>Tugas Belum Selesai</p>
            </div>
        </div>
    </div>

    <h4 class="fw-bold mb-3">Daftar Mata Pelajaran</h4>

    @if($jadwalMapel->count() === 0)
        <div class="alert alert-info">Belum ada jadwal mata pelajaran untuk kelas Anda.</div>
    @else
        <div class="row">
            @foreach($jadwalMapel as $id_guru_mapel => $jadwals)
                @php
                    $jadwal = $jadwals->first();
                @endphp
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card-mapel" onclick="window.location='{{ route('siswa.detail_mapel', $id_guru_mapel) }}'">
                        <h5>{{ $jadwal->guruMapel->mapel->nama_mapel ?? '-' }}</h5>
                        <p class="mb-1"><strong>Guru:</strong> {{ $jadwal->guruMapel->guru->nama ?? '-' }}</p>
                        <p class="mb-0 text-muted small">
                            @foreach($jadwals as $j)
                                {{ $j->hari }} ({{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }})
                                @if(!$loop->last), @endif
                            @endforeach
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <h4 class="fw-bold mb-3 mt-5">Pengumuman Terbaru</h4>

    @if($pengumuman->count() === 0)
        <div class="alert alert-info">Belum ada pengumuman.</div>
    @else
        @foreach($pengumuman as $p)
            <div class="pengumuman-card">
                <h6 class="fw-bold mb-1">{{ $p->judul }}</h6>
                <p class="text-muted small mb-2">{{ \Carbon\Carbon::parse($p->tanggal_upload)->format('d M Y') }}</p>
                <p class="mb-0">{{ Str::limit(strip_tags($p->isi), 100) }}</p>
                <a href="{{ route('siswa.detail_pengumuman', $p->id_pengumuman) }}" class="text-decoration-none">Baca selengkapnya »</a>
            </div>
        @endforeach
        
        <a href="{{ route('siswa.pengumuman') }}" class="btn btn-sm mt-2" style="background:#256343; color:white;">Lihat Semua Pengumuman</a>
    @endif
</div>

@if(session('success'))
    <div id="flash-message" style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:12px 16px; border-radius:6px; margin-top:20px; position:fixed; top:90px; right:20px; z-index:9999; transition: opacity 0.5s ease;">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            const msg = document.getElementById('flash-message');
            if(msg) {
                msg.style.opacity = "0";
                setTimeout(() => msg.remove(), 500);
            }
        }, 3000);
    </script>
@endif
@endsection