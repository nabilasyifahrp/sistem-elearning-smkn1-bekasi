@extends('partials.layouts-siswa')

@section('content')
<style>
    .detail-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin: 20px auto;
        max-width: 900px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .pengumuman-title {
        font-size: 28px;
        font-weight: 700;
        color: #256343;
        margin-bottom: 10px;
        line-height: 1.3;
    }

    .pengumuman-date {
        font-size: 14px;
        color: #888888;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pengumuman-isi {
        font-size: 16px;
        line-height: 1.8;
        color: #333;
        margin-bottom: 25px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: pre-wrap;
    }

    .lampiran-section {
        background: #f8f9fa;
        border: 2px dashed #256343;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .lampiran-section h5 {
        color: #256343;
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 18px;
    }

    .lampiran-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: white;
        border-radius: 8px;
        text-decoration: none;
        color: #256343;
        transition: 0.2s;
        border: 1px solid #e0e0e0;
    }

    .lampiran-link:hover {
        background: #e7f0ec;
        transform: translateX(5px);
        color: #1e4d32;
    }

    .lampiran-link i {
        font-size: 24px;
    }

    .btn-back {
        background: #6c757d;
        color: white;
        padding: 10px 18px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
        margin-bottom: 20px;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
    }

    img.preview {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .divider {
        border: 0;
        height: 1px;
        background: linear-gradient(to right, transparent, #256343, transparent);
        margin: 30px 0;
    }

    @media (max-width: 576px) {
        .detail-card {
            padding: 20px;
        }

        .pengumuman-title {
            font-size: 22px;
        }

        .pengumuman-isi {
            font-size: 15px;
        }

        img.preview {
            max-width: 100%;
        }

        .lampiran-link {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="container">
    <a href="{{ route('siswa.pengumuman') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pengumuman
    </a>

    <div class="detail-card">

        <div class="pengumuman-title">{{ $pengumuman->judul }}</div>
        
        <div class="pengumuman-date">
            <i class="bi bi-calendar3"></i>
            {{ \Carbon\Carbon::parse($pengumuman->tanggal_upload)->isoFormat('dddd, D MMMM YYYY') }}
        </div>

        <hr class="divider">

        <div class="pengumuman-isi">
            {!! nl2br(e($pengumuman->isi)) !!}
        </div>

        @if($pengumuman->file_path)
            <div class="lampiran-section">
                <h5><i class="bi bi-paperclip"></i> Lampiran</h5>
                
                @php
                    $fileExt = strtolower(pathinfo($pengumuman->file_path, PATHINFO_EXTENSION));
                    $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $fileName = basename($pengumuman->file_path);
                @endphp

                @if(in_array($fileExt, $imageExts))

                    <img src="{{ asset('storage/' . $pengumuman->file_path) }}" 
                         alt="Lampiran Gambar" 
                         class="preview">
                    <a href="{{ asset('storage/' . $pengumuman->file_path) }}" 
                       target="_blank" 
                       class="lampiran-link">
                        <i class="bi bi-download"></i>
                        <span>Unduh Gambar ({{ $fileName }})</span>
                    </a>
                @else
 
                    <a href="{{ asset('storage/' . $pengumuman->file_path) }}" 
                       target="_blank" 
                       class="lampiran-link">
                        <i class="bi bi-file-earmark-arrow-down"></i>
                        <div>
                            <strong>{{ $fileName }}</strong>
                            <br>
                            <small class="text-muted">Klik untuk membuka atau mengunduh</small>
                        </div>
                    </a>
                @endif
            </div>
        @endif

        <div class="mt-4 pt-3 border-top">
            <small class="text-muted">
                <i class="bi bi-person-circle"></i> 
                Dipublikasikan oleh: <strong>{{ $pengumuman->user->email ?? 'Admin' }}</strong>
            </small>
        </div>
    </div>
</div>

@if(session('success'))
    <div id="flash-message" style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:12px 16px; border-radius:6px; position:fixed; top:90px; right:20px; z-index:9999; transition: opacity 0.5s ease;">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            const msg = document.getElementById('flash-message');
            if(msg) {
                msg.style.opacity = "0";
                setTimeout(() => msg.remove(), 500);
            }
        }, 3000);
    </script>
@endif
@endsection