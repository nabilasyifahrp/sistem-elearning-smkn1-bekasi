@extends('partials.layouts-admin')

@section('content')
<style>
    .detail-box {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }

    .detail-title {
        font-weight: 700;
        font-size: 22px;
        color: #256343;
        margin-bottom: 15px;
    }

    .detail-list span {
        font-weight: 600;
        color: #256343;
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
</style>

<div>
    <h2 class="fw-bold mb-4" style="color:#256343;">Detail Jadwal Mapel</h2>

    <a href="{{ route('admin.jadwalmapel.index') }}" class="btn-back">Kembali</a>

    <div class="detail-box mt-4">
        <h3 class="detail-title mb-4">Informasi Jadwal</h3>
        <hr class="border-dark">

        <div class="detail-list">
            <p><span>Hari: </span> {{ $jadwal->hari }}</p>
            <p><span>Jam Mulai: </span> {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</p>
            <p><span>Jam Selesai: </span> {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</p>
            <p><span>Tipe: </span> {{ $jadwal->tipe }}</p>
            <p><span>Tahun Ajaran: </span> {{ $jadwal->tahun_ajaran }}</p>
            <p><span>Kelas: </span>
                {{ $jadwal->kelas->tingkat ?? '' }}
                {{ $jadwal->kelas->jurusan ?? '' }}
                {{ $jadwal->kelas->kelas ?? '' }}
            </p>
            <p><span>Guru: </span> {{ $jadwal->guruMapel->guru->nama ?? '-' }}</p>
            <p><span>Mata Pelajaran: </span> {{ $jadwal->guruMapel->mapel->nama_mapel ?? '-' }}</p>
        </div>
    </div>
</div>
@endsection