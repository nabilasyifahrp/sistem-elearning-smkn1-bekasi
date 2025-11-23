@extends('partials.layouts-siswa')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0" style="color: #256343;">Pengumuman</h1>
            <p class="text-muted">Informasi dan pengumuman terbaru dari sekolah</p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <input type="text"
                id="searchInput"
                class="form-control"
                placeholder="Cari pengumuman..."
                style="max-width: 400px;">
        </div>
    </div>

    <div id="pengumumanContainer">
        @if($pengumumanList->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-megaphone" style="font-size: 3rem; color: #ccc;"></i>
                <p class="text-muted mt-3">Belum ada pengumuman.</p>
            </div>
        </div>
        @else
        @foreach($pengumumanList as $pengumuman)
        <div class="card border-0 shadow-sm mb-3 pengumuman-card"
            style="cursor: pointer; transition: 0.3s;"
            onclick="window.location='{{ route('siswa.pengumuman.show', $pengumuman->id_pengumuman) }}'">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <h5 class="fw-bold mb-2" style="color: #256343;">
                            <i class="bi bi-megaphone"></i>
                            {{ $pengumuman->judul }}
                        </h5>
                        <p class="text-muted mb-2">
                            <small>
                                <i class="bi bi-calendar"></i>
                                {{ \Carbon\Carbon::parse($pengumuman->tanggal_upload)->format('d M Y') }}

                            </small>
                        </p>
                        <p class="mb-0 text-secondary">
                            {{ Str::limit(strip_tags($pengumuman->isi), 150) }}
                        </p>
                    </div>
                    <div class="col-md-3 text-end">
                        @if($pengumuman->file_path)
                        <span class="badge bg-info">
                            <i class="bi bi-paperclip"></i> Ada Lampiran
                        </span>
                        <br>
                        @endif
                        <span class="badge" style="background-color: #256343;">
                            Lihat Selengkapnya <i class="bi bi-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="mt-4">
            {{ $pengumumanList->links() }}
        </div>

        @endif
    </div>
</div>

<style>
    .pengumuman-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(37, 99, 67, 0.15) !important;
    }

    @media (max-width: 768px) {

        .pengumuman-card .col-md-9,
        .pengumuman-card .col-md-3 {
            text-align: left !important;
        }

        .pengumuman-card .col-md-3 {
            margin-top: 10px;
        }
    }
</style>

<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const cards = document.querySelectorAll('.pengumuman-card');

        cards.forEach(card => {
            const title = card.querySelector('h5').textContent.toLowerCase();
            const content = card.querySelector('p:last-child').textContent.toLowerCase();

            if (title.includes(searchTerm) || content.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
@endsection