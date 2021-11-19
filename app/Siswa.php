<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    protected $fillable = ['no_induk', 'nis', 'nama_siswa', 'kelas_id', 'jk', 'telp', 'tmp_lahir', 'tgl_lahir', 'foto'];

    public function kelas()
    {
        return $this->belongsTo('App\Kelas')->withDefault();
    }

    public function ulangan($id)
    {
        $dosen = Dosen::where('id_card', Auth::user()->id_card)->first();
        $nilai = Ulangan::where('siswa_id', $id)->where('dosen_id', $dosen->id)->first();
        return $nilai;
    }

    public function sikap($id)
    {
        $dosen = Dosen::where('id_card', Auth::user()->id_card)->first();
        $nilai = Sikap::where('siswa_id', $id)->where('dosen_id', $dosen->id)->first();
        return $nilai;
    }

    public function nilai($id)
    {
        $dosen = Dosen::where('id_card', Auth::user()->id_card)->first();
        $nilai = Rapot::where('siswa_id', $id)->where('dosen_id', $dosen->id)->first();
        return $nilai;
    }

    protected $table = 'siswa';
}
