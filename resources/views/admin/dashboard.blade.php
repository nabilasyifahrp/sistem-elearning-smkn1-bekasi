@extends('partials.layouts-admin')
@section('content')

<div class="dashboard-page">

    <style>
        body {
            background: #f7f9f8;
            font-family: 'Poppins', sans-serif;
        }

        .info-card {
            width: 100%;
            background: #e7f0ec;
            padding: 20px;
            border-radius: 18px;
            min-height: 130px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            stroke: #374151;
            flex-shrink: 0;
        }

        .info-title {
            font-size: 18px;
            font-weight: 600;
        }

        .info-value {
            font-size: 15px;
            font-weight: 500;
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

        @media(max-width: 1200px) {
            .info-card {
                padding: 18px;
                gap: 12px;
                min-height: 110px;
            }
        }

        @media(max-width: 992px) {
            .info-card {
                padding: 15px;
                border-radius: 15px;
            }

            .info-title {
                font-size: 17px;
            }

            .info-value {
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            .dashboard-page {
                padding-left: 18px !important;
                padding-right: 18px !important;
            }

            .info-card {
                margin-bottom: 15px;
            }

            h1, h2, h3, h4 {
                padding-left: 2px;
                padding-right: 2px;
            }
        }

        @media(max-width: 768px) {
            .row>[class*="col-"] {
                margin-bottom: 15px;
            }
        }
    </style>

    <h2 class="fw-bold">Selamat datang kembali!</h2>
    <p class="text-muted">Kelola agenda belajar mengajar di SMKN 1 Kota Bekasi sekarang!</p>
    <div class="row mb-4">

        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-card shadow-sm">
                <svg class="info-icon" fill="none" stroke-width="1.5">
                    <circle cx="12" cy="7" r="4" />
                    <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
                </svg>
                <div>
                    <div class="info-title">Total Guru</div>
                    <div class="info-value">75 Guru</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-card shadow-sm">
                <svg class="info-icon" fill="none" stroke-width="1.5">
                    <circle cx="9" cy="7" r="4" />
                    <circle cx="17" cy="11" r="4" />
                    <path d="M3 21c0-4 4-6 8-6" />
                    <path d="M13 17c4 0 8 2 8 6" />
                </svg>
                <div>
                    <div class="info-title">Total Siswa</div>
                    <div class="info-value">550 Siswa</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="info-card shadow-sm">
                <svg class="info-icon" fill="none" stroke-width="1.5">
                    <path d="M4 4h16v16H4z" />
                    <path d="M4 10h16" />
                </svg>
                <div>
                    <div class="info-title">Total Mapel</div>
                    <div class="info-value">25 Mapel</div>
                </div>
            </div>
        </div>

    </div>
    <h4 class="fw-bold mb-3">Aktivitas Terbaru</h4>

    <div class="activity-box shadow-sm">
        <div class="activity-title">Akun siswa baru dibuat</div>
        <p class="activity-sub">Amelia Putri - XII RPL A</p>
    </div>

    <div class="activity-box shadow-sm">
        <div class="activity-title">Pengumuman baru dipublikasikan</div>
        <p class="activity-sub">Judul Pengumumannya</p>
    </div>

</div>
@endsection
