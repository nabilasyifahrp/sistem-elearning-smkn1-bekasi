@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <h3 class="fw-bold mb-4" style="color:#256343;">
        Pengajuan Izin Siswa
    </h3>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header py-3" style="background:#256343; color:white;">
            <h6 class="mb-0 fw-semibold">Daftar Pengajuan Izin</h6>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 text-center align-middle table-hover">

                    <thead style="background:#f8f9fa;">
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
                            <th style="width:150px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pengajuanIzin as $izin)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $izin->siswa->nama }}</td>
                            <td>{{ $izin->nis }}</td>
                            <td>{{ $izin->tanggal_mulai->format('d-m-Y') }}</td>
                            <td>{{ $izin->tanggal_selesai->format('d-m-Y') }}</td>
                            <td><span class="badge bg-info text-dark">{{ $izin->jenis_izin }}</span></td>
                            <td style="max-width:180px;">{{ $izin->alasan }}</td>

                            <td>
                                @if($izin->bukti_file)
                                <a href="{{ asset('storage/'.$izin->bukti_file) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-secondary">
                                    Lihat
                                </a>
                                @else
                                -
                                @endif
                            </td>

                            <td>
                                @if($izin->status == 'pending')
                                <span class="badge bg-warning text-dark px-3">Menunggu</span>
                                @elseif($izin->status == 'disetujui')
                                <span class="badge bg-success px-3">Disetujui</span>
                                @else
                                <span class="badge bg-danger px-3">Ditolak</span>
                                @endif
                            </td>

                            <td>
                                @if($izin->status == 'pending')
                                <div class="d-flex justify-content-center gap-2">

                                    <form action="{{ route('guru.izin.setujui', $izin->id_pengajuan) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-success btn-sm px-3"
                                            style="background:#256343; border:none;">
                                            Setujui
                                        </button>
                                    </form>

                                    <form action="{{ route('guru.izin.tolak', $izin->id_pengajuan) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm px-3">
                                            Tolak
                                        </button>
                                    </form>

                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                Belum ada pengajuan izin.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>
@endsection