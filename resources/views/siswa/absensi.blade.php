@extends('partials.layouts-siswa')

@section('content')
<style>
    .rekap-box {
        background: #e7f0ec;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        margin-bottom: 15px;
    }

    .rekap-box h3 {
        font-size: 32px;
        font-weight: 700;
        color: #256343;
        margin-bottom: 5px;
    }

    .rekap-box p {
        color: #666;
        margin: 0;
    }

    table.table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
    }

    table.table thead {
        background: #256343;
        color: white;
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

    .badge-hadir {
        background: #28a745;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 13px;
    }

    .badge-izin {
        background: #ffc107;
        color: #333;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 13px;
    }

    .badge-sakit {
        background: #17a2b8;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 13px;
    }

    .badge-alfa {
        background: #dc3545;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 13px;
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
            margin-bottom: 15px;
            background: white;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
        }

        table.table td {
            text-align: left !important;
            padding: 8px 10px !important;
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
    <h2 class="fw-bold mb-4" style="color:#256343;">Rekap Absensi Saya</h2>

    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="rekap-box">
                <h3>{{ $totalHadir }}</h3>
                <p>Hadir</p>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="rekap-box">
                <h3>{{ $totalIzin }}</h3>
                <p>Izin</p>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="rekap-box">
                <h3>{{ $totalSakit }}</h3>
                <p>Sakit</p>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="rekap-box">
                <h3>{{ $totalAlfa }}</h3>
                <p>Alfa</p>
            </div>
        </div>
    </div>

    <h4 class="fw-bold mb-3">Riwayat Absensi</h4>

    @if($absensiList->count() === 0)
        <div class="alert alert-info">Belum ada data absensi.</div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Mata Pelajaran</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensiList as $absensi)
                    <tr>
                        <td data-label="Tanggal">{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d M Y') }}</td>
                        <td data-label="Mata Pelajaran">{{ $absensi->jadwal->guruMapel->mapel->nama_mapel ?? '-' }}</td>
                        <td data-label="Status">
                            @if($absensi->status === 'hadir')
                                <span class="badge-hadir">Hadir</span>
                            @elseif($absensi->status === 'izin')
                                <span class="badge-izin">Izin</span>
                            @elseif($absensi->status === 'sakit')
                                <span class="badge-sakit">Sakit</span>
                            @else
                                <span class="badge-alfa">Alfa</span>
                            @endif
                        </td>
                        <td data-label="Keterangan">{{ $absensi->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $absensiList->links() }}
        </div>
    @endif
</div>
@endsection