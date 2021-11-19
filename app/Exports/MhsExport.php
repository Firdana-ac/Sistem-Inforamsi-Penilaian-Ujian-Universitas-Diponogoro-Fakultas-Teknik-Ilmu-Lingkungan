<?php

namespace App\Exports;

use App\Mhs;
use Maatwebsite\Excel\Concerns\FromCollection;

class MhsExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $mhs = Mhs::join('kelas', 'kelas.id', '=', 'mhs.kelas_id')->select('mhs.nama_mhs', 'mhs.no_induk', 'mhs.jk', 'kelas.nama_kelas')->get();
        return $mhs;
    }
}
