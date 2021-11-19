<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    use SoftDeletes;

    protected $fillable = ['paket_id', 'nama_kelas', 'dosen_id'];

    public function dosen()
    {
        return $this->belongsTo('App\Dosen')->withDefault();
    }

    public function paket()
    {
        return $this->belongsTo('App\Paket')->withDefault();
    }

    protected $table = 'kelas';
}
