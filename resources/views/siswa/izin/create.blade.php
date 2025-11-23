@extends('partials.layouts-siswa')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0" style="color: #256343;">Ajukan Izin</h1>
            <p class="text-muted">Lengkapi form di bawah untuk mengajukan izin</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('siswa.izin.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header" style="background-color: #256343; color: white;">
            <h5 class="mb-0"><i class="bi bi-file-text"></i> Form Pengajuan Izin</h5>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi Kesalahan!</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('siswa.izin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            Tanggal Mulai <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                            name="tanggal_mulai"
                            class="form-control @error('tanggal_mulai') is-invalid @enderror"
                            value="{{ old('tanggal_mulai') }}"
                            required>
                        @error('tanggal_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Pilih tanggal mulai izin</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            Tanggal Selesai <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                            name="tanggal_selesai"
                            class="form-control @error('tanggal_selesai') is-invalid @enderror"
                            value="{{ old('tanggal_selesai') }}"
                            required>
                        @error('tanggal_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Pilih tanggal selesai izin</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Jenis Izin <span class="text-danger">*</span>
                    </label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check p-3 border rounded mb-2"
                                style="cursor: pointer; transition: 0.2s;"
                                onmouseover="this.style.backgroundColor='#f8f9fa'"
                                onmouseout="this.style.backgroundColor='white'">
                                <input class="form-check-input"
                                    type="radio"
                                    name="jenis_izin"
                                    id="izinSakit"
                                    value="sakit"
                                    {{ old('jenis_izin') == 'sakit' ? 'checked' : '' }}
                                    required>
                                <label class="form-check-label w-100" for="izinSakit" style="cursor: pointer;">
                                    <i class="bi bi-heart-pulse" style="color: #ff9800;"></i>
                                    <strong>Sakit</strong>
                                    <br>
                                    <small class="text-muted">Izin karena sakit/kondisi kesehatan</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check p-3 border rounded mb-2"
                                style="cursor: pointer; transition: 0.2s;"
                                onmouseover="this.style.backgroundColor='#f8f9fa'"
                                onmouseout="this.style.backgroundColor='white'">
                                <input class="form-check-input"
                                    type="radio"
                                    name="jenis_izin"
                                    id="izinKeperluan"
                                    value="izin"
                                    {{ old('jenis_izin') == 'izin' ? 'checked' : '' }}
                                    required>
                                <label class="form-check-label w-100" for="izinKeperluan" style="cursor: pointer;">
                                    <i class="bi bi-calendar-event" style="color: #17a2b8;"></i>
                                    <strong>Izin</strong>
                                    <br>
                                    <small class="text-muted">Izin karena keperluan lain</small>
                                </label>
                            </div>
                        </div>
                    </div>
                    @error('jenis_izin')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Alasan <span class="text-danger">*</span>
                    </label>
                    <textarea name="alasan"
                        class="form-control @error('alasan') is-invalid @enderror"
                        rows="5"
                        placeholder="Jelaskan alasan izin Anda dengan jelas dan lengkap..."
                        required>{{ old('alasan') }}</textarea>
                    @error('alasan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        Contoh: Sakit demam tinggi dan tidak bisa masuk sekolah,
                        atau ada keperluan keluarga yang mendesak
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Bukti Pendukung (Opsional)
                    </label>
                    <input type="file"
                        name="bukti_file"
                        class="form-control @error('bukti_file') is-invalid @enderror"
                        accept=".pdf,.jpg,.jpeg,.png">
                    @error('bukti_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        Upload surat keterangan dokter, foto, atau dokumen pendukung lainnya
                        (Format: PDF, JPG, PNG | Max: 2MB)
                    </small>
                </div>

                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle"></i> Perhatian:</h6>
                    <ul class="mb-0 small">
                        <li>Pastikan semua data yang diisi sudah benar</li>
                        <li>Pengajuan izin akan diproses oleh wali kelas</li>
                        <li>Untuk izin sakit lebih dari 3 hari, wajib melampirkan surat keterangan dokter</li>
                        <li>Status pengajuan dapat dilihat di halaman Pengajuan Izin</li>
                    </ul>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit"
                        class="btn text-white"
                        style="background-color: #256343;">
                        <i class="bi bi-send"></i> Ajukan Izin
                    </button>
                    <a href="{{ route('siswa.izin.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelector('input[name="tanggal_mulai"]').addEventListener('change', function() {
        const tanggalSelesai = document.querySelector('input[name="tanggal_selesai"]');
        if (!tanggalSelesai.value) {
            tanggalSelesai.value = this.value;
        }
    });
</script>
@endsection