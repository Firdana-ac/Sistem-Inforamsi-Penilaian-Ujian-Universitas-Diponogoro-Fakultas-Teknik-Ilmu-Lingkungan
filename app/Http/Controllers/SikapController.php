<?php

namespace App\Http\Controllers;

use Auth;
use App\Team;
use App\Dosen;
use App\Mhs;
use App\Kelas;
use App\Jadwal;
use App\Sikap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use DB;

class SikapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dosen = Dosen::where('id_card', Auth::user()->id_card)->first();
        if (
            $dosen->team->nama_team == "Pendidikan Agama dan Budi Pekerti" ||
            $dosen->team->nama_team == "Pendidikan Pancasila dan Kewarganegaraan"
        ) {
            $jadwal = Jadwal::where('dosen_id', $dosen->id)->orderBy('kelas_id')->get();
            $kelas = $jadwal->groupBy('kelas_id');
            return view('dosen.sikap.index', compact('kelas', 'dosen'));
        } else {
            return redirect()->back()->with('error', 'Maaf dosen ini tidak dapat menambahkan nilai sikap!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.sikap.home', compact('kelas'));
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
            if (
                $dosen->team->nama_team == "Pendidikan Agama dan Budi Pekerti" ||
                $dosen->team->nama_team == "Pendidikan Pancasila dan Kewarganegaraan"
            ) {
                Sikap::updateOrCreate(
                    [
                        'id' => $request->id
                    ],
                    [
                        'mhs_id' => $request->mhs_id,
                        'kelas_id' => $request->kelas_id,
                        'dosen_id' => $request->dosen_id,
                        'team_id' => $dosen->team_id,
                        'sikap_1' => $request->sikap_1,
                        'sikap_2' => $request->sikap_2,
                        'sikap_3' => $request->sikap_3
                    ]
                );
                return response()->json(['success' => 'Nilai sikap mhs berhasil ditambahkan!']);
            } else {
                return redirect()->json(['error' => 'Maaf dosen ini tidak dapat menambahkan nilai sikap!']);
            }
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
        return view('dosen.sikap.show', compact('dosen', 'kelas', 'mhs'));
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
        return view('admin.sikap.index', compact('kelas', 'mhs'));
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

    public function sikap($id)
    {
        $id = Crypt::decrypt($id);
        $mhs = Mhs::findorfail($id);
        $kelas = Kelas::findorfail($mhs->kelas_id);
        $team = Team::where('nama_team', 'Pendidikan Agama dan Budi Pekerti')->orWhere('nama_team', 'Pendidikan Pancasila dan Kewarganegaraan')->get();
        return view('admin.sikap.show', compact('team', 'mhs', 'kelas'));
    }

    public function mhs()
    {
        $mhs = Mhs::where('no_induk', Auth::user()->no_induk)->first();
        $kelas = Kelas::findorfail($mhs->kelas_id);
        $team = Team::where('nama_team', 'Pendidikan Agama dan Budi Pekerti')->orWhere('nama_team', 'Pendidikan Pancasila dan Kewarganegaraan')->get();
        return view('mhs.sikap', compact('mhs', 'kelas', 'team'));
    }
}
