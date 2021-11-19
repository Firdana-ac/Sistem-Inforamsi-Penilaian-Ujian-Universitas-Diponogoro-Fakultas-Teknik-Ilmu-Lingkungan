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
        $dosen = Dosen::join('team', 'team.id', '=', 'dosen.team_id')->select('dosen.nama_dosen', 'dosen.nip', 'dosen.jk', 'team.nama_team')->get();
        return $dosen;
    }
}
