<?php

namespace App\Http\Controllers;

use App\Dosen;
use App\Kelas;
use App\Team;
use App\Nilai;
use App\Rapot;
use App\Sikap;
use App\Mhs;
use App\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class RapotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dosen = Dosen::where('id_card', Auth::user()->id_card)->first();
        $jadwal = Jadwal::where('dosen_id', $dosen->id)->orderBy('kelas_id')->get();
        $kelas = $jadwal->groupBy('kelas_id');

        return view('dosen.rapot.kelas', compact('kelas', 'dosen'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.rapot.home', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dosen = Dosen::findorfail($request->dosen_id);
        $cekJadwal = Jadwal::where('dosen_id', $dosen->id)->where('kelas_id', $request->kelas_id)->count();
        if ($cekJadwal >= 1) {
            Rapot::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'mhs_id' => $request->mhs_id,
                    'kelas_id' => $request->kelas_id,
                    'dosen_id' => $request->dosen_id,
                    'team_id' => $dosen->team_id,
                    'k_nilai' => $request->nilai,
                    'k_predikat' => $request->predikat,
                    'k_deskripsi' => $request->deskripsi,
                ]
            );
            return response()->json(['success' => 'Nilai rapot mhs berhasil ditambahkan!']);
        } else {
            return response()->json(['error' => 'Maaf dosen ini tidak mengajar kelas ini!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $dosen = Dosen::where('id_card', Auth::user()->id_card)->first();
        $kelas = Kelas::findorfail($id);
        $mhs = Mhs::where('kelas_id', $id)->get();
        return view('dosen.rapot.rapot', compact('dosen', 'kelas', 'mhs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $kelas = Kelas::findorfail($id);
        $mhs = Mhs::orderBy('nama_mhs')->where('kelas_id', $id)->get();
        return view('admin.rapot.index', compact('kelas', 'mhs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function rapot($id)
    {
        $id = Crypt::decrypt($id);
        $mhs = Mhs::findorfail($id);
        $kelas = Kelas::findorfail($mhs->kelas_id);
        $jadwal = Jadwal::orderBy('team_id')->where('kelas_id', $kelas->id)->get();
        $team = $jadwal->groupBy('team_id');
        return view('admin.rapot.show', compact('team', 'mhs', 'kelas'));
    }

    public function predikat(Request $request)
    {
        $nilai = Nilai::where('dosen_id', $request->id)->first();
        if ($request->nilai > 79) {
            $newForm[] = array(
                'predikat' => 'A',
                'deskripsi' => $nilai->deskripsi_a,
            );
        } else if ($request->nilai > 69) {
            $newForm[] = array(
                'predikat' => 'B',
                'deskripsi' => $nilai->deskripsi_b,
            );
        } else if ($request->nilai > 50) {
            $newForm[] = array(
                'predikat' => 'C',
                'deskripsi' => $nilai->deskripsi_c,
            );
        } else {
            $newForm[] = array(
                'predikat' => 'D',
                'deskripsi' => $nilai->deskripsi_d,
            );
        }
        return response()->json($newForm);
    }

    public function mhs()
    {
        $mhs = Mhs::where('no_induk', Auth::user()->no_induk)->first();
        $kelas = Kelas::findorfail($mhs->kelas_id);
        $pai = Team::where('nama_team', 'Pendidikan Agama dan Budi Pekerti')->first();
        $ppkn = Team::where('nama_team', 'Pendidikan Pancasila dan Kewarganegaraan')->first();
        if ($pai != null && $ppkn != null) {
            $Spai = Sikap::where('mhs_id', $mhs->id)->where('team_id', $pai->id)->first();
            $Sppkn = Sikap::where('mhs_id', $mhs->id)->where('team_id', $ppkn->id)->first();
        } else {
            $Spai = "";
            $Sppkn = "";
        }
        $jadwal = Jadwal::where('kelas_id', $kelas->id)->orderBy('team_id')->get();
        $team = $jadwal->groupBy('team_id');
        return view('mhs.rapot', compact('mhs', 'kelas', 'team', 'Spai', 'Sppkn'));
    }
}
