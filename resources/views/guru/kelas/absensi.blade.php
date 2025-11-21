@extends('partials.layouts-guru')

@section('content')
<h3>Absensi Kelas {{ $guruMapel->kelas->nama_kelas }}</h3>

<form action="{{ route('guru.kelas.absensi.store', $guruMapel->id_guru_mapel) }}" method="POST">
    @csrf

    <table class="table">
        <thead>
            <tr>
                <th>NIS</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswa as $s)
            <tr>
                <td>{{ $s->nis }}</td>
                <td>{{ $s->nama }}</td>
                <td>
                    <select name="absensi[{{ $s->nis }}]" class="form-select" required>
                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alfa">Alfa</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="keterangan[{{ $s->nis }}]" class="form-control">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button class="btn btn-primary">Simpan</button>
</form>
@endsection