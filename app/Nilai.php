<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $fillable = ['dosen_id', 'kkm', 'deskripsi_a', 'deskripsi_b', 'deskripsi_c', 'deskripsi_d'];

    public function dosen()
    {
        return $this->belongsTo('App\Dosen')->withDefault();
    }

    protected $table = 'nilai';
}
