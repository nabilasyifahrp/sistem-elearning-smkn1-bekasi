@extends('partials.layouts-guru')

@section('content')

@php
use Carbon\Carbon;
Carbon::setLocale('id');
@endphp

<div class="container mt-4">

    <h3 class="fw-bold mb-4" style="color:#256343; font-size:2rem;">
        Pengumuman
    </h3>

    <div class="row mb-4">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="search-wrapper shadow-sm"
                style="
                    background: #ffffff;
                    border-radius: 14px;
                    display: flex;
                    align-items: center;
                    padding: 12px 18px;
                    gap: 12px;
                    border: 1px solid #dfe4e6;
                    transition: .25s;
                "
                onmouseover="this.style.boxShadow='0 6px 16px rgba(0,0,0,0.12)'"
                onmouseout="this.style.boxShadow='0 2px 6px rgba(0,0,0,0.05)'">

                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#6c757d"
                    viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 
                        1.398l3.85 3.85a1 1 0 0 0 
                        1.415-1.414l-3.85-3.85zm-5.442 
                        1.398a5 5 0 1 1 0-10 5 5 0 0 
                        1 0 10z" />
                </svg>

                <input type="text"
                    id="searchPengumuman"
                    placeholder="Cari pengumuman..."
                    onkeyup="filterPengumuman()"
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

    <div id="pengumumanContainer">
        @foreach ($pengumuman as $item)
        <div class="card mb-3 shadow-sm pengumuman-item"
            style="
                    border-left: 5px solid #256343;
                    border-radius: 14px;
                    transition: .25s;
                "
            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.12)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 6px rgba(0,0,0,0.05)'">

            <div class="card-body" style="cursor:pointer;" onclick="window.location='{{ route('guru.pengumuman.show', $item->id_pengumuman) }}'">

                <h5 class="card-title fw-bold" style="font-size:1.25rem; color:#256343;">
                    {{ $item->judul }}
                </h5>

                <small class="text-muted d-block mb-2">
                    {{ Carbon::parse($item->tanggal_upload)->translatedFormat('l, d F Y') }}
                </small>

                <p class="mt-2" style="line-height:1.6; color:#444;">
                    {{ $item->isi }}
                </p>

            </div>
        </div>
        @endforeach
    </div>

</div>

<script>
    function filterPengumuman() {
        let filter = document.getElementById('searchPengumuman').value.toLowerCase();
        let items = document.getElementsByClassName('pengumuman-item');

        for (let i = 0; i < items.length; i++) {
            let title = items[i].querySelector('.card-title').innerText.toLowerCase();
            let isi = items[i].querySelector('p').innerText.toLowerCase();

            items[i].style.display = title.includes(filter) || isi.includes(filter) ?
                '' :
                'none';
        }
    }
</script>

@endsection