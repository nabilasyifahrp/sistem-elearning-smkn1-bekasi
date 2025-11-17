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

        @media (max-width: 1000px) {
            td[data-label="Aksi"] .aksi-container {
                flex-direction: column !important;
                justify-content: flex-start;
                align-items: flex-start;
            }
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
                flex-direction: row !important;
                justify-content: flex-start;
                gap: 8px !important;
            }
        }
    </style>

    <div>

        <h2 class="fw-bold mb-4" style="color:#256343;">Kelola Guru</h2>

        <a href="{{ route('admin.guru.create') }}" class="btn-green mb-3">+ Tambah Guru</a>

        <div style="background:white; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.08); padding:20px;">

            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th style="width:200px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($guruList as $guru)
                        <tr>
                            <td data-label="Nama">{{ $guru->nama }}</td>
                            <td data-label="NIP">{{ $guru->nip }}</td>
                            <td data-label="Email">{{ $guru->user->email ?? '-' }}</td>

                            <td data-label="Aksi">
                                <div class="aksi-container">
                                     <a href="{{ route('admin.guru.edit', $guru->id_guru) }}" class="btn-action btn-green">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="{{ route('admin.guru.show', $guru->id_guru) }}" class="btn-action btn-green">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.guru.destroy', $guru->id_guru) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-action btn-red"
                                            onclick="return confirm('Yakin ingin menghapus siswa ini?')">
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
