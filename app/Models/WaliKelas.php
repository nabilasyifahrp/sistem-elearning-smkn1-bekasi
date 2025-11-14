<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaliKelas extends Model
{
    use HasFactory;

    protected $table = 'wali_kelas';
    protected $primaryKey = 'id_wali_kelas';
    
    protected $fillable = [
        'tahun_ajaran',
        'id_guru',
        'id_kelas'
    ];

    public function guru(){
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
