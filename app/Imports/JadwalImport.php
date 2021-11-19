<?php

namespace App\Imports;

use App\Jadwal;
use App\Hari;
use App\Kelas;
use App\Team;
use App\Dosen;
use App\Ruang;
use Maatwebsite\Excel\Concerns\ToModel;

class JadwalImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $hari = Hari::where('nama_hari', $row[0])->first();
        $kelas = Kelas::where('nama_kelas', $row[1])->first();
        $team = Team::where('nama_team', $row[2])->first();
        $dosen = Dosen::where('nama_dosen', $row[3])->first();
        $ruang = Ruang::where('nama_ruang', $row[6])->first();

        return new Jadwal([
            'hari_id' => $hari->id,
            'kelas_id' => $kelas->id,
            'team_id' => $team->id,
            'dosen_id' => $dosen->id,
            'jam_mulai' => $row[4],
            'jam_selesai' => $row[5],
            'ruang_id' => $ruang->id,
        ]);
    }
}
