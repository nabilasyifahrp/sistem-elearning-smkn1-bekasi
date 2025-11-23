@extends('partials.layouts-siswa')

@section('content')
<div class="container-fluid py-4">

    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0" style="color: #256343;">
                {{ $guruMapel->mapel->nama_mapel }}
            </h1>
            <p class="text-muted">
                Guru: {{ $guruMapel->guru->nama }} |
                Kelas: {{ $guruMapel->kelas->tingkat }} {{ $guruMapel->kelas->jurusan }} {{ $guruMapel->kelas->kelas }}
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if($absensiHariIni)

    @if($absensiHariIni->status === 'hadir')
    <div class="alert alert-success">
        <i class="bi bi-check-circle"></i>
        <strong>Anda sudah absen hari ini!</strong> Status: Hadir
    </div>

    @elseif($absensiHariIni->id_pengajuan && in_array($absensiHariIni->status, ['izin', 'sakit']))
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        <strong>Anda tercatat {{ $absensiHariIni->status }} hari ini</strong>
        (Izin disetujui wali kelas)
    </div>

    @elseif($absensiHariIni->status === 'alfa' && $absensiHariIni->keterangan === null && !$izinHariIni)
    <div class="card border-warning mb-4">
        <div class="card-body bg-warning bg-opacity-10">
            <h5 class="card-title text-warning">
                <i class="bi bi-exclamation-triangle"></i> Absensi Tersedia!
            </h5>
            <p class="mb-3">Silakan klik tombol di bawah untuk melakukan absensi hari ini.</p>

            <form action="{{ route('siswa.absensi.hadir', $guruMapel->id_guru_mapel) }}" method="POST">
                @csrf
                <button type="submit" class="btn text-white" style="background-color: #256343;">
                    <i class="bi bi-hand-thumbs-up"></i> Saya Hadir
                </button>
            </form>
        </div>
    </div>

    @elseif($absensiHariIni->status === 'alfa' && $absensiHariIni->keterangan === null && $izinHariIni && $izinHariIni->status === 'pending')
    <div class="alert alert-warning">
        <i class="bi bi-hourglass-split"></i>
        <strong>Pengajuan izin Anda sedang menunggu persetujuan wali kelas</strong>
        <br>
        <small>Jenis: {{ ucfirst($izinHariIni->jenis_izin) }} |
            Periode: {{ $izinHariIni->tanggal_mulai->format('d M') }} - {{ $izinHariIni->tanggal_selesai->format('d M Y') }}</small>
    </div>

    @elseif($absensiHariIni->status === 'alfa' && $absensiHariIni->keterangan !== null)
    <div class="alert alert-danger">
        <i class="bi bi-x-circle"></i>
        <strong>Anda tercatat Alfa hari ini</strong>
        <br>
        <small>{{ $absensiHariIni->keterangan }}</small>
    </div>

    @endif

    @elseif($izinHariIni && $izinHariIni->status === 'pending')
    <div class="alert alert-warning">
        <i class="bi bi-hourglass-split"></i>
        <strong>Pengajuan izin Anda sedang menunggu persetujuan wali kelas</strong>
        <br>
        <small>Jenis: {{ ucfirst($izinHariIni->jenis_izin) }} |
            Periode: {{ $izinHariIni->tanggal_mulai->format('d M') }} - {{ $izinHariIni->tanggal_selesai->format('d M Y') }}</small>
    </div>

    @elseif($izinHariIni && $izinHariIni->status === 'disetujui')
    <div class="alert alert-success">
        <i class="bi bi-check-circle"></i>
        <strong>Izin Anda telah disetujui wali kelas</strong>
        <br>
        <small>Anda akan tercatat {{ $izinHariIni->jenis_izin }} ketika guru membuka sesi absensi</small>
    </div>

    @endif


    <div class="row mb-3">
        <div class="col-12">
            <ul class="nav nav-tabs" id="kelasDetailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="materi-tab" data-bs-toggle="tab"
                        data-bs-target="#materi" type="button" style="color: #256343;">
                        <i class="bi bi-file-earmark-pdf"></i> Materi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tugas-tab" data-bs-toggle="tab"
                        data-bs-target="#tugas" type="button" style="color: #256343;">
                        <i class="bi bi-clipboard-check"></i> Tugas
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="absensi-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#absensi"
                        type="button"
                        style="color: #256343;">
                        <i class="bi bi-calendar-check"></i> Absensi
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="kelasDetailContent">

        <div class="tab-pane fade show active" id="materi" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background-color: #256343; color: white;">
                    <h5 class="mb-0">Daftar Materi</h5>
                </div>
                <div class="card-body">
                    @if($materiList->isEmpty())
                    <p class="text-muted text-center">Belum ada materi yang diunggah.</p>
                    @else
                    <div class="list-group">
                        @foreach($materiList as $materi)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    {{ $materi->judul_materi }}
                                </h6>
                                <p class="text-muted small mb-0">
                                    Diupload {{ $materi->tanggal_upload->format('d M Y') }}
                                </p>
                                @if($materi->deskripsi)
                                <p class="small mb-0 mt-2">{{ $materi->deskripsi }}</p>
                                @endif
                            </div>
                            <div>
                                @if($materi->file_path)
                                <a href="{{ asset('storage/' . $materi->file_path) }}"
                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download"></i> Unduh
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tugas" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background-color: #256343; color: white;">
                    <h5 class="mb-0">Daftar Tugas</h5>
                </div>
                <div class="card-body">
                    @if($tugasList->isEmpty())
                    <p class="text-muted text-center">Belum ada tugas untuk mata pelajaran ini.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: #f0f0f0;">
                                <tr>
                                    <th>No</th>
                                    <th>Judul Tugas</th>
                                    <th>Tenggat Waktu</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tugasList as $tugas)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $tugas->judul_tugas }}</td>
                                    <td>{{ $tugas->deadline->format('d M Y') }}</td>
                                    <td>
                                        @php
                                        $pengumpulan = \App\Models\PengumpulanTugas::where('id_tugas', $tugas->id_tugas)
                                        ->where('nis', $siswa->nis)
                                        ->first();
                                        @endphp

                                        @if($pengumpulan)
                                        @if($pengumpulan->nilai)
                                        <span class="badge" style="background-color: #28a745;">
                                            Dinilai ({{ $pengumpulan->nilai }})
                                        </span>
                                        @else
                                        <span class="badge" style="background-color: #ffc107;">
                                            Sudah Dikumpulkan
                                        </span>
                                        @endif
                                        @else
                                        @if(now() > $tugas->deadline)
                                        <span class="badge" style="background-color: #dc3545;">
                                            Terlambat
                                        </span>
                                        @else
                                        <span class="badge" style="background-color: #6c757d;">
                                            Belum Dikumpulkan
                                        </span>
                                        @endif
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('siswa.tugas.detail', $tugas->id_tugas) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
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

        <div class="tab-pane fade" id="absensi" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-white" style="background-color: #256343;">
                    <h5 class="mb-0">Rekap Absensi Bulanan</h5>
                </div>

                <div class="card-body">
                    <form method="GET" class="row g-2 mb-4">
                        <input type="hidden" name="tab" value="absensi">

                        <div class="col-md-3">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-control">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('bulan', now()->month) == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                    @endfor
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-control">
                                @for($y = now()->year - 3; $y <= now()->year; $y++)
                                    <option value="{{ $y }}" {{ request('tahun', now()->year) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                    @endfor
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn text-white" style="background-color: #256343;">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                        </div>
                    </form>

                    @php
                    $bulan = request('bulan', now()->month);
                    $tahun = request('tahun', now()->year);

                    $absensiMapel = \App\Models\Absensi::where('nis', $siswa->nis)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->where(function($q) {
                    $q->where('status', '!=', 'alfa')
                    ->orWhere(function($sub) {
                    $sub->where('status', 'alfa')
                    ->whereNotNull('keterangan');
                    });
                    })
                    ->whereHas('jadwal', function($q) use ($guruMapel) {
                    $q->where('id_guru_mapel', $guruMapel->id_guru_mapel);
                    })
                    ->orderBy('tanggal')
                    ->get();

                    $rekap = [
                    'pertemuan' => $absensiMapel->count(),
                    'hadir' => $absensiMapel->where('status', 'hadir')->count(),
                    'izin' => $absensiMapel->where('status', 'izin')->count(),
                    'sakit' => $absensiMapel->where('status', 'sakit')->count(),
                    'alfa' => $absensiMapel->where('status', 'alfa')->count(),
                    ];
                    @endphp

                    <div class="row text-center mb-4">
                        <div class="col-md-3">
                            <h6>Pertemuan</h6>
                            <h4>{{ $rekap['pertemuan'] }}</h4>
                        </div>
                        <div class="col-md-2">
                            <h6>Hadir</h6>
                            <h4 class="text-success">{{ $rekap['hadir'] }}</h4>
                        </div>
                        <div class="col-md-2">
                            <h6>Izin</h6>
                            <h4 class="text-warning">{{ $rekap['izin'] }}</h4>
                        </div>
                        <div class="col-md-2">
                            <h6>Sakit</h6>
                            <h4 class="text-info">{{ $rekap['sakit'] }}</h4>
                        </div>
                        <div class="col-md-2">
                            <h6>Alfa</h6>
                            <h4 class="text-danger">{{ $rekap['alfa'] }}</h4>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <h6>Persentase Kehadiran</h6>
                        <h3 style="color:#256343;">
                            {{ $rekap['pertemuan'] > 0 ? number_format(($rekap['hadir'] / $rekap['pertemuan']) * 100, 1) : 0 }}%
                        </h3>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensiMapel as $a)
                            <tr>
                                <td>{{ $a->tanggal->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $a->status == 'hadir' ? 'success' :
                                        ($a->status == 'izin' ? 'warning' :
                                        ($a->status == 'sakit' ? 'info' : 'danger'))
                                    }}">
                                        {{ ucfirst($a->status) }}
                                    </span>
                                </td>
                                <td>{{ $a->keterangan ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada absensi bulan ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

</div>
@endsection