@extends('partials.layouts-siswa')

@section('content')
<style>
    .pengumuman-card {
        background: white;
        border-left: 6px solid #256343;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        transition: 0.2s;
        padding: 20px;
        margin-bottom: 20px;
        cursor: pointer;
    }

    .pengumuman-card:hover {
        transform: scale(1.01);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
    }

    .pengumuman-card h5 {
        color: #256343;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .search-input {
        width: 100%;
        max-width: 400px;
        padding: 10px 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
        margin-bottom: 20px;
    }
</style>

<div>
    <h2 class="fw-bold mb-4" style="color:#256343;">Pengumuman</h2>

    <input type="text" id="search" class="search-input" placeholder="Cari pengumuman...">

    <div id="pengumuman-list">
        @if($pengumumanList->count() === 0)
            <div class="alert alert-info">Belum ada pengumuman.</div>
        @else
            @foreach($pengumumanList as $pengumuman)
                <div class="pengumuman-card" onclick="window.location='{{ route('siswa.detail_pengumuman', $pengumuman->id_pengumuman) }}'">
                    <h5>{{ $pengumuman->judul }}</h5>
                    <p class="text-muted small mb-2">{{ \Carbon\Carbon::parse($pengumuman->tanggal_upload)->format('d M Y') }}</p>
                    <p class="mb-0">{{ Str::limit(strip_tags($pengumuman->isi), 200) }}</p>
                </div>
            @endforeach

            <div class="mt-3">
                {{ $pengumumanList->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const listContainer = document.getElementById('pengumuman-list');

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const cards = listContainer.querySelectorAll('.pengumuman-card');

        cards.forEach(card => {
            const title = card.querySelector('h5').textContent.toLowerCase();
            if(title.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>
@endsection