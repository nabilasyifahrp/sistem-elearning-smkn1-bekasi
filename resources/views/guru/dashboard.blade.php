@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-5">
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="mb-2" style="color: #256343; font-weight: 700; font-size: 2.5rem;">
                        Selamat Datang Kembali!
                    </h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="input-group">
            <span class="input-group-text" style="background-color:#256343; color:white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.442 1.398a5 5 0 1 1 0-10 5 5 0 0 1 0 10z" />
                </svg>
            </span>
            <input type="text" id="searchKelas" class="form-control form-control-lg" placeholder="Cari kelas atau mata pelajaran..." onkeyup="filterKelas()">
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12 mb-4">
        <h5 style="color: #256343; font-weight: 600;">Kelas Saya</h5>
    </div>

    @forelse($mapelList as $item)
    <div class="col-md-6 col-lg-4 mb-4 kelas-item">
        <div class="card border-0 shadow-sm h-100" style="overflow: hidden; border-radius: 12px; transition: all 0.3s; cursor: pointer;">
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
                    <div class="col-4">
                        <div style="background-color: #f0f0f0; padding: 12px; border-radius: 8px; text-align: center;">
                            <p class="text-muted small mb-1">Siswa</p>
                            <p style="color: #256343; font-weight: 700; font-size: 1.2rem; margin-bottom: 0;">
                                {{ $item->kelas->siswa->count() }}
                            </p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="background-color: #f0f0f0; padding: 12px; border-radius: 8px; text-align: center;">
                            <p class="text-muted small mb-1">Materi</p>
                            <p style="color: #256343; font-weight: 700; font-size: 1.2rem; margin-bottom: 0;">
                                {{ \App\Models\Materi::where('id_guru_mapel', $item->id_guru_mapel)->count() }}
                            </p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="background-color: #f0f0f0; padding: 12px; border-radius: 8px; text-align: center;">
                            <p class="text-muted small mb-1">Tugas</p>
                            <p style="color: #256343; font-weight: 700; font-size: 1.2rem; margin-bottom: 0;">
                                {{ \App\Models\Tugas::where('id_guru_mapel', $item->id_guru_mapel)->count() }}
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

<script>
    function filterKelas() {
        let input = document.getElementById('searchKelas');
        let filter = input.value.toLowerCase();
        let items = document.getElementsByClassName('kelas-item');

        for (let i = 0; i < items.length; i++) {
            let title = items[i].getElementsByClassName('card-title')[0].innerText.toLowerCase();
            let kelas = items[i].getElementsByTagName('p')[0].innerText.toLowerCase();
            if (title.includes(filter) || kelas.includes(filter)) {
                items[i].style.display = '';
            } else {
                items[i].style.display = 'none';
            }
        }
    }
</script>

<style>
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(37, 99, 67, 0.2) !important;
    }
</style>
@endsection