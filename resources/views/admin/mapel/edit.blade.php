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

        .btn-green:disabled {
            background-color: #256343 !important;
            color: #ffffff !important;
            cursor: not-allowed !important;
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

        <h2 class="fw-bold mb-4" style="color:#256343;">Edit Mapel</h2>

        <a href="{{ route('admin.mapel.index') }}" class="btn-back">
            Kembali
        </a>

        <div class="form-card mt-4">
            <form action="{{ route('admin.mapel.update', $mapel->id_mapel) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Mapel</label>
                    <input type="text" name="nama_mapel" class="form-control @error('nama_mapel') is-invalid @enderror"
                        value="{{ old('nama_mapel', $mapel->nama_mapel) }}"
                        placeholder="Contoh: Matematika, Fisika, B. Indonesia" required>

                    @error('nama_mapel')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi Mapel <small>(maks 150 karakter)</small></label>

                    <textarea name="deskripsi" maxlength="150" id="descInput" class="form-control @error('deskripsi') is-invalid @enderror"
                        rows="3" placeholder="Contoh: Mata pelajaran yang mempelajari dasar-dasar logika dan numerik.">{{ old('deskripsi', $mapel->deskripsi) }}</textarea>

                    <div class="counter" id="descCounter">0/150 karakter</div>

                    @error('deskripsi')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button id="btnUpdate" class="btn btn-green px-4 py-2 mt-3" disabled>Update Mapel</button>

            </form>
        </div>

    </div>
    <script>
        const descInput = document.getElementById('descInput');
        const descCounter = document.getElementById('descCounter');
        descCounter.textContent = `${descInput.value.length}/150 karakter`;

        descInput.addEventListener('input', () => {
            if (descInput.value.length > 150) {
                descInput.value = descInput.value.substring(0, 150);
            }
            descCounter.textContent = `${descInput.value.length}/150 karakter`;
        });

        document.addEventListener("DOMContentLoaded", function() {

            const form = document.querySelector("form");
            const btn = document.getElementById("btnUpdate");
            const initialData = new FormData(form);

            function checkForChanges() {
                const currentData = new FormData(form);
                let hasChanged = false;

                for (let [key, value] of currentData.entries()) {
                    if (value !== initialData.get(key)) {
                        hasChanged = true;
                        break;
                    }
                }

                btn.disabled = !hasChanged;
            }

            form.addEventListener("input", checkForChanges);
        });
    </script>
@endsection
