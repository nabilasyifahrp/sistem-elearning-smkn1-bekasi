<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';
    protected $primaryKey = 'id_guru';

    protected $fillable = [
        'nama',
        'nip',
        'jenis_kelamin',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function waliKelas(){
        return $this->hasOne(WaliKelas::class, 'id_guru', 'id_guru');
    }

    public function guruMapel(){
        return $this->hasMany(GuruMapel::class, 'id_guru', 'id_guru');
    }

    public function jadwalMapel()
    {
        return $this->hasManyThrough(
            JadwalMapel::class,
            GuruMapel::class,
            'id_guru',            
            'id_guru_mapel',
            'id_guru',
            'id_guru_mapel'
        );
    }
}