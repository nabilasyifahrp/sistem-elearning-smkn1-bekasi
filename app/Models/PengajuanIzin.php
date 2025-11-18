<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanIzin extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_izin';
    protected $primaryKey = 'id_pengajuan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nis',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_izin',
        'alasan',
        'bukti_file',
        'status',
        'keterangan_wali',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Relasi ke Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    /**
     * Relasi ke Absensi
     * Satu pengajuan izin bisa terkait dengan banyak absensi
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_pengajuan', 'id_pengajuan');
    }
}