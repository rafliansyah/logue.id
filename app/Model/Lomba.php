<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Lomba extends Model
{

    protected $table = 'create_lomba';

    protected $fillable = [
        'poster', 'nama_lomba', 'jenis_lomba', 'date', 'time', 'place', 'sifat_lomba', 'biaya_pendaftaran', 'deskripsi_lomba', 'file_legal',
    ];

}
