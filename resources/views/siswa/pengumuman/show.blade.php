@extends('partials.layouts-siswa')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('siswa.pengumuman.index') }}" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header" style="background-color: #256343; color: white;">
            <h5 class="mb-0"><i class="bi bi-megaphone"></i> Detail Pengumuman</h5>
        </div>
        <div class="card-body">
            <h2 class="fw-bold mb-3" style="color: #256343;">
                {{ $data->judul }}
            </h2>

            <div class="d-flex align-items-center gap-3 mb-4 text-muted">
                <span>
                    <i class="bi bi-calendar"></i>
                    {{ \Carbon\Carbon::parse($data->tanggal_upload)->format('d F Y') }}
                </span>

            </div>

            <hr>

            <div class="content-area mb-4">
                <p style="white-space: pre-wrap; line-height: 1.8;">{{ $data->isi }}</p>
            </div>

            @if($data->file_path)
            <div class="alert alert-info">
                <h6><i class="bi bi-paperclip"></i> Lampiran:</h6>

                @php
                $fileExt = strtolower(pathinfo($data->file_path, PATHINFO_EXTENSION));
                $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                @endphp

                @if(in_array($fileExt, $imageExts))
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $data->file_path) }}"
                        alt="Lampiran"
                        class="img-fluid rounded shadow-sm"
                        style="max-width: 100%; height: auto;">
                </div>
                @else
                <div class="d-flex align-items-center gap-2 mt-2">
                    <i class="bi bi-file-earmark" style="font-size: 2rem; color: #256343;"></i>
                    <div>
                        <p class="mb-1 fw-bold">{{ basename($data->file_path) }}</p>
                        <a href="{{ asset('storage/' . $data->file_path) }}"
                            target="_blank"
                            class="btn btn-sm text-white"
                            style="background-color: #256343;">
                            <i class="bi bi-download"></i> Unduh File
                        </a>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('siswa.pengumuman.index') }}"
            class="btn btn-outline-secondary">
            <i class="bi bi-list"></i> Lihat Pengumuman Lainnya
        </a>
    </div>
</div>

<style>
    .content-area {
        font-size: 16px;
        color: #333;
    }

    @media (max-width: 768px) {
        .card-body h2 {
            font-size: 1.5rem;
        }

        .content-area {
            font-size: 14px;
        }
    }
</style>
@endsection