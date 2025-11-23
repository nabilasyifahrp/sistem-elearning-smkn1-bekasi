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
        padding: 10px 18px;
        border-radius: 6px;
        transition: 0.2s;
        font-size: 15px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
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

    .filter-scroll {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 10px;
        padding-bottom: 6px;
        scrollbar-width: thin;
    }

    .filter-scroll::-webkit-scrollbar {
        height: 6px;
    }

    .filter-scroll::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }

    .filter-scroll select,
    .filter-scroll input {
        padding: 6px 8px;
        font-size: 13px;
        height: 34px;
        white-space: nowrap;
    }

    .filter-scroll .btn-green,
    .filter-scroll .btn-red {
        padding: 6px 12px !important;
        font-size: 13px !important;
        height: 34px;
        white-space: nowrap;
        display: flex;
        align-items: center;
    }
</style>

<div>
    <h2 class="fw-bold mb-4" style="color:#256343;">Kelola Jadwal Mapel</h2>

    <a href="{{ route('admin.jadwalmapel.create') }}" class="btn-green mb-3">+ Tambah Jadwal</a>
    <form action="{{ route('admin.jadwalmapel.index') }}" method="GET" class="filter-scroll mb-3">

        <select name="hari" class="form-control" style="width:120px;">
            <option value="">-- Hari --</option>
            @foreach (['Senin','Selasa','Rabu','Kamis','Jumat'] as $h)
            <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
            @endforeach
        </select>

        <select name="id_kelas" class="form-control" style="width:160px;">
            <option value="">-- Kelas --</option>
            @foreach ($kelasList as $kelas)
            <option value="{{ $kelas->id_kelas }}" {{ request('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                {{ $kelas->tingkat }} {{ $kelas->jurusan }} {{ $kelas->kelas }}
            </option>
            @endforeach
        </select>

        <select name="id_guru" class="form-control" style="width:160px;">
            <option value="">-- Guru --</option>
            @foreach ($guruList as $guru)
            <option value="{{ $guru->id_guru }}" {{ request('id_guru') == $guru->id_guru ? 'selected' : '' }}>
                {{ $guru->nama }}
            </option>
            @endforeach
        </select>

        <select name="id_mapel" class="form-control" style="width:160px;">
            <option value="">-- Mapel --</option>
            @foreach ($mapelList as $mapel)
            <option value="{{ $mapel->id_mapel }}" {{ request('id_mapel') == $mapel->id_mapel ? 'selected' : '' }}>
                {{ $mapel->nama_mapel }}
            </option>
            @endforeach
        </select>

        <select name="tipe" class="form-control" style="width:120px;">
            <option value="">-- Tipe --</option>
            <option value="Teori" {{ request('tipe') == 'Teori' ? 'selected' : '' }}>Teori</option>
            <option value="Tefa" {{ request('tipe') == 'Tefa' ? 'selected' : '' }}>Tefa</option>
        </select>

        <input type="text" name="tahun_ajaran" placeholder="Thn Ajaran" class="form-control"
            style="width:110px;" value="{{ request('tahun_ajaran') }}">

        <button type="submit" class="btn-green">Filter</button>

        <a href="{{ route('admin.jadwalmapel.index') }}" class="btn-red">Reset</a>
    </form>

    <div style="background:white; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.08); padding:20px;">
        <table class="table">
            <thead>
                <tr>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Tipe</th>
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
                    <td data-label="Jam">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                    <td data-label="Tipe">{{ $jadwal->tipe }}</td>
                    <td>{{ $jadwal->kelas?->tingkat }} {{ $jadwal->kelas?->jurusan }} {{ $jadwal->kelas?->kelas }}</td>
                    <td data-label="Mapel">{{ $jadwal->guruMapel->mapel->nama_mapel ?? '-' }}</td>
                    <td data-label="Guru">{{ $jadwal->guruMapel->guru->nama ?? '-' }}</td>
                    <td data-label="Aksi">
                        <div class="aksi-container">
                            <a href="{{ route('admin.jadwalmapel.edit', $jadwal->id_jadwal) }}" class="btn-action btn-green"><i class="bi bi-pencil-square"></i></a>
                            <a href="{{ route('admin.jadwalmapel.show', $jadwal->id_jadwal) }}" class="btn-action btn-green"><i class="bi bi-eye"></i></a>

                            <form action="{{ route('admin.jadwalmapel.destroy', $jadwal->id_jadwal) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn-action btn-red" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">
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
    <div id="flash-message" style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:12px 16px; border-radius:6px; margin-top:20px; transition: opacity 0.5s ease;">
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