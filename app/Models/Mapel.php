<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapels';
    protected $primaryKey = 'id_mapel';

    protected $fillable = [
        'nama_mapel',
        'deskripsi'
    ];

    public function guruMapel(){
        return $this->hasMany(GuruMapel::class, 'id_mapel', 'id_mapel');
    }

    public function jadwalMapel()
    {
        return $this->hasManyThrough(
            JadwalMapel::class,
            GuruMapel::class,
            'id_mapel',
            'id_guru_mapel',
            'id_mapel',
            'id_guru_mapel'
        );
    }
}
