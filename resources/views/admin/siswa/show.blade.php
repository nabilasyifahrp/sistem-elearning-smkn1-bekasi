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

        .detail-list p {
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
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

        <h2 class="fw-bold mb-4" style="color:#256343;">Detail Siswa</h2>

        <a href="{{ route('admin.siswa.index') }}" class="btn-back">
            Kembali
        </a>

        <div class="detail-box mt-4">
            <h3 class="detail-title mb-4">Informasi Siswa</h3>

            <hr class="border-dark">

            <div class="detail-list">
                <p><span>NIS: </span> {{ $siswa->nis }}</p>
                <p><span>Nama: </span> {{ $siswa->nama }}</p>
                <p><span>Jenis Kelamin: </span>
                    {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                </p>

                <p><span>Kelas: </span>
                    {{ $siswa->kelas->tingkat ?? '-' }}
                    {{ $siswa->kelas->jurusan ?? '' }}
                    {{ $siswa->kelas->kelas ?? '' }}
                </p>
                <p><span>Tahun Ajaran:</span> {{ $siswa->kelas->tahun_ajaran ?? '-' }}</p>
                <p><span>Email: </span> {{ $siswa->user->email ?? '-' }}</p>
                <p><span>Password: </span> {{ $siswa->user->password ?? '-' }}</p>
            </div>
        </div>
    </div>
@endsection
