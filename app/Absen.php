<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    protected $fillable = ['dosen_id', 'tanggal', 'kehadiran_id'];

    public function dosen()
    {
        return $this->belongsTo('App\Dosen')->withDefault();
    }

    public function kehadiran()
    {
        return $this->belongsTo('App\Kehadiran')->withDefault();
    }

    protected $table = 'absensi_dosen';
}
