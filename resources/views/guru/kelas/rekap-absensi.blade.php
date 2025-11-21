@extends('layouts-guru')

@section('content')
<h3>Rekap Absensi</h3>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>NIS</th>
            <th>Nama</th>
            <th>Status</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekap as $a)
        <tr>
            <td>{{ $a->tanggal }}</td>
            <td>{{ $a->siswa->nis }}</td>
            <td>{{ $a->siswa->nama }}</td>
            <td>{{ ucfirst($a->status) }}</td>
            <td>{{ $a->keterangan ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection