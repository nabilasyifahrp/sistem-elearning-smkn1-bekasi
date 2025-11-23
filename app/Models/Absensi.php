<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nis',
        'id_jadwal',
        'id_pengajuan',
        'tanggal',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalMapel::class, 'id_jadwal', 'id_jadwal');
    }

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanIzin::class, 'id_pengajuan', 'id_pengajuan');
    }
}
