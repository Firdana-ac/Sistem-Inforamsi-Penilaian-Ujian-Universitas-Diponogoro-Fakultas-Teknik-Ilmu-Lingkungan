<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sikap extends Model
{
    protected $fillable = ['mhs_id', 'kelas_id', 'dosen_id', 'team_id', 'sikap_1', 'sikap_2', 'sikap_3'];

    protected $table = 'sikap';
}
