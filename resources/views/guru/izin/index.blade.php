@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">
    <h3 class="mb-4" style="color: #256343;">Pengajuan Izin Siswa</h3>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-success">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Jenis Izin</th>
                        <th>Alasan</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuanIzin as $izin)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $izin->siswa->nama }}</td>
                        <td>{{ $izin->nis }}</td>
                        <td>{{ $izin->tanggal_mulai->format('d-m-Y') }}</td>
                        <td>{{ $izin->tanggal_selesai->format('d-m-Y') }}</td>
                        <td>{{ $izin->jenis_izin }}</td>
                        <td>{{ $izin->alasan }}</td>
                        <td>
                            @if($izin->bukti_file)
                            <a href="{{ asset('storage/' . $izin->bukti_file) }}" target="_blank">Lihat</a>
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if($izin->status == 'pending')
                            <span class="badge bg-warning text-dark">Menunggu</span>
                            @elseif($izin->status == 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                            @elseif($izin->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if($izin->status == 'pending')
                            <form action="{{ route('guru.izin.setujui', $izin->id_pengajuan) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                            </form>
                            <form action="{{ route('guru.izin.tolak', $izin->id_pengajuan) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                            </form>
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">Belum ada pengajuan izin.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection