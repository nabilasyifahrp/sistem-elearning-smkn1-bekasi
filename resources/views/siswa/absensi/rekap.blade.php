@extends('partials.layouts-siswa')

@section('content')
<div class="container-fluid py-4">

    <h3 class="mb-4" style="color: #256343;">
        Rekap Absensi Bulanan
    </h3>

    <form action="{{ route('siswa.absensi.rekap') }}" method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <label class="form-label">Bulan</label>
            <select name="bulan" class="form-control">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                    @endfor
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Tahun</label>
            <select name="tahun" class="form-control">
                @for($tahunOption = now()->year - 3; $tahunOption <= now()->year; $tahunOption++)
                    <option value="{{ $tahunOption }}" {{ $tahun == $tahunOption ? 'selected' : '' }}>
                        {{ $tahunOption }}
                    </option>
                    @endfor
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn text-white" style="background-color: #256343;">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </div>
    </form>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header text-white" style="background-color: #256343;">
            <h5 class="mb-0">Rekap Bulan
                {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}
            </h5>
        </div>

        <div class="card-body">

            <div class="row text-center mb-3">
                <div class="col-md-3">
                    <h6 class="text-muted">Pertemuan</h6>
                    <h4>{{ $rekap['pertemuan'] }}</h4>
                </div>

                <div class="col-md-2">
                    <h6 class="text-muted">Hadir</h6>
                    <h4 class="text-success">{{ $rekap['hadir'] }}</h4>
                </div>

                <div class="col-md-2">
                    <h6 class="text-muted">Izin</h6>
                    <h4 class="text-warning">{{ $rekap['izin'] }}</h4>
                </div>

                <div class="col-md-2">
                    <h6 class="text-muted">Sakit</h6>
                    <h4 class="text-info">{{ $rekap['sakit'] }}</h4>
                </div>

                <div class="col-md-2">
                    <h6 class="text-muted">Alfa</h6>
                    <h4 class="text-danger">{{ $rekap['alfa'] }}</h4>
                </div>
            </div>

            <div class="text-center mt-4">
                <h6 class="text-muted">Persentase Kehadiran</h6>
                <h3 style="color: #256343;">
                    {{ $rekap['pertemuan'] > 0 ? number_format(($rekap['hadir'] / $rekap['pertemuan']) * 100, 1) : 0 }}%
                </h3>
            </div>

        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header text-white" style="background-color: #256343;">
            <h5 class="mb-0">Detail Absensi Bulan Ini</h5>
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Mata Pelajaran</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($absensi as $row)
                    <tr>
                        <td>{{ $row->tanggal->format('d M Y') }}</td>
                        <td>{{ $row->jadwal->guruMapel->mapel->nama_mapel }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                    $row->status == 'hadir' ? 'success' :
                                    ($row->status == 'izin' ? 'warning' :
                                    ($row->status == 'sakit' ? 'info' : 'danger'))
                                }}">
                                {{ ucfirst($row->status) }}
                            </span>
                        </td>
                        <td>{{ $row->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            Tidak ada data absensi bulan ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection