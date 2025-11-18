@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-5">
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="mb-2" style="color: #256343; font-weight: 700; font-size: 2.5rem;">
                        Selamat Datang Kembali
                    </h1>
                    <p class="text-muted mb-0" style="font-size: 1.1rem;">
                        <i class="bi bi-calendar3"></i> {{ now()->format('l, d F Y') }}
                    </p>
                </div>
                <div class="text-end">
                    <p class="text-muted small mb-2">Logged in as</p>
                    <p style="color: #256343; font-weight: 600; font-size: 1.1rem;">{{ Auth::user()->name ?? 'Guru' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12 mb-3">
            <h5 style="color: #256343; font-weight: 600;">Rekapitulasi Absensi Hari Ini</h5>
        </div>
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #28a745;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1" style="font-weight: 500;">Hadir</p>
                            <h2 class="mb-0" style="color: #28a745; font-weight: 700;">24</h2>
                        </div>
                        <div style="width: 60px; height: 60px; background-color: #e8f5e9; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-check-circle" style="font-size: 2rem; color: #28a745;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #ffc107;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1" style="font-weight: 500;">Izin</p>
                            <h2 class="mb-0" style="color: #ffc107; font-weight: 700;">3</h2>
                        </div>
                        <div style="width: 60px; height: 60px; background-color: #fff8e1; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-info-circle" style="font-size: 2rem; color: #ffc107;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #ff9800;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1" style="font-weight: 500;">Sakit</p>
                            <h2 class="mb-0" style="color: #ff9800; font-weight: 700;">2</h2>
                        </div>
                        <div style="width: 60px; height: 60px; background-color: #ffe0b2; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-exclamation-circle" style="font-size: 2rem; color: #ff9800;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #dc3545;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1" style="font-weight: 500;">Alfa</p>
                            <h2 class="mb-0" style="color: #dc3545; font-weight: 700;">1</h2>
                        </div>
                        <div style="width: 60px; height: 60px; background-color: #ffebee; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-x-circle" style="font-size: 2rem; color: #dc3545;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <a href="{{ route('guru.absensi.index') }}" class="btn btn-lg" style="background-color: #256343; color: white; padding: 12px 24px; font-weight: 600; transition: all 0.3s; border: none;">
                <i class="bi bi-clipboard-check"></i> Kelola Absensi & Lihat Rekap
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <h5 style="color: #256343; font-weight: 600;">Kelas Saya</h5>
        </div>

        @forelse($mapelList as $item)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100" style="overflow: hidden; transition: all 0.3s; cursor: pointer;">
                <div style="height: 100px; background: linear-gradient(135deg, #256343, #3a8f5f); position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -10px; right: -10px; width: 100px; height: 100px; background-color: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                </div>

                <div class="card-body">
                    <h5 class="card-title" style="color: #256343; font-weight: 700; margin-bottom: 1rem;">
                        {{ $item->mapel->nama_mapel }}
                    </h5>

                    <p class="small mb-3" style="color: #666; font-weight: 500;">
                        {{ $item->kelas->tingkat }} {{ $item->kelas->jurusan }} {{ $item->kelas->kelas }}
                    </p>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div style="background-color: #f0f0f0; padding: 12px; border-radius: 8px; text-align: center;">
                                <p class="text-muted small mb-1">Siswa</p>
                                <p style="color: #256343; font-weight: 700; font-size: 1.3rem; margin-bottom: 0;">
                                    {{ $item->kelas->siswa->count() }}
                                </p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div style="background-color: #f0f0f0; padding: 12px; border-radius: 8px; text-align: center;">
                                <p class="text-muted small mb-1">Materi</p>
                                <p style="color: #256343; font-weight: 700; font-size: 1.3rem; margin-bottom: 0;">
                                    {{ \App\Models\Materi::where('id_guru', $item->id_guru)
                                        ->where('id_mapel', $item->id_mapel)
                                        ->where('id_kelas', $item->id_kelas)
                                        ->count() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('guru.kelas.detail', ['id_guru_mapel' => $item->id_guru_mapel]) }}"
                        class="btn btn-sm w-100" style="background-color: #256343; color: white; border: none; font-weight: 600; transition: all 0.3s;">
                        <i class="bi bi-arrow-right"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle"></i> Anda belum mengampu kelas manapun.
            </div>
        </div>
        @endforelse
    </div>

</div>

<style>
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(37, 99, 67, 0.15) !important;
    }
</style>

@endsection