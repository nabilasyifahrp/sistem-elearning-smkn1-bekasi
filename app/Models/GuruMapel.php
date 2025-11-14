<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruMapel extends Model
{
    use HasFactory;

    protected $table = 'guru_mapels';
    protected $primaryKey = 'id_guru_mapel';

    protected $fillable = [
        'tahun_ajaran',
        'id_guru',
        'id_mapel',
        'id_kelas'
    ];

    public function guru(){
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function mapel(){
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id_mapel');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function jadwalMapel(){
        return $this->hasMany(JadwalMapel::class, 'id_guru_mapel', 'id_guru_mapel');
    }
}
