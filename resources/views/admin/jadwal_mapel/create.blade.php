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
            color: #fff;
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
            background: #1d4c31;
        }

        .error-wrap {
            min-height: 18px;
        }

        .error-absolute {
            position: absolute;
            top: 0;
            left: 0;
            font-size: 12px;
        }
    </style>

    <div class="container">
        <h2 class="fw-bold mb-4" style="color:#256343;">Tambah Jadwal Mapel</h2>
        <a href="{{ route('admin.jadwalmapel.index') }}" class="btn-back">Kembali</a>

        <div class="form-card mt-4">
            <form action="{{ route('admin.jadwalmapel.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Hari</label>
                        <select name="hari" class="form-control @error('hari') is-invalid @enderror" required>
                            <option value="">-- Pilih Hari --</option>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                                <option value="{{ $h }}" {{ old('hari') == $h ? 'selected' : '' }}>
                                    {{ $h }}
                                </option>
                            @endforeach
                        </select>
                        @error('hari')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror"
                            min="06:30" max="16:59" value="{{ old('jam_mulai') }}" required>
                        @error('jam_mulai')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai"
                            class="form-control @error('jam_selesai') is-invalid @enderror" min="06:31" max="17:00"
                            value="{{ old('jam_selesai') }}" required>
                        @error('jam_selesai')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Tipe</label>
                        <select name="tipe" class="form-control @error('tipe') is-invalid @enderror" required>
                            <option value="">-- Pilih Tipe --</option>
                            @foreach (['Teori', 'Tefa'] as $t)
                                <option value="{{ $t }}" {{ old('tipe') == $t ? 'selected' : '' }}>
                                    {{ $t }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipe')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran"
                            class="form-control @error('tahun_ajaran') is-invalid @enderror" placeholder="Contoh: 2024/2025"
                            value="{{ old('tahun_ajaran') }}" required>
                        @error('tahun_ajaran')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kelas</label>
                        <select name="id_kelas" class="form-control @error('id_kelas') is-invalid @enderror" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id_kelas }}"
                                    {{ old('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                    {{ $kelas->tingkat }} {{ $kelas->jurusan }} {{ $kelas->kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kelas')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Guru</label>
                        <select name="id_guru" class="form-control @error('id_guru') is-invalid @enderror" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($guruList as $guru)
                                <option value="{{ $guru->id_guru }}"
                                    {{ old('id_guru') == $guru->id_guru ? 'selected' : '' }}>
                                    {{ $guru->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_guru')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Mapel</label>
                        <select name="id_mapel" class="form-control @error('id_mapel') is-invalid @enderror" required>
                            <option value="">-- Pilih Mapel --</option>
                            @foreach ($mapelList as $mapel)
                                <option value="{{ $mapel->id_mapel }}"
                                    {{ old('id_mapel') == $mapel->id_mapel ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                        <div class="error-wrap position-relative mb-1">
                            @error('id_mapel')
                                <small class="text-danger error-absolute">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>
                    </div>
                </div>

                <button class="btn-green mt-3">Simpan Jadwal</button>
            </form>
        </div>
    </div>
@endsection