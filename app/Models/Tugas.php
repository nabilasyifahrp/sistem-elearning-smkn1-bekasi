<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'judul_tugas',
        'deskripsi',
        'deadline',
        'file_path',
        'id_guru_mapel',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function guruMapel()
    {
        return $this->belongsTo(GuruMapel::class, 'id_guru_mapel', 'id_guru_mapel');
    }

    
}
