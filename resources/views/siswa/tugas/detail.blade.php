@extends('partials.layouts-siswa')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0" style="color:#256343;">Detail Tugas</h3>
            <p class="text-muted mb-0">
                {{ $tugas->guruMapel->mapel->nama_mapel }} —
                {{ $tugas->guruMapel->kelas->tingkat }}
                {{ $tugas->guruMapel->kelas->jurusan }}
                {{ $tugas->guruMapel->kelas->kelas }}
            </p>
        </div>
        <a href="{{ route('siswa.tugas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header text-white" style="background:#256343;">
            <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Informasi Tugas</h5>
        </div>
        <div class="card-body">
            <h4 class="fw-bold mb-3">{{ $tugas->judul_tugas }}</h4>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Tenggat Waktu:</strong></p>
                    <p class="text-muted">
                        {{ $tugas->deadline->format('d M Y') }}
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Status Pengumpulan:</strong></p>

                    @if($pengumpulan)
                    @if($pengumpulan->nilai)
                    <span class="badge bg-success" style="font-size: 14px;">
                        Sudah Dinilai ({{ $pengumpulan->nilai }})
                    </span>
                    @else
                    <span class="badge bg-warning text-dark" style="font-size: 14px;">
                        Sudah Dikumpulkan — Menunggu Penilaian
                    </span>
                    @endif
                    @else
                    <span class="badge bg-secondary" style="font-size: 14px;">
                        Belum Dikumpulkan
                    </span>
                    @endif
                </div>
            </div>

            @if($tugas->file_path)
            <p class="mb-2"><strong>File Tugas:</strong></p>
            <a href="{{ asset('storage/'.$tugas->file_path) }}"
                target="_blank"
                class="btn btn-sm btn-outline-primary mb-3">
                <i class="bi bi-file-earmark"></i> Unduh File Tugas
            </a>
            @endif

            <hr>

            <p class="mb-2"><strong>Deskripsi Tugas:</strong></p>
            <div class="bg-light p-3 rounded">
                {!! nl2br(e($tugas->deskripsi)) !!}
            </div>
        </div>
    </div>

    @if($pengumpulan)

    <div class="card shadow-sm border-0">
        <div class="card-header text-white" style="background:#256343;">
            <h5 class="mb-0"><i class="bi bi-check-circle"></i> Tugas Anda</h5>
        </div>
        <div class="card-body">

            <p><strong>Tanggal Pengumpulan:</strong>
                {{ $pengumpulan->tanggal_pengumpulan->format('d M Y') }}
            </p>

            <p><strong>Status:</strong>
                <span class="badge"
                    style="background-color: {{ $pengumpulan->status === 'Terlambat' ? '#dc3545' : '#28a745' }};">
                    {{ $pengumpulan->status }}
                </span>
            </p>

            <hr>

            <p class="mb-2"><strong>Isi Tugas:</strong></p>
            <div class="bg-light p-3 rounded mb-3">
                {!! nl2br(e($pengumpulan->isi_tugas)) !!}
            </div>

            @if($pengumpulan->file_pengumpulan || $pengumpulan->file_path)
            <p class="mb-2"><strong>File yang Dikumpulkan:</strong></p>

            @php
            $file = $pengumpulan->file_pengumpulan ?? $pengumpulan->file_path;
            @endphp

            <a href="{{ asset('storage/'.$file) }}"
                target="_blank"
                class="btn btn-sm btn-outline-primary">
                <i class="bi bi-file-earmark"></i> Lihat File
            </a>
            @endif

            @if($pengumpulan->nilai)
            <hr>
            <div class="alert alert-success">
                <h5><i class="bi bi-star-fill"></i> Nilai: {{ $pengumpulan->nilai }}</h5>
                @if($pengumpulan->feedback)
                <p class="mb-0"><strong>Feedback Guru:</strong></p>
                <p class="mb-0">{{ $pengumpulan->feedback }}</p>
                @endif
            </div>
            @endif

            @if(!$pengumpulan->nilai)
            <hr>
            <form action="{{ route('siswa.tugas.batalkan', $tugas->id_tugas) }}"
                method="POST"
                onsubmit="return confirm('Yakin ingin membatalkan pengumpulan? Anda bisa mengumpulkan ulang.')">

                @csrf
                @method('DELETE')

                <button class="btn btn-danger">
                    <i class="bi bi-x-circle"></i> Batalkan Pengumpulan Tugas
                </button>
            </form>
            @endif

        </div>
    </div>

    @else
    <div class="card shadow-sm border-0">
        <div class="card-header text-white" style="background:#256343;">
            <h5 class="mb-0"><i class="bi bi-upload"></i> Kumpulkan Tugas</h5>
        </div>
        <div class="card-body">

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('siswa.tugas.kumpul', $tugas->id_tugas) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Jawaban Tugas</label>
                    <textarea name="jawaban"
                        class="form-control @error('jawaban') is-invalid @enderror"
                        rows="6"
                        placeholder="Tulis jawaban Anda di sini...">{{ old('jawaban') }}</textarea>
                    @error('jawaban')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">File Tugas (Opsional)</label>
                    <input type="file"
                        name="file_tugas"
                        class="form-control @error('file_tugas') is-invalid @enderror"
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,.rar,.ppt,.pptx,.xls,.xlsx">

                    @error('file_tugas')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <small class="text-muted">
                        Format: PDF, DOC, DOCX, JPG, PNG, ZIP, RAR, PPT, PPTX, XLS, XLSX
                        <br><span class="text-danger">*Tidak ada batas ukuran file*</span>
                    </small>
                </div>

                @if(now() > $tugas->deadline)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Peringatan:</strong> Tenggat waktu sudah lewat.
                    Tugas akan ditandai sebagai <strong>"Terlambat"</strong>.
                </div>
                @endif

                <button type="submit" class="btn text-white" style="background-color:#256343;">
                    <i class="bi bi-send"></i> Kumpulkan Tugas
                </button>

                <a href="{{ route('siswa.tugas.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>

        </div>
    </div>

    @endif
</div>
@endsection