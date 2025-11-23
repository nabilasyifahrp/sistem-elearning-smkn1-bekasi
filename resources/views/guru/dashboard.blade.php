@extends('partials.layouts-guru')

@section('content')

@php
$idGuru = auth()->user()->guru->id_guru;
$totalKelas = \App\Models\GuruMapel::where('id_guru', $idGuru)->count();

$totalSiswa = \App\Models\GuruMapel::where('id_guru', $idGuru)
->with('kelas.siswa')
->get()
->sum(function ($item) {
return $item->kelas->siswa->count();
});
@endphp

<div class="container-fluid" style="background: linear-gradient(135deg, #256343 0%, #2d7a52 100%); padding: 40px 30px; margin-bottom: 40px; border-radius: 12px;">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-2" style="color: white; font-weight: 700; font-size: 2.2rem; margin-bottom: 8px;">
                Selamat Datang Kembali!
            </h1>
            <p style="color: rgba(255, 255, 255, 0.95); font-size: 1rem; margin-bottom: 0;">
                Anda memiliki <b>{{ $totalKelas }}</b> kelas dengan total <b>{{ $totalSiswa }}</b> siswa aktif
            </p>
        </div>
    </div>
</div>

<div class="container-fluid mb-5">
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">

            <div class="search-wrapper shadow-sm" style="
                background: #ffffff;
                border-radius: 12px;
                display: flex;
                align-items: center;
                padding: 10px 16px;
                gap: 12px;
                border: 1px solid #e3e6e8;
                transition: all .25s ease;
            "
                onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.10)'"
                onmouseout="this.style.boxShadow='0 2px 6px rgba(0,0,0,0.05)'">

                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                    fill="#6c757d" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 
                             0 1.415-1.414l-3.85-3.85zm-5.442 1.398a5 
                             5 0 1 1 0-10 5 5 0 0 1 0 10z" />
                </svg>

                <input
                    type="text"
                    id="searchKelas"
                    placeholder="Cari kelas atau mata pelajaran..."
                    onkeyup="filterKelas()"
                    style="
                        border: none;
                        outline: none;
                        flex-grow: 1;
                        font-size: 1.05rem;
                        color: #333;
                        background: transparent;
                    ">
            </div>

        </div>
    </div>
</div>


<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-12">
            <h5 style="color: #256343; font-weight: 700; font-size: 1.3rem; margin-bottom: 1.5rem;">Kelas Saya</h5>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row g-4 mb-5">
        @forelse($mapelList as $item)
        <div class="col-12 col-sm-6 col-lg-4 kelas-item">
            <div class="card border-0 h-100" style="
                border-left: 6px solid #256343;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                border-radius: 10px;
                transition: all 0.3s ease;
                overflow: hidden;"
                onmouseover="this.style.boxShadow='0 8px 20px rgba(37, 99, 67, 0.15)'; this.style.transform='translateY(-4px)';"
                onmouseout="this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.08)'; this.style.transform='translateY(0)';">

                <div style="
                    background: linear-gradient(135deg, #256343 0%, #3a8f5f 100%);
                    padding: 20px;
                    color: white;">
                    <h5 class="mb-1" style="color: white; font-weight: 700; font-size: 1.1rem;">
                        {{ $item->mapel->nama_mapel }}
                    </h5>
                    <p class="small mb-0" style="color: rgba(255, 255, 255, 0.9); font-size: 0.85rem;">
                        {{ $item->kelas->tingkat }} {{ $item->kelas->jurusan }} {{ $item->kelas->kelas }}
                    </p>
                </div>

                <div class="card-body" style="padding: 24px;">
                    <div style="display: flex; flex-direction: column; gap: 16px;">

                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 12px; border-bottom: 1px solid #e9ecef;">
                            <span style="color: #6c757d; font-weight: 500; font-size: 0.95rem;">Siswa</span>
                            <span style="color: #256343; font-weight: 600; font-size: 1.25rem;">
                                {{ $item->kelas->siswa->count() }}
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 12px; border-bottom: 1px solid #e9ecef;">
                            <span style="color: #6c757d; font-weight: 500; font-size: 0.95rem;">Materi</span>
                            <span style="color: #256343; font-weight: 600; font-size: 1.25rem;">
                                {{ \App\Models\Materi::where('id_guru_mapel', $item->id_guru_mapel)->count() }}
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #6c757d; font-weight: 500; font-size: 0.95rem;">Tugas</span>
                            <span style="color: #256343; font-weight: 600; font-size: 1.25rem;">
                                {{ \App\Models\Tugas::where('id_guru_mapel', $item->id_guru_mapel)->count() }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('guru.kelas.detail', ['id_guru_mapel' => $item->id_guru_mapel]) }}"
                        class="btn w-100 mt-4" style="
                        background-color: #256343;
                        color: white;
                        border: none;
                        font-weight: 600;
                        padding: 10px 16px;
                        border-radius: 8px;
                        transition: all 0.3s;
                        text-decoration: none;
                        display: inline-block;
                        text-align: center;
                        font-size: 0.95rem;">
                        Lihat Detail
                    </a>
                </div>

            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert" style="
                border-left: 4px solid #0d6efd;
                border-radius: 8px;
                background-color: #e7f3ff;
                border: none;
                padding: 16px;
                color: #0c5de4;">
                <i class="bi bi-info-circle" style="margin-right: 8px;"></i>
                <strong>Informasi:</strong> Anda belum mengampu kelas manapun.
            </div>
        </div>
        @endforelse
    </div>
</div>

<script>
    function filterKelas() {
        let input = document.getElementById('searchKelas');
        let filter = input.value.toLowerCase();
        let items = document.getElementsByClassName('kelas-item');

        for (let i = 0; i < items.length; i++) {
            let title = items[i].querySelector('h5')?.innerText.toLowerCase() || '';
            let kelas = items[i].getElementsByTagName('p')[0]?.innerText.toLowerCase() || '';

            if (title.includes(filter) || kelas.includes(filter)) {
                items[i].style.display = '';
            } else {
                items[i].style.display = 'none';
            }
        }
    }
</script>

@endsection