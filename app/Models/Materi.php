<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';
    protected $primaryKey = 'id_materi';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'judul_materi',
        'deskripsi',
        'file_path',
        'tanggal_upload',
        'id_guru_mapel',
    ];

    protected $casts = [
        'tanggal_upload' => 'date',
    ];

    public function guruMapel()
    {
        return $this->belongsTo(GuruMapel::class, 'id_guru_mapel', 'id_guru_mapel');
    }
}
