@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0" style="color: #256343;">Rekap Absensi</h1>
            <p class="text-muted">
                {{ $guruMapel->mapel->nama_mapel }} -
                {{ $guruMapel->kelas->tingkat }} {{ $guruMapel->kelas->jurusan }} {{ $guruMapel->kelas->kelas }}
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('guru.absensi.kelas', $guruMapel->id_guru_mapel) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header text-white" style="background-color: #256343;">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter Rekap Per Bulan</h5>
        </div>

        <div class="card-body">
            <form method="GET" class="row g-3">

                <div class="col-md-4">
                    <label class="form-label fw-bold">Pilih Bulan</label>
                    <select name="bulan" class="form-select">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $i == $bulan ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                            @endfor
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Pilih Tahun</label>
                    <select name="tahun" class="form-select">
                        @for ($t = date('Y') - 5; $t <= date('Y'); $t++)
                            <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>
                            {{ $t }}
                            </option>
                            @endfor
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn text-white" style="background-color:#256343;">
                        <i class="bi bi-search"></i> Tampilkan Rekap
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header text-white" style="background-color: #256343;">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-bar-graph"></i>
                Rekap Bulan {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}
            </h5>
        </div>

        <div class="card-body">

            @if($rekap->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-calendar-x" style="font-size: 3rem; color: #ccc;"></i>
                <p class="text-muted mt-3">Tidak ada data absensi pada bulan ini.</p>
            </div>
            @else

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f0f0f0;">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th class="text-center">Total Pertemuan</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Izin</th>
                            <th class="text-center">Sakit</th>
                            <th class="text-center">Alfa</th>
                            <th class="text-center">Persentase Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekap as $data)
                        @php
                        $persentase = $data['total'] > 0 ? ($data['hadir'] / $data['total']) * 100 : 0;
                        $badgeColor = $persentase >= 80
                        ? 'success'
                        : ($persentase >= 60 ? 'warning' : 'danger');
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data['siswa']->nis }}</td>
                            <td>{{ $data['siswa']->nama }}</td>

                            <td class="text-center">{{ $data['total'] }}</td>

                            <td class="text-center">
                                <span class="badge bg-success">{{ $data['hadir'] }}</span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-warning">{{ $data['izin'] }}</span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-info">{{ $data['sakit'] }}</span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-danger">{{ $data['alfa'] }}</span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-{{ $badgeColor }}">
                                    {{ number_format($persentase, 1) }}%
                                </span>
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection