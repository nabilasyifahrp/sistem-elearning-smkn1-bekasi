<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;

class CrudMapelController extends Controller
{
    public function index()
    {
        $mapelList = Mapel::latest()->get();
        return view('admin.mapel.index', compact('mapelList'));
    }

}
