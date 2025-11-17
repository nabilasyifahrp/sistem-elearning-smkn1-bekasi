@extends('partials.layouts-admin')

@section('content')
    <style>
        .btn-green {
            background: #256343;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            border: none;
            transition: 0.2s;
            font-size: 15px;
        }

        .btn-green:hover {
            background: #1e4d32;
            color: white;
        }

        .btn-red {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px 8px;
            border-radius: 4px;
            transition: 0.2s;
        }

        .btn-red:hover {
            background: #b52a36;
        }

        .btn-action {
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 16px;
            display: inline-block;
            border: none;
            transition: 0.2s;
        }

        .aksi-container {
            display: flex;
            flex-direction: row;
            gap: 8px;
            align-items: center;
            justify-content: center;
        }

        table.table {
            width: 100%;
            border-collapse: collapse;
        }

        table.table thead {
            background: #256343;
            color: #ffffff;
        }

        table.table th,
        table.table td {
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }

        table.table tr {
            border-bottom: 1px solid #e7e7e7;
        }

        @media (max-width: 1000px) {

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
                margin-bottom: 15px;
                background: white;
                border-radius: 8px;
                padding: 10px;
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
                border-bottom: none;
            }

            table.table td {
                text-align: left !important;
                padding: 8px 10px !important;
                position: relative;
            }

            table.table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #256343;
                display: block;
                margin-bottom: 4px;
                font-size: 13px;
            }

            td[data-label="Aksi"] .aksi-container {
                flex-direction: column !important;
                justify-content: flex-start;
                gap: 8px !important;
            }
        }

        @media (min-width: 768px) and (max-width: 992px) {
            table.table th,
            table.table td {
                padding: 10px !important;
                font-size: 14px;
            }

            .btn-green,
            .btn-action {
                font-size: 14px;
                padding: 7px 12px;
            }
        }

        @media (min-width: 992px) and (max-width: 1200px) {
            table.table th,
            table.table td {
                padding: 10px !important;
            }

            td[data-label="Aksi"] {
                width: 150px;
            }
        }
    </style>

    <div>

        <h2 class="fw-bold mb-4" style="color:#256343;">Kelola Jadwal Mapel</h2>

        <a href="{{ route('admin.jadwalmapel.create') }}" class="btn-green mb-3 d-inline-block">
            + Tambah Jadwal
        </a>

        <div style="background:white; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.08); padding:20px;">

            <table class="table">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Tipe</th>
                        <th>Tahun Ajaran</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Guru</th>
                        <th style="width:200px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($jadwalList as $jadwal)
                        <tr>
                            <td data-label="Hari">{{ $jadwal->hari }}</td>

                            <td data-label="Jam">
                                {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                            </td>

                            <td data-label="Tipe">{{ $jadwal->tipe }}</td>

                            <td data-label="Tahun Ajaran">{{ $jadwal->tahun_ajaran }}</td>

                            <td data-label="Kelas">{{ $jadwal->kelas->nama_kelas }}</td>

                            <td data-label="Mapel">{{ $jadwal->guruMapel->mapel->nama_mapel }}</td>

                            <td data-label="Guru">{{ $jadwal->guruMapel->guru->nama_guru }}</td>

                            <td data-label="Aksi">
                                <div class="aksi-container">

                                    <a href="{{ route('admin.jadwalmapel.edit', $jadwal->id_jadwal) }}"
                                        class="btn-action btn-green">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.jadwalmapel.destroy', $jadwal->id_jadwal) }}"
                                        method="POST" class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn-action btn-red"
                                            onclick="return confirm('Yakin ingin menghapus jadwal ini?')">

                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        @if (session('success'))
            <div id="flash-message"
                style="background:#d4edda; border:1px solid #c3e6cb; color:#155724;
                   padding:12px 16px; border-radius:6px; margin-top:20px;
                   transition: opacity 0.5s ease;">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(() => {
                    const msg = document.getElementById('flash-message');
                    if (msg) {
                        msg.style.opacity = "0";
                        setTimeout(() => msg.remove(), 500);
                    }
                }, 3000);
            </script>
        @endif

    </div>
@endsection
