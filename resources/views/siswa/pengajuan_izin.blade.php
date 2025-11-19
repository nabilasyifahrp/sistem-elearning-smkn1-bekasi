@extends('partials.layouts-siswa')

@section('content')
<style>
    .btn-green {
        background: #256343;
        color: white;
        padding: 10px 18px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        border: none;
        transition: 0.2s;
    }

    .btn-green:hover {
        background: #1e4d32;
        color: white;
    }

    .izin-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 15px;
    }

    .badge-pending {
        background: #ffc107;
        color: #333;
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 13px;
    }

    .badge-disetujui {
        background: #28a745;
        color: white;
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 13px;
    }

    .badge-ditolak {
        background: #dc3545;
        color: white;
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 13px;
    }

    .btn-warning {
    background: #ffc107;
    color: #333;
    border: none;
}

.btn-warning:hover {
    background: #e0a800;
    color: #333;
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
}

.btn-danger:hover {
    background: #c82333;
}
</style>

<div>
    <h2 class="fw-bold mb-4" style="color:#256343;">Pengajuan Izin</h2>

    <a href="{{ route('siswa.create_pengajuan_izin') }}" class="btn-green mb-3">+ Ajukan Izin Baru</a>

    @if($pengajuanList->count() === 0)
        <div class="alert alert-info">Belum ada pengajuan izin.</div>
    @else
    @foreach($pengajuanList as $pengajuan)
    <div class="izin-card">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h5 class="fw-bold mb-0" style="color:#256343;">
                {{ ucfirst($pengajuan->jenis_izin) }}
            </h5>
            <div class="d-flex gap-2 align-items-center">
                @if($pengajuan->status === 'pending')
                    <span class="badge-pending">Menunggu Persetujuan</span>
                @elseif($pengajuan->status === 'disetujui')
                    <span class="badge-disetujui">Disetujui</span>
                @else
                    <span class="badge-ditolak">Ditolak</span>
                @endif
            </div>
        </div>

        <p class="mb-1">
            <strong>Periode:</strong> 
            {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }} - 
            {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}
        </p>
        
        <p class="mb-1"><strong>Alasan:</strong> {{ $pengajuan->alasan }}</p>

        @if($pengajuan->bukti_file)
            <a href="{{ asset('storage/' . $pengajuan->bukti_file) }}" target="_blank" class="btn btn-sm mt-2" style="background:#256343; color:white;">
                <i class="bi bi-file-earmark"></i> Lihat Bukti
            </a>
        @endif

        @if($pengajuan->keterangan_wali)
            <div class="alert alert-{{ $pengajuan->status === 'disetujui' ? 'success' : 'danger' }} mt-3 mb-0">
                <strong>Keterangan Wali Kelas:</strong><br>
                {{ $pengajuan->keterangan_wali }}
            </div>
        @endif

        @if($pengajuan->status === 'pending')
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('siswa.edit_pengajuan_izin', $pengajuan->id_pengajuan) }}" 
                   class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                
                <form action="{{ route('siswa.cancel_pengajuan_izin', $pengajuan->id_pengajuan) }}" 
                      method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="bi bi-x-circle"></i> Batalkan
                    </button>
                </form>
            </div>
        @endif
    </div>
@endforeach

        <div class="mt-3">
            {{ $pengajuanList->links() }}
        </div>
    @endif
</div>

@if(session('success'))
    <div id="flash-message" style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:12px 16px; border-radius:6px; margin-top:20px; position:fixed; top:90px; right:20px; z-index:9999; transition: opacity 0.5s ease;">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            const msg = document.getElementById('flash-message');
            if(msg) {
                msg.style.opacity = "0";
                setTimeout(() => msg.remove(), 500);
            }
        }, 3000);
    </script>
@endif

@if(session('error'))
    <div id="flash-error" style="background:#f8d7da; border:1px solid #f5c6cb; color:#721c24; padding:12px 16px; border-radius:6px; margin-top:20px; position:fixed; top:90px; right:20px; z-index:9999; transition: opacity 0.5s ease;">
        {{ session('error') }}
    </div>
    <script>
        setTimeout(() => {
            const msg = document.getElementById('flash-error');
            if(msg) {
                msg.style.opacity = "0";
                setTimeout(() => msg.remove(), 500);
            }
        }, 3000);
    </script>
@endif
@endsection