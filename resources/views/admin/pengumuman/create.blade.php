@extends('partials.layouts-admin')

@section('content')
    <style>
        .form-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }
        .form-label {
            font-weight: 600;
            color: #256343;
        }
        .btn-green {
            background: #256343;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
        }
        .btn-green:hover {
            background: #1d4c31;
        }
        .btn-back {
            background: #256343;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn-back:hover {
            background: #1d4c31;
        }
    </style>

    <div class="container">

        <h2 class="fw-bold mb-4" style="color:#256343;">Tambah Pengumuman</h2>

        <a href="{{ route('admin.pengumuman.index') }}" class="btn-back">Kembali</a>

        <div class="form-card mt-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Judul Pengumuman</label>
                    <input type="text" name="judul"
                           value="{{ old('judul') }}"
                           class="form-control @error('judul') is-invalid @enderror"
                           placeholder="Masukkan judul pengumuman" required>
                    @error('judul')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Isi Pengumuman</label>
                    <textarea name="isi" rows="5"
                              class="form-control @error('isi') is-invalid @enderror"
                              placeholder="Tulis isi pengumuman..." required>{{ old('isi') }}</textarea>
                    @error('isi')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Lampiran (Opsional)</label>
                    <input type="file" name="file_path"
                           class="form-control @error('file_path') is-invalid @enderror">
                    @error('file_path')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Upload</label>
                    <input type="date" name="tanggal_upload"
                           value="{{ old('tanggal_upload', date('Y-m-d')) }}"
                           class="form-control @error('tanggal_upload') is-invalid @enderror"
                           required>
                    @error('tanggal_upload')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn-green mt-3">Simpan Pengumuman</button>
            </form>
        </div>

    </div>
@endsection
