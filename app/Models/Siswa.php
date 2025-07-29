<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = ['nis', 'nama', 'kelas', 'jenis_kelamin'];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'id_siswa');
    }

    public function hasil()
    {
        return $this->hasMany(Hasil::class, 'id_siswa');
    }
}
