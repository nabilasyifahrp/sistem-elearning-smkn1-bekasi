@extends('partials.layouts-siswa')

@section('content')
<style>
    .detail-box {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .detail-box h4 {
        color: #256343;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .form-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .btn-submit {
        background: #256343;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        font-weight: 600;
    }

    .btn-submit:hover {
        background: #1e4d32;
    }

    .btn-back {
        background: #6c757d;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
    }

    .badge-deadline {
        background: #dc3545;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
    }

    .nilai-box {
        background: #d4edda;
        border: 2px solid #c3e6cb;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
    }

    .nilai-box h3 {
        color: #155724;
        font-size: 48px;
        font-weight: 700;
        margin: 0;
    }
</style>

<div>
    <a href="javascript:history.back()" class="btn-back mb-3">← Kembali</a>

    <div class="detail-box">
        <h4>{{ $tugas->judul_tugas }}</h4>
        
        <p><strong>Mata Pelajaran:</strong> {{ $tugas->mapel->nama_mapel ?? '-' }}</p>
        <p><strong>Guru:</strong> {{ $tugas->guru->nama ?? '-' }}</p>
        <p><strong>Kelas:</strong> {{ $tugas->kelas->tingkat }} {{ $tugas->kelas->jurusan }} {{ $tugas->kelas->kelas }}</p>
        <p><strong>Deadline:</strong> <span class="badge-deadline">{{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}</span></p>
        
        <hr>
        
        <h5 class="fw-bold">Deskripsi Tugas:</h5>
        <p>{{ $tugas->deskripsi }}</p>

        @if($tugas->file_path)
            <a href="{{ asset('storage/' . $tugas->file_path) }}" target="_blank" class="btn btn-sm" style="background:#256343; color:white;">
                <i class="bi bi-download"></i> Unduh File Tugas
            </a>
        @endif
    </div>

    @if($pengumpulan)
        <div class="detail-box">
            <h4>Status Pengumpulan</h4>
            
            <p><strong>Tanggal Dikumpulkan:</strong> {{ \Carbon\Carbon::parse($pengumpulan->tanggal_pengumpulan)->format('d M Y, H:i') }}</p>
            
            <h5 class="fw-bold mt-3">Jawaban Anda:</h5>
            <p>{{ $pengumpulan->isi_tugas }}</p>
            
            @if($pengumpulan->file_path)
                <a href="{{ asset('storage/' . $pengumpulan->file_path) }}" target="_blank" class="btn btn-sm" style="background:#256343; color:white;">
                    <i class="bi bi-file-earmark"></i> Lihat File yang Dikumpulkan
                </a>
            @endif

            @if($pengumpulan->nilai !== null)
                <div class="nilai-box text-center">
                    <p class="mb-1 text-muted">Nilai Anda:</p>
                    <h3>{{ $pengumpulan->nilai }}</h3>
                </div>

                @if($pengumpulan->feedback)
                    <div class="mt-3">
                        <h5 class="fw-bold">Feedback dari Guru:</h5>
                        <p>{{ $pengumpulan->feedback }}</p>
                    </div>
                @endif
            @else
                <div class="alert alert-info mt-3">
                    Tugas Anda sudah dikumpulkan dan sedang menunggu penilaian dari guru.
                </div>
            @endif
        </div>
    @else
        @if(\Carbon\Carbon::parse($tugas->deadline)->isPast())
            <div class="alert alert-danger">
                <strong>Deadline sudah terlewat!</strong> Anda tidak dapat lagi mengumpulkan tugas ini.
            </div>
        @else
            <div class="form-card">
                <h4>Kumpulkan Tugas</h4>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('siswa.kumpulkan_tugas', $tugas->id_tugas) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jawaban Tugas <span class="text-danger">*</span></label>
                        <textarea name="isi_tugas" rows="8" class="form-control" placeholder="Tulis jawaban tugas Anda di sini..." required>{{ old('isi_tugas') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Lampiran File (Opsional)</label>
                        <input type="file" name="file_path" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar">
                        <small class="text-muted">Format: PDF, DOC, DOCX, PPT, PPTX, ZIP, RAR (Max 10MB)</small>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-send"></i> Kumpulkan Tugas
                    </button>
                </form>
            </div>
        @endif
    @endif
</div>

@if(session('success'))
    <div id="flash-message" style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:12px 16px; border-radius:6px; margin-top:20px; position:fixed; top:90px; right:20px; z-index:9999; transition: opacity 0.5s ease;">
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