@extends('partials.layouts-guru')

@section('content')

@php
use Carbon\Carbon;
Carbon::setLocale('id');
@endphp

<div class="container mt-4">
    <h3 class="fw-bold mb-4 text-success" style="color:#256343; font-size:1.8rem;">Pengumuman</h3>

    <div class="mb-4">
        <div class="input-group">
            <span class="input-group-text" style="background-color:#256343; color:white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.442 1.398a5 5 0 1 1 0-10 5 5 0 0 1 0 10z" />
                </svg>
            </span>
            <input type="text" id="searchPengumuman" class="form-control form-control-lg" placeholder="Cari pengumuman..." onkeyup="filterPengumuman()">
        </div>
    </div>

    <div id="pengumumanContainer">
        @foreach ($pengumuman as $item)
        <div class="card mb-3 shadow-sm pengumuman-item" style="border-left: 4px solid #256343; border-radius: 12px;">
            <div class="card-body">

                <h5 class="card-title fw-bold" style="font-size:1.2rem;">{{ $item->judul }}</h5>

                <small class="text-muted d-block mb-2">
                    {{ Carbon::parse($item->tanggal_upload)->translatedFormat('l, d F Y') }}
                </small>

                <p class="mt-2">{{ $item->isi }}</p>

                @if ($item->file_path)
                <div class="mt-3">
                    <a href="{{ asset('storage/' . $item->file_path) }}"
                        class="btn btn-sm"
                        style="background-color:#256343; color:white;"
                        target="_blank">
                        Lihat Lampiran
                    </a>
                </div>
                @endif

            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function filterPengumuman() {
        let input = document.getElementById('searchPengumuman');
        let filter = input.value.toLowerCase();
        let items = document.getElementsByClassName('pengumuman-item');

        for (let i = 0; i < items.length; i++) {
            let title = items[i].getElementsByClassName('card-title')[0].innerText.toLowerCase();
            let isi = items[i].getElementsByTagName('p')[0].innerText.toLowerCase();
            if (title.includes(filter) || isi.includes(filter)) {
                items[i].style.display = '';
            } else {
                items[i].style.display = 'none';
            }
        }
    }
</script>

@endsection