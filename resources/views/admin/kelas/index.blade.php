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
        justify-content: center;
        align-items: center;
    }

    @media (max-width: 900px) and (min-width: 600px) {
        .aksi-container {
            flex-direction: column !important;
            gap: 10px !important;
            align-items: flex-start !important;
        }
    }

    @media (max-width: 600px) {
        .aksi-container {
            flex-direction: row !important;
            gap: 8px !important;
            justify-content: flex-start !important;
            flex-wrap: wrap;
        }
    }

    .filter-group {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap !important;
        gap: 10px;
        align-items: center;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
        padding-bottom: 8px;
        scrollbar-width: thin;
    }

    .filter-group::-webkit-scrollbar {
        height: 6px;
    }

    .filter-group::-webkit-scrollbar-thumb {
        background: #256343;
        border-radius: 20px;
    }

    .filter-group select,
    .filter-group input {
        padding: 6px 8px;
        font-size: 13px;
        height: 34px;
        white-space: nowrap;
    }

    .filter-group .btn-green,
    .filter-group .btn-red {
        padding: 6px 12px !important;
        font-size: 13px !important;
        height: 34px;
        display: flex;
        align-items: center;
        white-space: nowrap;
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
        cursor: pointer;
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
            justify-content: flex-start !important;
            gap: 8px !important;
        }
    }
</style>

<div>
    <h2 class="fw-bold mb-4" style="color:#256343;">Kelola Kelas</h2>

    <a href="{{ route('admin.kelas.create') }}" class="btn-green mb-3 d-inline-block">+ Tambah Kelas</a>

    <form action="{{ route('admin.kelas.index') }}" method="GET" class="filter-group">

        <select name="tingkat" class="form-control" style="width:120px;">
            <option value="">-- Tingkat --</option>
            @foreach (['X', 'XI', 'XII'] as $t)
            <option value="{{ $t }}" {{ request('tingkat') == $t ? 'selected' : '' }}>{{ $t }}
            </option>
            @endforeach
        </select>

        <select name="jurusan" class="form-control" style="width:160px;">
            <option value="">-- Jurusan --</option>
            @foreach ($jurusanList as $j)
            <option value="{{ $j }}" {{ request('jurusan') == $j ? 'selected' : '' }}>
                {{ $j }}
            </option>
            @endforeach
        </select>

        <select name="kelas" class="form-control" style="width:120px;">
            <option value="">-- Kelas --</option>
            @foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $k)
            <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>
                {{ $k }}
            </option>
            @endforeach
        </select>

        <input type="text" name="tahun_ajaran" placeholder="Thn Ajaran" class="form-control" style="width:130px;"
            value="{{ request('tahun_ajaran') }}">

        <button type="submit" class="btn-green">Filter</button>
        <a href="{{ route('admin.kelas.index') }}" class="btn-red">Reset</a>
    </form>
    @error('tahun_ajaran')
    <div style="color:red; font-size:13px; margin-bottom:10px;">
        {{ $message }}
    </div>
    @enderror

    <div style="background:white; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.08); padding:20px;">

        <table class="table">
            <thead>
                <tr>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th>Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th>Tahun Ajaran</th>
                    <th style="width:200px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($kelasList as $kelas)
                <tr onclick="window.location='{{ route('admin.kelas.show', $kelas->id_kelas) }}'">

                    <td data-label="Tingkat">{{ $kelas->tingkat }}</td>
                    <td data-label="Jurusan">{{ $kelas->jurusan }}</td>
                    <td data-label="Kelas">{{ $kelas->kelas }}</td>
                    <td data-label="Jumlah Siswa">{{ $kelas->jumlah_siswa ?? '-' }}</td>
                    <td data-label="Tahun Ajaran">{{ $kelas->tahun_ajaran }}</td>

                    <td data-label="Aksi">
                        <div class="aksi-container">

                            <a href="{{ route('admin.kelas.edit', $kelas->id_kelas) }}"
                                class="btn-action btn-green">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <a href="{{ route('admin.kelas.createWali', $kelas->id_kelas) }}"
                                class="btn-action btn-green">
                                <i class="bi bi-person-badge"></i>
                            </a>

                            <form action="{{ route('admin.kelas.destroy', $kelas->id_kelas) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn-action btn-red"
                                    onclick="return confirm('Yakin ingin menghapus kelas ini?')">
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
                       padding:12px 16px; border-radius:6px; margin-top:20px; transition: opacity 0.5s ease;">
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