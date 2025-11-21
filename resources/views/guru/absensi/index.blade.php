    @extends('layouts.app')

    @section('content')
    <div class="container">
        <h3 class="mb-3">Form Absensi Siswa</h3>

        <div class="card">
            <div class="card-body">

                <p>Kelas: <strong>{{ $jadwal->kelas->nama_kelas }}</strong></p>
                <p>Mata Pelajaran: <strong>{{ $jadwal->guruMapel->mapel->nama_mapel }}</strong></p>
                <p>Guru: <strong>{{ $jadwal->guruMapel->guru->nama }}</strong></p>
                <p>Tanggal: <strong>{{ date('d M Y') }}</strong></p>

                <hr>

                <form action="{{ route('absensi.siswa.hadir', $jadwal->id_jadwal) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nis">NIS Siswa</label>
                        <input type="text" name="nis" class="form-control" required minlength="9" maxlength="9">
                    </div>

                    <input type="hidden" name="status" value="hadir">

                    <button class="btn btn-success w-100">Simpan Kehadiran</button>
                </form>

            </div>
        </div>
    </div>
    @endsection