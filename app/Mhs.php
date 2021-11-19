<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mhs extends Model
{
    use SoftDeletes;

    protected $fillable = ['no_induk', 'nis', 'nama_mhs', 'kelas_id', 'jk', 'telp', 'tmp_lahir', 'tgl_lahir', 'foto'];

    public function kelas()
    {
        return $this->belongsTo('App\Kelas')->withDefault();
    }

    public function ulangan($id)
    {
        $dosen = Dosen::where('id_card', Auth::user()->id_card)->first();
        $nilai = Ulangan::where('mhs_id', $id)->where('dosen_id', $dosen->id)->first();
        return $nilai;
    }

    public function sikap($id)
    {
        $dosen = Dosen::where('id_card', Auth::user()->id_card)->first();
        $nilai = Sikap::where('mhs_id', $id)->where('dosen_id', $dosen->id)->first();
        return $nilai;
    }

    public function nilai($id)
    {
        $dosen = Dosen::where('id_card', Auth::user()->id_card)->first();
        $nilai = Rapot::where('mhs_id', $id)->where('dosen_id', $dosen->id)->first();
        return $nilai;
    }

    protected $table = 'mhs';
}
