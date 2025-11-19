@extends('partials.layouts-siswa')

@section('content')
<style>
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 30px;
        max-width: 800px;
        margin: 0 auto;
    }

    .form-card h4 {
        color: #256343;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #256343;
        margin-bottom: 8px;
    }

    .btn-submit {
        background: #256343;
        color: white;
        padding: 12px 30px;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        transition: 0.2s;
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
        transition: 0.2s;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
    }

    .info-box {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .info-box p {
        margin: 0;
        color: #856404;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #256343;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 67, 0.25);
    }

    .counter {
        font-size: 13px;
        color: #666;
        text-align: right;
        margin-top: 5px;
    }
</style>

<div>
    <a href="{{ route('siswa.pengajuan_izin') }}" class="btn-back mb-3">← Kembali</a>

    <div class="form-card">
        <h4>Edit Pengajuan Izin</h4>

        <div class="info-box">
            <p><i class="bi bi-exclamation-triangle"></i> Anda hanya dapat mengedit pengajuan yang masih berstatus "Menunggu Persetujuan".</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan!</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('siswa.update_pengajuan_izin', $pengajuan->id_pengajuan) }}" method="POST" enctype="multipart/form-data" id="formPengajuan">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" 
                           name="tanggal_mulai" 
                           id="tanggalMulai"
                           class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                           value="{{ old('tanggal_mulai', $pengajuan->tanggal_mulai) }}"
                           min="{{ date('Y-m-d') }}"
                           required>
                    @error('tanggal_mulai')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" 
                           name="tanggal_selesai" 
                           id="tanggalSelesai"
                           class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                           value="{{ old('tanggal_selesai', $pengajuan->tanggal_selesai) }}"
                           min="{{ date('Y-m-d') }}"
                           required>
                    @error('tanggal_selesai')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Izin <span class="text-danger">*</span></label>
                <select name="jenis_izin" 
                        class="form-control @error('jenis_izin') is-invalid @enderror" 
                        required>
                    <option value="">-- Pilih Jenis Izin --</option>
                    <option value="sakit" {{ old('jenis_izin', $pengajuan->jenis_izin) == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="izin" {{ old('jenis_izin', $pengajuan->jenis_izin) == 'izin' ? 'selected' : '' }}>Izin</option>
                </select>
                @error('jenis_izin')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Alasan <span class="text-danger">*</span></label>
                <textarea name="alasan" 
                          id="alasanInput"
                          rows="5" 
                          maxlength="500"
                          class="form-control @error('alasan') is-invalid @enderror" 
                          placeholder="Jelaskan alasan Anda mengajukan izin (maksimal 500 karakter)..." 
                          required>{{ old('alasan', $pengajuan->alasan) }}</textarea>
                <div class="counter" id="alasanCounter">0/500 karakter</div>
                @error('alasan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Bukti/Lampiran (Opsional)</label>
                
                @if($pengajuan->bukti_file)
                    <div class="mb-2">
                        <small class="text-muted">File saat ini:</small><br>
                        <a href="{{ asset('storage/' . $pengajuan->bukti_file) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="bi bi-file-earmark"></i> Lihat File Lama
                        </a>
                    </div>
                @endif
                
                <input type="file" 
                       name="bukti_file" 
                       class="form-control @error('bukti_file') is-invalid @enderror"
                       accept=".pdf,.jpg,.jpeg,.png"
                       id="fileInput">
                <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Maksimal 5MB). Biarkan kosong jika tidak ingin mengubah file.</small>
                @error('bukti_file')
                    <small class="text-danger d-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('siswa.pengajuan_izin') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const alasanInput = document.getElementById('alasanInput');
    const alasanCounter = document.getElementById('alasanCounter');
    
    function updateCounter() {
        const length = alasanInput.value.length;
        alasanCounter.textContent = `${length}/500 karakter`;
        
        if (length > 450) {
            alasanCounter.style.color = '#dc3545';
        } else if (length > 400) {
            alasanCounter.style.color = '#ffc107';
        } else {
            alasanCounter.style.color = '#666';
        }
    }
    
    alasanInput.addEventListener('input', updateCounter);
    updateCounter();

    const tanggalMulai = document.getElementById('tanggalMulai');
    const tanggalSelesai = document.getElementById('tanggalSelesai');
    
    tanggalMulai.addEventListener('change', function() {
        tanggalSelesai.min = this.value;
        if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
            tanggalSelesai.value = this.value;
        }
    });

    const fileInput = document.getElementById('fileInput');
    fileInput.addEventListener('change', function() {
        if (this.files[0]) {
            const fileSize = this.files[0].size / 1024 / 1024; 
            if (fileSize > 5) {
                alert('Ukuran file terlalu besar! Maksimal 5MB.');
                this.value = '';
            }
        }
    });
});
</script>
@endsection