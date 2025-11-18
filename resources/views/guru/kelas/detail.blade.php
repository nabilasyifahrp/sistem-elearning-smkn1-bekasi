@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0" style="color: #256343;">Matematika - X IPA 1</h1>
            <p class="text-muted">Kode Kelas: MTK-101 | Tahun Ajaran: 2024/2025</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 style="color: #256343;">32</h3>
                    <p class="text-muted small mb-0">Total Siswa</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 style="color: #256343;">8</h3>
                    <p class="text-muted small mb-0">Materi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 style="color: #256343;">5</h3>
                    <p class="text-muted small mb-0">Tugas Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 style="color: #256343;">24</h3>
                    <p class="text-muted small mb-0">Menunggu Penilaian</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <ul class="nav nav-tabs" id="classDetailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" style="color: #256343;">
                        <i class="bi bi-speedometer2"></i> Ringkasan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="materi-tab" data-bs-toggle="tab" data-bs-target="#materi" type="button" style="color: #256343;">
                        <i class="bi bi-file-earmark-pdf"></i> Materi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tugas-tab" data-bs-toggle="tab" data-bs-target="#tugas" type="button" style="color: #256343;">
                        <i class="bi bi-clipboard-check"></i> Tugas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="siswa-tab" data-bs-toggle="tab" data-bs-target="#siswa" type="button" style="color: #256343;">
                        <i class="bi bi-people"></i> Siswa
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="classDetailContent">
        <div class="tab-pane fade" id="tugas" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background-color: #256343; color: white;">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5 class="mb-0">Daftar Tugas</h5>
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="{{ route('guru.kelas.tugas.create', $guruMapel->id_guru_mapel) }}"
                                class="btn btn-sm btn-light">
                                <i class="bi bi-plus-circle"></i> Buat Tugas
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($tugasList->isEmpty())
                    <p class="text-muted text-center">Belum ada tugas untuk kelas ini.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: #f0f0f0;">
                                <tr>
                                    <th>No</th>
                                    <th>Judul Tugas</th>
                                    <th>Tenggat Waktu</th>
                                    <th>Dikumpulkan</th>
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
                                        <span class="badge" style="background-color: #ffc107;">
                                            {{ $tugas->pengumpulan->count() }}/{{ $guruMapel->kelas->siswa->count() }}
                                        </span>
                                    </td>

                                    <td>
                                        @if(now() < $tugas->deadline)
                                            <span class="badge" style="background-color: #ff9800;">Sedang Berjalan</span>
                                            @else
                                            <span class="badge" style="background-color: #28a745;">Selesai</span>
                                            @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('guru.tugas.detail', $tugas->id_tugas) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('guru.tugas.edit', $tugas->id_tugas) }}"
                                            class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('guru.tugas.delete', $tugas->id_tugas) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus tugas?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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

        <div class="tab-pane fade" id="materi" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background-color: #256343; color: white;">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5 class="mb-0">Daftar Materi</h5>
                        </div>
                        <div class="col-md-3 text-end">
                            <button class="btn btn-sm btn-light">
                                <i class="bi bi-plus-circle"></i> Tambah Materi
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1"><i class="bi bi-file-earmark-pdf"></i> Persamaan Linear Satu Variabel</h6>
                                <p class="text-muted small mb-0">Diupload 15 Nov 2024 | 2.5 MB PDF</p>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-download"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1"><i class="bi bi-file-earmark-text"></i> Sistem Persamaan Linear Dua Variabel</h6>
                                <p class="text-muted small mb-0">Diupload 10 Nov 2024 | File PPT</p>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-download"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1"><i class="bi bi-link"></i> Video Materi Fungsi Kuadrat</h6>
                                <p class="text-muted small mb-0">Diupload 5 Nov 2024 | Link YouTube</p>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tugas" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background-color: #256343; color: white;">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5 class="mb-0">Daftar Tugas</h5>
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="{{ route('guru.kelas.tugas.create', $guruMapel->id_guru_mapel) }}"
                                class="btn btn-sm btn-light">
                                <i class="bi bi-plus-circle"></i> Buat Tugas
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($tugasList->isEmpty())
                    <p class="text-muted text-center">Belum ada tugas untuk kelas ini.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: #f0f0f0;">
                                <tr>
                                    <th>No</th>
                                    <th>Judul Tugas</th>
                                    <th>Tenggat Waktu</th>
                                    <th>Dikumpulkan</th>
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
                                        <span class="badge" style="background-color: #ffc107;">
                                            {{ $tugas->pengumpulan->count() }}/{{ $guruMapel->kelas->siswa->count() }}
                                        </span>
                                    </td>

                                    <td>
                                        @if(now() < $tugas->deadline)
                                            <span class="badge" style="background-color: #ff9800;">Sedang Berjalan</span>
                                            @else
                                            <span class="badge" style="background-color: #28a745;">Selesai</span>
                                            @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('guru.tugas.detail', $tugas->id_tugas) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('guru.tugas.edit', $tugas->id_tugas) }}"
                                            class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('guru.tugas.delete', $tugas->id_tugas) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus tugas?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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


        <div class="tab-pane fade" id="siswa" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background-color: #256343; color: white;">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">Daftar Siswa (32 Siswa)</h5>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control form-control-sm" placeholder="Cari siswa...">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: #f0f0f0;">
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Email</th>
                                    <th>Tugas Dikumpulkan</th>
                                    <th>Nilai Rata-rata</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>001</td>
                                    <td>Ahmad Hidayat</td>
                                    <td>ahmad@school.com</td>
                                    <td><span class="badge" style="background-color: #28a745;">3/3</span></td>
                                    <td><strong style="color: #256343;">92</strong></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>002</td>
                                    <td>Siti Nurhaliza</td>
                                    <td>siti@school.com</td>
                                    <td><span class="badge" style="background-color: #28a745;">3/3</span></td>
                                    <td><strong style="color: #256343;">88</strong></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>003</td>
                                    <td>Budi Santoso</td>
                                    <td>budi@school.com</td>
                                    <td><span class="badge" style="background-color: #ffc107;">2/3</span></td>
                                    <td><strong style="color: #256343;">75</strong></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>004</td>
                                    <td>Rini Wijaya</td>
                                    <td>rini@school.com</td>
                                    <td><span class="badge" style="background-color: #28a745;">3/3</span></td>
                                    <td><strong style="color: #256343;">85</strong></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>005</td>
                                    <td>Doni Pratama</td>
                                    <td>doni@school.com</td>
                                    <td><span class="badge" style="background-color: #28a745;">3/3</span></td>
                                    <td><strong style="color: #256343;">90</strong></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection