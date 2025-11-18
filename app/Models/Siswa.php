<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';
    protected $primaryKey = 'nis';
    public $incrementing = false;
    protected $keyType = 'bigint';

    protected $fillable = [
        'nis',
        'nama',
        'jenis_kelamin',
        'tahun_ajaran',
        'id_kelas',
        'user_id',
    ];
   
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    /**
     * Relasi ke Absensi
     * Satu siswa memiliki banyak absensi
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'nis', 'nis');
    }

    /**
     * Relasi ke PengajuanIzin
     * Satu siswa dapat membuat banyak pengajuan izin
     */
    public function pengajuanIzin()
    {
        return $this->hasMany(PengajuanIzin::class, 'nis', 'nis');
    }

    /**
     * Relasi ke PengumpulanTugas
     * Satu siswa dapat mengumpulkan banyak tugas
     */
    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'nis', 'nis');
    }
}