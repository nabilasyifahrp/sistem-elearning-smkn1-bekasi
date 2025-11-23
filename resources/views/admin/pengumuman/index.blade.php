@extends('partials.layouts-admin')

@section('content')
<style>
    .btn-green {
        background: #256343;
        color: white;
        padding: 10px 18px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        border: none;
        transition: 0.2s;
        font-size: 15px;
    }

    .btn-green:hover {
        background: #1e4d32;
        color: white;
    }

    .btn-red {
        background: #dc3545;
        color: white;
        border: none;
        padding: 6px 8px;
        border-radius: 4px;
        transition: 0.2s;
    }

    .btn-red:hover {
        background: #b52a36;
    }

    .btn-action {
        padding: 6px 10px;
        border-radius: 4px;
        font-size: 16px;
        border: none;
        transition: 0.2s;
    }

    .card-announcement {
        border-left: 6px solid #256343;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        transition: 0.2s;
    }

    .card-announcement:hover {
        transform: scale(1.01);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
    }

    .search-input {
        width: 100%;
        max-width: 300px;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ccc;
        margin-bottom: 15px;
    }
</style>

<div class="container">
    <h2 class="fw-bold mb-4" style="color:#256343;">Daftar Pengumuman</h2>

    <input type="text" id="search" class="search-input" placeholder="Cari judul pengumuman...">

    <a href="{{ route('admin.pengumuman.create') }}" class="btn-green mb-3 d-inline-block">+ Tambah Pengumuman</a>

    <div id="pengumuman-list">
        @foreach ($pengumumanList as $pengumuman)
        <div class="card card-announcement mb-3">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div style="max-width: 80%;">
                    <h4 class="fw-bold" style="color:#256343;">{{ $pengumuman->judul }}</h4>
                    <p class="text-muted mb-2" style="font-size: 14px;">
                        {{ \Carbon\Carbon::parse($pengumuman->tanggal_upload)->translatedFormat('d F Y') }}
                    </p>
                    <p style="color:#444; font-size:15px;">{{ Str::limit(strip_tags($pengumuman->isi), 140) }}</p>
                </div>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('admin.pengumuman.show', $pengumuman->id_pengumuman) }}" class="btn-action btn-green">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('admin.pengumuman.edit', $pengumuman->id_pengumuman) }}" class="btn-action btn-green">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('admin.pengumuman.destroy', $pengumuman->id_pengumuman) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn-red btn-action" onclick="return confirm('Yakin ingin menghapus?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const listContainer = document.getElementById('pengumuman-list');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const cards = listContainer.querySelectorAll('.card-announcement');

            cards.forEach(card => {
                const title = card.querySelector('h4').textContent.toLowerCase();
                if (title.includes(query)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection