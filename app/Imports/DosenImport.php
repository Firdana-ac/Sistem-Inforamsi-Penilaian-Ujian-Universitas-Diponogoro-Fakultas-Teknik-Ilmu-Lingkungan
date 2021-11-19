<?php

namespace App\Imports;

use App\Dosen;
use App\Mapel;
use Maatwebsite\Excel\Concerns\ToModel;

class DosenImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $max = Dosen::max('id_card');
        $kode = $max + 1;
        if (strlen($kode) == 1) {
            $id_card = "0000" . $kode;
        } else if (strlen($kode) == 2) {
            $id_card = "000" . $kode;
        } else if (strlen($kode) == 3) {
            $id_card = "00" . $kode;
        } else if (strlen($kode) == 4) {
            $id_card = "0" . $kode;
        } else {
            $id_card = $kode;
        }
        $mapel = Mapel::where('nama_mapel', $row[3])->first();
        if ($row[2] == 'L') {
            $foto = 'uploads/dosen/35251431012020_male.jpg';
        } else {
            $foto = 'uploads/dosen/23171022042020_female.jpg';
        }

        return new Dosen([
            'id_card' => $id_card,
            'nama_dosen' => $row[0],
            'nip' => $row[1],
            'jk' => $row[2],
            'foto' => $foto,
            'mapel_id' => $mapel->id,
        ]);
    }
}
