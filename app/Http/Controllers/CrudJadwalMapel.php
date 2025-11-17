<?php

namespace App\Http\Controllers;

use App\Models\JadwalMapel;
use Illuminate\Http\Request;

class CrudJadwalMapel extends Controller
{
    public function index()
    {
        $jadwalList = JadwalMapel::with([
            'kelas',
            'guruMapel.guru',
            'guruMapel.mapel'
        ])->get();

        return view('admin.jadwal_mapel.index', compact('jadwalList'));
    }
}
