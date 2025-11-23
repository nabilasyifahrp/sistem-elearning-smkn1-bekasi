@extends('partials.layouts-guru')

@section('content')

<style>
    .stat-card {
        border-radius: 14px;
        padding: 22px 0;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        transition: 0.3s;
        border: none;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.10);
    }

    .stat-icon {
        font-size: 32px;
        color: #256343;
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 1.7rem;
        font-weight: 600;
        color: #256343;
        margin: 0;
    }

    .stat-label {
        margin: 0;
        font-size: 0.92rem;
        color: #6c757d;
    }

    .nav-tabs .nav-link {
        color: #256343 !important;
        font-weight: 600;
        border-radius: 8px 8px 0 0;
    }

    .nav-tabs .nav-link.active {
        background-color: #256343 !important;
        color: white !important;
        font-weight: 700;
    }

    .list-group-item {
        transition: 0.2s;
    }

    .list-group-item:hover {
        background: #f8f9fa;
    }

    @media (max-width: 768px) {
        .stat-card {
            padding: 18px 0;
        }

        .stat-value {
            font-size: 1.4rem;
        }

        .stat-icon {
            font-size: 28px;
        }

        .nav-tabs .nav-link {
            font-size: 0.85rem;
        }
    }
</style>

<div class="container-fluid py-4">

    <div class="row mb-5">
        <div class="col-12">
            <div class="p-5 rounded-4 shadow-sm"
                style="background: linear-gradient(135deg, #1f5a36 0%, #2f7f52 50%, #3ba26b 100%); color: white; border-radius: 18px;">

                <h1 class="h3 mb-2 fw-bold" style="font-size: 1.9rem;">
                    {{ $guruMapel->mapel->nama_mapel }} {{ $guruMapel->kelas->nama_kelas }}
                </h1>
                <p class="mb-0 opacity-75" style="font-size: 1.05rem;">
                    Tahun Ajaran: {{ $guruMapel->tahun_ajaran }}
                </p>

            </div>
        </div>
    </div>

    <div class="row justify-content-center mb-4">
        <div class="col-10 col-md-3 mb-3">
            <div class="card stat-card text-center">
                <i class="bi bi-people-fill stat-icon"></i>
                <h3 class="stat-value">{{ $guruMapel->kelas->siswa->count() }}</h3>
                <p class="stat-label">Total Siswa</p>
            </div>
        </div>

        <div class="col-10 col-md-3 mb-3">
            <div class="card stat-card text-center">
                <i class="bi bi-journal-bookmark-fill stat-icon"></i>
                <h3 class="stat-value">{{ $guruMapel->materi->count() }}</h3>
                <p class="stat-label">Materi</p>
            </div>
        </div>

        <div class="col-10 col-md-3 mb-3">
            <div class="card stat-card text-center">
                <i class="bi bi-clipboard-check-fill stat-icon"></i>
                <h3 class="stat-value">
                    {{
                        \App\Models\Tugas::where('id_guru_mapel', $guruMapel->id_guru_mapel)
                        ->where('deadline', '>=', now()->toDateString())
                        ->count() 
                    }}
                </h3>
                <p class="stat-label">Tugas Aktif</p>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">

            <ul class="nav nav-tabs flex-wrap" id="classDetailTabs">

                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#materi">
                        <i class="bi bi-file-earmark-pdf"></i> Materi
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tugas">
                        <i class="bi bi-clipboard-check"></i> Tugas
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#siswa">
                        <i class="bi bi-people"></i> Siswa
                    </button>
                </li>

                <li class="nav-item">
                    <a href="{{ route('guru.absensi.kelas', $guruMapel->id_guru_mapel) }}" class="nav-link">
                        <i class="bi bi-calendar-check"></i> Absensi
                    </a>
                </li>

            </ul>
        </div>
    </div>

    <div class="tab-content">

        <div class="tab-pane fade show active" id="materi">

            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center"
                    style="background-color: #256343; color:white; border-radius: 0.5rem 0.5rem 0 0;">

                    <h5 class="mb-0"><i class="bi bi-file-earmark"></i> Daftar Materi</h5>

                    <a href="{{ route('guru.kelas.materi.create', $guruMapel->id_guru_mapel) }}"
                        class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle"></i> Tambah Materi
                    </a>
                </div>

                <div class="card-body p-0">

                    @if($materiList->isEmpty())
                    <p class="text-muted text-center py-5 mb-0">Belum ada materi untuk kelas ini.</p>
                    @else

                    <div class="list-group list-group-flush">

                        @foreach($materiList as $materi)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">

                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-500">
                                    <i class="bi bi-file-earmark"></i> {{ $materi->judul_materi }}
                                </h6>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar"></i>
                                    Diupload {{ \Carbon\Carbon::parse($materi->tanggal_upload)->format('d M Y') }}
                                </p>
                            </div>

                            <div class="d-flex align-items-center gap-2">

                                @if($materi->file_path)
                                <a href="{{ asset('storage/'.$materi->file_path) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-secondary"
                                    title="Lihat Materi">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endif

                                <a href="{{ route('guru.materi.edit', $materi->id_materi) }}"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('guru.materi.delete', $materi->id_materi) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Hapus materi ini?')"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>

                        </div>
                        @endforeach

                    </div>

                    @endif
                </div>
            </div>

        </div>

        <div class="tab-pane fade" id="tugas">

            <div class="card border-0 shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center"
                    style="background-color: #256343; color:white; border-radius:0.5rem 0.5rem 0 0;">

                    <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Daftar Tugas</h5>

                    <a href="{{ route('guru.kelas.tugas.create', $guruMapel->id_guru_mapel) }}"
                        class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle"></i> Tambah Tugas
                    </a>

                </div>

                <div class="card-body p-0">

                    @if($tugasList->isEmpty())
                    <p class="text-muted text-center py-5 mb-0">Belum ada tugas untuk kelas ini.</p>
                    @else

                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-center align-middle">

                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th>No</th>
                                    <th>Judul Tugas</th>
                                    <th>Tenggat</th>
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
                                        <span class="badge bg-info text-dark">
                                            {{ $tugas->pengumpulan->count() }}/{{ $guruMapel->kelas->siswa->count() }}
                                        </span>
                                    </td>

                                    <td>
                                        @if(now() < $tugas->deadline)
                                            <span class="badge bg-warning text-dark">Sedang Berjalan</span>
                                            @else
                                            <span class="badge bg-success">Selesai</span>
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
                                            @method('DELETE')
                                            <button onclick="return confirm('Hapus tugas?')"
                                                class="btn btn-sm btn-outline-danger">
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

        <div class="tab-pane fade" id="siswa">

            <div class="card border-0 shadow-sm">

                <div class="card-header" style="background-color:#256343; color:white;">
                    <div class="row align-items-center">

                        <div class="col-md-6">
                            <h5 class="mb-0"><i class="bi bi-people"></i>
                                Daftar Siswa ({{ $guruMapel->kelas->siswa->count() }})
                            </h5>
                        </div>

                        <div class="col-md-6">
                            <input type="text" class="form-control form-control-sm"
                                id="searchSiswa" placeholder="Cari siswa...">
                        </div>

                    </div>
                </div>

                @php
                $tugasIDs = $tugasList->pluck('id_tugas');
                @endphp

                <div class="card-body p-0">
                    <div class="table-responsive">

                        <table class="table table-hover mb-0 text-center align-middle">

                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Tugas Dikumpulkan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody id="tableSiswa">

                                @foreach($guruMapel->kelas->siswa as $siswa)

                                @php
                                $jumlahDikumpulkan = $siswa->pengumpulanTugas()
                                ->whereIn('id_tugas', $tugasIDs)
                                ->count();
                                @endphp

                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $siswa->nis }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $jumlahDikumpulkan }}/{{ $tugasIDs->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('guru.kelas.siswa.detail', [$guruMapel->id_guru_mapel, $siswa->nis]) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<script>
    document.getElementById("searchSiswa").addEventListener("keyup", function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#tableSiswa tr");

        rows.forEach(row => {
            let nama = row.cells[2].innerText.toLowerCase();
            row.style.display = nama.includes(filter) ? "" : "none";
        });
    });
</script>

@endsection