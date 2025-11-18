@extends('partials.layouts-admin')

@section('content')
    <style>
        .form-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #256343;
        }

        .btn-green {
            background: #256343;
            color: #ffffff;
            border: none;
        }

        .btn-green:hover {
            background: #1e4d32;
            color: #ffffff;
        }

        .btn-back {
            background: #256343;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-back:hover {
            background: #1e4d32;
            color: white;
        }

        @media(max-width:768px) {
            .form-card {
                padding: 18px;
            }
        }
    </style>

    <div class="container">

        <h2 class="fw-bold mb-4" style="color:#256343;">
            Pilih Wali Kelas - {{ $kelas->tingkat }} {{ $kelas->jurusan }} {{ $kelas->kelas }}
        </h2>

        <a href="{{ route('admin.kelas.index') }}" class="btn-back">Kembali</a>

        <div class="form-card mt-6">
            <form action="{{ route('admin.kelas.storeWali', $kelas->id_kelas) }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Pilih Guru Wali Kelas</label>
                        <select name="id_guru" class="form-control @error('id_guru') is-invalid @enderror" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($guruList as $guru)
                                <option value="{{ $guru->id_guru }}"
                                    {{ $kelas->waliKelas && $kelas->waliKelas->id_guru == $guru->id_guru ? 'selected' : '' }}>
                                    {{ $guru->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_guru')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran"
                            class="form-control @error('tahun_ajaran') is-invalid @enderror"
                            value="{{ $kelas->waliKelas ? $kelas->waliKelas->tahun_ajaran : '' }}"
                            placeholder="Contoh: 2024/2025" required>
                        @error('tahun_ajaran')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-green px-4 py-2 mt-3">Simpan Wali Kelas</button>
            </form>
        </div>
    </div>
@endsection
