@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color:#256343;">Detail Tugas</h3>

            <p class="text-muted mb-0">
                {{ $tugas->guruMapel->mapel->nama_mapel }} —
                {{ $tugas->guruMapel->kelas->tingkat }}
                {{ $tugas->guruMapel->kelas->jurusan }}
                {{ $tugas->guruMapel->kelas->kelas }}
            </p>
        </div>

        <a href="{{ route('guru.kelas.detail', $tugas->id_guru_mapel) }}"
            class="btn btn-success px-3"
            style="background:#256343; border:none;">
            Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-4">

        <div class="card-header py-3 text-white rounded-top-4"
            style="background:#256343;">
            <h5 class="mb-0 fw-semibold">Informasi Tugas</h5>
        </div>

        <div class="card-body pt-4">

            <div class="mb-3">
                <strong class="text-dark">Judul:</strong>
                <p class="mb-0 text-muted">{{ $tugas->judul_tugas }}</p>
            </div>

            <div class="mb-3">
                <strong class="text-dark">Deadline:</strong>
                <p class="mb-0 text-muted">
                    {{ $tugas->deadline->format('d M Y') }}
                </p>
            </div>

            <hr>

            <div class="mt-3">
                <strong class="text-dark">Deskripsi:</strong>
                <p class="text-muted mt-1">
                    {!! nl2br(e($tugas->deskripsi)) !!}
                </p>
            </div>

        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">

        <div class="card-header py-3 text-white rounded-top-4" style="background:#256343;">
            <h5 class="mb-0 fw-semibold">Daftar Pengumpulan</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead style="background:#f5f5f5;">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Tanggal Upload</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pengumpulan as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>

                            <td class="fw-semibold">
                                {{ $item->siswa->nama }}
                            </td>

                            <td>
                                {{ $item->created_at->format('d M Y H:i') }}
                            </td>

                            <td>
                                @if ($item->nilai)
                                <span class="badge bg-success px-3 py-2">
                                    {{ $item->nilai }}
                                </span>
                                @else
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    Belum dinilai
                                </span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('guru.tugas.pengumpulan.detail', [
                                    'id_tugas' => $tugas->id_tugas,
                                    'id_pengumpulan' => $item->id_pengumpulan,
                                ]) }}"
                                    class="btn btn-sm btn-outline-success px-3 rounded-3"
                                    style="border-color:#256343; color:#256343;">
                                    Buka
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada siswa yang mengumpulkan.
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