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

        .btn-green:disabled {
            background: #256343 !important;
            color: #ffffff !important;
            cursor: not-allowed !important;
            opacity: 0.7;
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

        <h2 class="fw-bold mb-4" style="color:#256343;">Edit Pengumuman</h2>

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

            <form id="formPengumuman" action="{{ route('admin.pengumuman.update', $data->id_pengumuman) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Judul Pengumuman</label>
                    <input type="text" name="judul"
                           value="{{ old('judul', $data->judul) }}"
                           class="form-control @error('judul') is-invalid @enderror"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Isi Pengumuman</label>
                    <textarea name="isi" rows="5"
                              class="form-control @error('isi') is-invalid @enderror"
                              required>{{ old('isi', $data->isi) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lampiran (Opsional)</label>
                    <input type="file" name="file_path" class="form-control @error('file_path') is-invalid @enderror">

                    @if ($data->file_path)
                        @php
                            $fileExt = strtolower(pathinfo($data->file_path, PATHINFO_EXTENSION));
                            $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                        @endphp

                        @if (in_array($fileExt, $imageExts))
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $data->file_path) }}" alt="Gambar Lama"
                                    style="max-width: 200px; max-height: 150px; border-radius: 6px; border: 1px solid #ccc;">
                            </div>
                        @else
                            <div class="mt-2" style="display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 24px;">📄</span>
                                <a href="{{ asset('storage/' . $data->file_path) }}" target="_blank">
                                    {{ basename($data->file_path) }}
                                </a>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Upload</label>
                    <input type="date" name="tanggal_upload" value="{{ old('tanggal_upload', $data->tanggal_upload) }}"
                        class="form-control @error('tanggal_upload') is-invalid @enderror" required>
                </div>

                <button id="btnUpdate" class="btn-green mt-3" disabled>
                    Perbarui Pengumuman
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const form = document.getElementById("formPengumuman");
            const btn = document.getElementById("btnUpdate");
            const initialData = new FormData(form);

            function checkForChanges() {
                const currentData = new FormData(form);
                let hasChanged = false;

                for (let [key, value] of currentData.entries()) {
                    if (key === "file_path" && value.name !== "") {
                        hasChanged = true;
                        break;
                    }

                    if (value !== initialData.get(key)) {
                        hasChanged = true;
                        break;
                    }
                }

                btn.disabled = !hasChanged;
            }

            form.addEventListener("input", checkForChanges);
            form.addEventListener("change", checkForChanges);
        });
    </script>

@endsection
