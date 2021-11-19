<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $fillable = ['id', 'nama_team', 'paket_id', 'kelompok'];

    public function paket()
    {
        return $this->belongsTo('App\Paket')->withDefault();
    }

    public function sikap($id)
    {
        $mhs = Mhs::where('no_induk', Auth::user()->no_induk)->first();
        $nilai = Sikap::where('mhs_id', $mhs->id)->where('team_id', $id)->first();
        return $nilai;
    }

    public function cekSikap($id)
    {
        $data = json_decode($id, true);
        $sikap = Sikap::where('mhs_id', $data['mhs'])->where('team_id', $data['team'])->first();
        return $sikap;
    }

    protected $table = 'team';
}
