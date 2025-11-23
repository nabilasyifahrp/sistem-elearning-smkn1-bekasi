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

    .counter {
        font-size: 13px;
        color: #555;
        text-align: right;
    }

    @media(max-width:768px) {
        .form-card {
            padding: 18px;
        }
    }
</style>

<div class="container">

    <h2 class="fw-bold mb-4" style="color:#256343;">Tambah Mapel</h2>

    <a href="{{ route('admin.mapel.index') }}" class="btn-back">
        Kembali
    </a>

    <div class="form-card mt-4">
        <form action="{{ route('admin.mapel.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Mapel</label>
                <input type="text" name="nama_mapel" class="form-control @error('nama_mapel') is-invalid @enderror"
                    placeholder="Contoh: Matematika, Fisika, B. Indonesia" required>

                @error('nama_mapel')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Mapel <small>(maks 150 karakter)</small></label>

                <textarea name="deskripsi" maxlength="150" id="descInput" class="form-control @error('deskripsi') is-invalid @enderror"
                    placeholder="Contoh: Mata pelajaran yang mempelajari dasar-dasar logika dan numerik." rows="3"></textarea>

                <div class="counter" id="descCounter">0/150 karakter</div>

                @error('deskripsi')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button class="btn btn-green px-4 py-2 mt-3">Simpan Mapel</button>

        </form>
    </div>

</div>

<script>
    const descInput = document.getElementById('descInput');
    const descCounter = document.getElementById('descCounter');

    descInput.addEventListener('input', () => {
        if (descInput.value.length > 150) {
            descInput.value = descInput.value.substring(0, 150);
        }
        descCounter.textContent = `${descInput.value.length}/150 karakter`;
    });
</script>
@endsection