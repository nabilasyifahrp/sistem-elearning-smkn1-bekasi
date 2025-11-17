<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMapel extends Model
{
    use HasFactory;

    protected $table = 'jadwal_mapels';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'hari',
        'jam_mulai',
        'jam_selesai',
        'tipe',
        'tahun_ajaran',
        'id_kelas',
        'id_guru_mapel',
    ];

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function guruMapel(){
        return $this->belongsTo(GuruMapel::class, 'id_guru_mapel', 'id_guru_mapel');
    }
}