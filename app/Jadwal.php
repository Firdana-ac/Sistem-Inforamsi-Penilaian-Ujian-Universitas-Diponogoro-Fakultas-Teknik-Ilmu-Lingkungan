<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
  use SoftDeletes;

  protected $fillable = ['hari_id', 'kelas_id', 'mapel_id', 'dosen_id', 'jam_mulai', 'jam_selesai', 'ruang_id'];

  public function hari()
  {
    return $this->belongsTo('App\Hari')->withDefault();
  }

  public function kelas()
  {
    return $this->belongsTo('App\Kelas')->withDefault();
  }

  public function mapel()
  {
    return $this->belongsTo('App\Mapel')->withDefault();
  }

  public function dosen()
  {
    return $this->belongsTo('App\Dosen')->withDefault();
  }

  public function ruang()
  {
    return $this->belongsTo('App\Ruang')->withDefault();
  }

  public function rapot($id)
  {
    $kelas = Kelas::where('id', $id)->first();
    return $kelas;
  }

  public function pengajar($id)
  {
    $dosen = Dosen::where('id', $id)->first();
    return $dosen;
  }

  public function ulangan($id)
  {
    $mhs = Mhs::where('no_induk', Auth::user()->no_induk)->first();
    $nilai = Ulangan::where('mhs_id', $mhs->id)->where('mapel_id', $id)->first();
    return $nilai;
  }

  public function nilai($id)
  {
    $mhs = Mhs::where('no_induk', Auth::user()->no_induk)->first();
    $nilai = Rapot::where('mhs_id', $mhs->id)->where('mapel_id', $id)->first();
    return $nilai;
  }

  public function kkm($id)
  {
    $kkm = Nilai::where('dosen_id', $id)->first();
    return $kkm['kkm'];
  }

  public function absen($id)
  {
    $absen = Absen::where('tanggal', date('Y-m-d'))->where('dosen_id', $id)->first();
    $ket = Kehadiran::where('id', $absen['kehadiran_id'])->first();
    return $ket['color'];
  }

  public function cekUlangan($id)
  {
    $data = json_decode($id, true);
    $ulangan = Ulangan::where('mhs_id', $data['mhs'])->where('mapel_id', $data['mapel'])->first();
    return $ulangan;
  }

  public function cekRapot($id)
  {
    $data = json_decode($id, true);
    $rapot = Rapot::where('mhs_id', $data['mhs'])->where('mapel_id', $data['mapel'])->first();
    return $rapot;
  }

  protected $table = 'jadwal';
}
