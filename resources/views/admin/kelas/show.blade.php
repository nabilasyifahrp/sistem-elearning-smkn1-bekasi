@extends('partials.layouts-admin')

@section('content')

    <style>
        .detail-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .detail-title {
            font-weight: 700;
            font-size: 22px;
            color: #256343;
            margin-bottom: 15px;
        }

        .detail-list span {
            font-weight: 600;
            color: #256343;
        }

        .btn-back {
            background: #256343;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-back:hover {
            background: #1e4d32;
            color: white;
        }


        table.table {
            width: 100%;
            border-collapse: collapse;
        }

        table.table thead {
            background: #256343;
            color: white;
        }

        table.table th,
        table.table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e7e7e7;
        }

        table.table tr:hover td {
            background: #e5ffef !important;
        }

        @media (max-width: 768px) {
            table.table thead {
                display: none;
            }

            table.table,
            table.table tbody,
            table.table tr,
            table.table td {
                display: block;
                width: 100%;
            }

            table.table tr {
                background: white;
                margin-bottom: 15px;
                padding: 10px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            }

            table.table td {
                padding: 8px 10px !important;
                border: none !important;
            }

            table.table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #256343;
                display: block;
                margin-bottom: 4px;
            }
        }
    </style>


    <div>

        <h2 class="fw-bold mb-4" style="color:#256343;">Detail Kelas</h2>

        <a href="{{ route('admin.kelas.index') }}" class="btn-back">
            Kembali
        </a>
        <div class="detail-box mt-6">
            <h3 class="detail-title">Informasi Kelas</h3>

            <hr class="border-dark">

            <div class="detail-list mt-4">
                <p><span>Tingkat:</span> {{ $kelas->tingkat }}</p>
                <p><span>Jurusan:</span> {{ $kelas->jurusan }}</p>
                <p><span>Kelas:</span> {{ $kelas->kelas }}</p>
                <p><span>Tahun Ajaran:</span> {{ $kelas->tahun_ajaran }}</p>
                <p><span>Jumlah Siswa:</span> {{ $kelas->siswa->count() }}</p>
                <p><span>Wali Kelas:</span>
                    {{ $kelas->waliKelas ? $kelas->waliKelas->guru->nama : '-' }}
                </p>
            </div>
        </div>

        <div class="detail-box">
            <h3 class="detail-title mb-4">Daftar Siswa</h3>

            <hr class="border-dark">

            @if ($kelas->siswa->count() === 0)
                <p style="color:#777;">Belum ada siswa di kelas ini.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Email</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($kelas->siswa as $index => $siswa)
                            <tr onclick="window.location='{{ route('admin.siswa.index', $kelas->id_kelas) }}'"
                                style="cursor: pointer;">
                                <td data-label="No">{{ $index + 1 }}</td>
                                <td data-label="NIS">{{ $siswa->nis }}</td>
                                <td data-label="Nama">{{ $siswa->nama }}</td>
                                <td data-label="Email">{{ $siswa->user->email ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>

    </div>

@endsection
