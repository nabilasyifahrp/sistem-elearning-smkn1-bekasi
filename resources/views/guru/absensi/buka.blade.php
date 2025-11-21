@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Absensi Dimulai</h3>

    <div class="card">
        <div class="card-body">
            <p>Guru Mapel: <strong>{{ $guruMapel->guru->nama }}</strong></p>
            <p>Mata Pelajaran: <strong>{{ $guruMapel->mapel->nama_mapel }}</strong></p>
            <p>Kelas: <strong>{{ $guruMapel->kelas->nama_kelas }}</strong></p>

            <hr>

            <p>Absensi sudah dibuka pada:</p>
            <h5><strong>{{ now()->format('d M Y - H:i') }}</strong></h5>

            <hr>

            <p>Link untuk siswa mengisi absensi:</p>
            <div class="alert alert-info">
                <strong>{{ route('absensi.siswa.form', $jadwal->id_jadwal) }}</strong>
            </div>

            <p>Berikan link ini kepada siswa atau tampilkan sebagai QR Code.</p>

            <form action="{{ route('guru.absensi.tutup', $jadwal->id_jadwal) }}" method="POST">
                @csrf
                <button class="btn btn-danger">Tutup Absensi</button>
            </form>
        </div>
    </div>
</div>
@endsection