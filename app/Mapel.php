<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mapel extends Model
{
    use SoftDeletes;

    protected $fillable = ['id', 'nama_mapel', 'paket_id', 'kelompok'];

    public function paket()
    {
        return $this->belongsTo('App\Paket')->withDefault();
    }

    public function sikap($id)
    {
        $mhs = Mhs::where('no_induk', Auth::user()->no_induk)->first();
        $nilai = Sikap::where('mhs_id', $mhs->id)->where('mapel_id', $id)->first();
        return $nilai;
    }

    public function cekSikap($id)
    {
        $data = json_decode($id, true);
        $sikap = Sikap::where('mhs_id', $data['mhs'])->where('mapel_id', $data['mapel'])->first();
        return $sikap;
    }

    protected $table = 'mapel';
}
