<?php

namespace App\Imports;

use App\Mhs;
use App\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;

class MhsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $kelas = Kelas::where('nama_kelas', $row[3])->first();
        if ($row[2] == 'L') {
            $foto = 'uploads/mhs/52471919042020_male.jpg';
        } else {
            $foto = 'uploads/mhs/50271431012020_female.jpg';
        }

        return new Mhs([
            'nama_mhs' => $row[0],
            'no_induk' => $row[1],
            'jk' => $row[2],
            'foto' => $foto,
            'kelas_id' => $kelas->id,
        ]);
    }
}
