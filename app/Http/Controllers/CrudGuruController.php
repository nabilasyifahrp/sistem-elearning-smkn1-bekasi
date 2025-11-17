<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class CrudGuruController extends Controller
{
    public function index()
    {
        $guruList = Guru::with('user')->get();
        return view('admin.guru.index', compact('guruList'));
    }

    public function create(){
        return view('admin.guru.create');
    }
}
