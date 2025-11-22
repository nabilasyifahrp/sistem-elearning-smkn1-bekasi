@extends('partials.layouts-siswa')

@section('content')
<div class="dashboard-page">
    <style>
        body {
            background: #f7f9f8;
            font-family: 'Poppins', sans-serif;
        }

        .row-equal>[class*="col-"] {
            display: flex;
        }

        .info-card {
            width: 100%;
            background: #e7f0ec;
            padding: 15px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            flex: 1 1 auto;
            min-height: 0;
        }

        .info-card div {
            flex: 1 1 auto;
            min-width: 0;
        }

        .info-icon {
            width: 32px;
            height: 32px;
            stroke: #256343;
            flex-shrink: 0;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .info-title {
            font-size: 16px;
            font-weight: 600;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
        }

        .mapel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .mapel-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
            cursor: pointer;
        }

        .mapel-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 18px rgba(37, 99, 67, 0.18);
        }

        .mapel-title {
            font-size: 18px;
            font-weight: 700;
            color: #256343;
            margin-bottom: 8px;
        }

        .mapel-info {
            font-size: 14px;
            color: #666;
        }

        .pengumuman-box {
            background: #d9e4dd;
            padding: 18px 22px;
            border-radius: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: 0.2s;
        }

        .pengumuman-box:hover {
            background: #cfd9d3;
        }

        .pengumuman-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }
    </style>

    <h2 class="fw-bold">Selamat datang, {{ $siswa->nama ?? 'Siswa' }}!</h2>
    <p class="text-muted">Kelas:
        {{ $siswa->kelas->tingkat ?? '' }}
        {{ $siswa->kelas->jurusan ?? '' }}
        {{ $siswa->kelas->kelas ?? '' }}
    </p>

    <div class="row row-equal mb-4">
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-card shadow-sm">
                <svg class="info-icon" fill="none" stroke-width="2">
                    <path d="M4 4h16v16H4z" />
                    <path d="M4 10h16" />
                </svg>
                <div>
                    <div class="info-title">Total Mata Pelajaran</div>
                    <div class="info-value">{{ $mapelList->count() }} Mapel</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-card shadow-sm">
                <svg class="info-icon" fill="none" stroke-width="2">
                    <path d="M4 4h16v16H4z" />
                    <path d="M9 9h6M9 13h6" />
                </svg>
                <div>
                    <div class="info-title">Tugas Aktif</div>
                    <div class="info-value">{{ $tugasAktif }} Tugas</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-card shadow-sm">
                <svg class="info-icon" fill="none" stroke-width="2">
                    <path d="M3 11l18-6v14l-18-6v8l6 2" />
                </svg>
                <div>
                    <div class="info-title">Pengumuman</div>
                    <div class="info-value">{{ $pengumuman->count() }} Baru</div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-bold mb-3">Mata Pelajaran Saya</h4>

    <div class="mapel-grid">
        @forelse($mapelList as $item)
        <div class="mapel-card" onclick="window.location='{{ route('siswa.kelas.detail', $item->id_guru_mapel) }}'">
            <div class="mapel-title">{{ $item->mapel->nama_mapel }}</div>

            <div class="mapel-info">
                Guru: {{ $item->guru->nama }} <br>
                Kelas: {{ $item->kelas->tingkat }} {{ $item->kelas->jurusan }} {{ $item->kelas->kelas }}
            </div>
        </div>
        @empty
        <p class="text-muted">Belum ada mata pelajaran.</p>
        @endforelse
    </div>

    <h4 class="fw-bold mb-3 mt-4">Pengumuman Terbaru</h4>

    @foreach ($pengumuman as $item)
    <div class="pengumuman-box shadow-sm"
        onclick="window.location='{{ route('siswa.pengumuman.show', $item->id_pengumuman) }}'">
        <div class="pengumuman-title">{{ $item->judul }}</div>
        <p class="mb-0 small text-muted">{{ $item->tanggal_upload->format('d M Y') }}</p>
    </div>
    @endforeach

</div>
@endsection