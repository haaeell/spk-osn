<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kategori_mapel'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'id_penilai');
    }
}
