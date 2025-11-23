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
        opacity: 0.6;
        cursor: not-allowed;
        transition: 0.3s;
    }

    .btn-green.active {
        opacity: 1;
        cursor: pointer;
    }

    .btn-back {
        background: #256343;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        transition: 0.2s;
        display: inline-block;
        margin-bottom: 20px;
    }

    .btn-back:hover {
        background: #1d4c31;
    }

    .error-wrap {
        position: relative;
        min-height: 18px;
    }

    .error-absolute {
        position: absolute;
        top: 0;
        left: 0;
        font-size: 12px;
        color: #dc3545;
    }
</style>

<div class="container">
    <h2 class="fw-bold mb-4" style="color:#256343;">Edit Jadwal Mapel</h2>

    <a href="{{ route('admin.jadwalmapel.index') }}" class="btn-back">Kembali</a>

    <div class="form-card mt-2">
        <form id="editJadwalForm" action="{{ route('admin.jadwalmapel.update', $jadwal->id_jadwal) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Hari</label>
                    <select name="hari" class="form-control @error('hari') is-invalid @enderror" required>
                        <option value="">-- Pilih Hari --</option>
                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                        <option value="{{ $h }}" {{ $jadwal->hari == $h ? 'selected' : '' }}>
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
                        min="06:30" max="17:00" value="{{ $jadwal->jam_mulai }}" required>
                    @error('jam_mulai')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai"
                        class="form-control @error('jam_selesai') is-invalid @enderror" min="06:31" max="16:59"
                        value="{{ $jadwal->jam_selesai }}" required>
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
                        <option value="{{ $t }}" {{ $jadwal->tipe == $t ? 'selected' : '' }}>
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
                        value="{{ $jadwal->tahun_ajaran }}" required>
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
                            {{ $jadwal->id_kelas == $kelas->id_kelas ? 'selected' : '' }}>
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
                            {{ $jadwal->id_guru_mapel ? ($jadwal->guruMapel->id_guru == $guru->id_guru ? 'selected' : '') : '' }}>
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
                            {{ $jadwal->id_guru_mapel ? ($jadwal->guruMapel->id_mapel == $mapel->id_mapel ? 'selected' : '') : '' }}>
                            {{ $mapel->nama_mapel }}
                        </option>
                        @endforeach
                    </select>
                    <div class="error-wrap mb-1">
                        @if ($errors->has('error'))
                        <small class="error-absolute">
                            {{ $errors->first('error') }}
                        </small>
                        @endif
                    </div>
                </div>
            </div>

            <button type="submit" id="btnUpdate" class="btn-green mt-3">Perbarui Jadwal</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("editJadwalForm");
        const btn = document.getElementById("btnUpdate");
        const initialValues = {};
        Array.from(form.elements).forEach(el => {
            if (!el.name) return;
            initialValues[el.name] = el.value ?? '';
        });

        function checkForChanges() {
            let changed = false;
            Array.from(form.elements).forEach(el => {
                if (!el.name) return;
                let current = el.value ?? '';
                if (current !== initialValues[el.name]) changed = true;
            });

            if (changed) {
                btn.classList.add('active');
                btn.disabled = false;
            } else {
                btn.classList.remove('active');
                btn.disabled = true;
            }
        }

        form.addEventListener('input', checkForChanges);
        form.addEventListener('change', checkForChanges);
        checkForChanges();
    });
</script>
@endsection