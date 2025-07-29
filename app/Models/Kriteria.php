<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $fillable = ['nama_kriteria', 'mapel', 'bobot'];
    protected $table = 'kriteria';

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'id_kriteria');
    }
}
