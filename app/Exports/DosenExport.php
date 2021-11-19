<?php

namespace App\Exports;

use App\Dosen;
use Maatwebsite\Excel\Concerns\FromCollection;

class DosenExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $dosen = Dosen::join('mapel', 'mapel.id', '=', 'dosen.mapel_id')->select('dosen.nama_dosen', 'dosen.nip', 'dosen.jk', 'mapel.nama_mapel')->get();
        return $dosen;
    }
}
