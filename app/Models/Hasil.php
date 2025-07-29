<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    protected $fillable = ['id_siswa', 'mapel', 'nilai_akhir', 'hasil'];
    protected $table = 'hasil';

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}
