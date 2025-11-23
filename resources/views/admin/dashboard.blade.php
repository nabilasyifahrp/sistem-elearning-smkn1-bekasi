@extends('partials.layouts-admin')
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
            word-break: break-word;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
            word-break: break-word;
        }

        .activity-box {
            background: #d9e4dd;
            padding: 18px 22px;
            border-radius: 20px;
            margin-bottom: 15px;
        }

        .activity-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }
    </style>

    <h2 class="fw-bold">Selamat datang kembali!</h2>
    <p class="text-muted">Kelola agenda belajar mengajar di SMKN 1 Kota Bekasi sekarang!</p>

    <div class="row row-equal mb-4">

        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-card shadow-sm">
                <svg class="info-icon" fill="none" stroke-width="2">
                    <circle cx="12" cy="7" r="4" />
                    <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
                </svg>
                <div>
                    <div class="info-title">Total Guru</div>
                    <div class="info-value">{{ $totalGuru }} Guru</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-card shadow-sm">
                <svg class="info-icon" fill="none" stroke-width="2">
                    <circle cx="9" cy="7" r="4" />
                    <circle cx="17" cy="11" r="4" />
                    <path d="M3 21c0-4 4-6 8-6" />
                    <path d="M13 17c4 0 8 2 8 6" />
                </svg>
                <div>
                    <div class="info-title">Total Siswa</div>
                    <div class="info-value">{{ $totalSiswa }} Siswa</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-card shadow-sm">
                <svg class="info-icon" fill="none" stroke-width="2">
                    <path d="M4 4h16v16H4z" />
                    <path d="M4 10h16" />
                </svg>
                <div>
                    <div class="info-title">Total Mapel</div>
                    <div class="info-value">{{ $totalMapel }} Mapel</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-card shadow-sm">
                <svg class="info-icon" fill="none" stroke-width="2">
                    <rect x="2" y="4" width="20" height="14" rx="1.5" />
                    <path d="M8 20h8" />
                    <path d="M7 8h10" />
                    <path d="M7 12h6" />
                </svg>
                <div>
                    <div class="info-title">Total Kelas</div>
                    <div class="info-value">{{ $totalKelas }} Kelas</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-card shadow-sm">
                <svg class="info-icon" fill="none" stroke-width="2">
                    <rect x="3" y="5" width="18" height="16" rx="2" />
                    <path d="M16 3v4M8 3v4M3 11h18" />
                </svg>
                <div>
                    <div class="info-title">Total Jadwal Mapel</div>
                    <div class="info-value">{{ $totalJadwal }} Jadwal</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-card shadow-sm">
                <svg class="info-icon" fill="none" stroke-width="2">
                    <path d="M3 11l18-6v14l-18-6v8l6 2" />
                </svg>
                <div>
                    <div class="info-title">Total Pengumuman</div>
                    <div class="info-value">{{ $totalPengumuman }} Pengumuman</div>
                </div>
            </div>
        </div>

    </div>

    <h4 class="fw-bold mb-3">Aktivitas Terbaru</h4>

    @foreach ($latestActivities as $activity)
    <div class="activity-box shadow-sm">
        <div class="activity-title">{{ $activity['title'] }}</div>
        <p class="activity-sub">{{ $activity['sub'] }}</p>
    </div>
    @endforeach

</div>
@endsection