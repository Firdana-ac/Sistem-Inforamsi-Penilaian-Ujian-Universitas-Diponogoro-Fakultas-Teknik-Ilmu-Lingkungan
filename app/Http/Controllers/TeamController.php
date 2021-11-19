<?php

namespace App\Http\Controllers;

use App\Jadwal;
use App\Team;
use App\Paket;
use App\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $team = Team::OrderBy('kelompok', 'asc')->OrderBy('nama_team', 'asc')->get();
        $paket = Paket::all();
        return view('admin.team.index', compact('team', 'paket'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_team' => 'required',
            'paket_id' => 'required',
            'kelompok' => 'required'
        ]);

        Team::updateOrCreate(
            [
                'id' => $request->team_id
            ],
            [
                'nama_team' => $request->nama_team,
                'paket_id' => $request->paket_id,
                'kelompok' => $request->kelompok,
            ]
        );

        return redirect()->back()->with('success', 'Data team berhasil diperbarui!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $team = Team::findorfail($id);
        $paket = Paket::all();
        return view('admin.team.edit', compact('team', 'paket'));
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
        $team = Team::findorfail($id);
        $countJadwal = Jadwal::where('team_id', $team->id)->count();
        if ($countJadwal >= 1) {
            $jadwal = Jadwal::where('team_id', $team->id)->delete();
        } else {
        }
        $countDosen = Dosen::where('team_id', $team->id)->count();
        if ($countDosen >= 1) {
            $dosen = Dosen::where('team_id', $team->id)->delete();
        } else {
        }
        $team->delete();
        return redirect()->back()->with('warning', 'Data team berhasil dihapus! (Silahkan cek trash data team)');
    }

    public function trash()
    {
        $team = Team::onlyTrashed()->get();
        return view('admin.team.trash', compact('team'));
    }

    public function restore($id)
    {
        $id = Crypt::decrypt($id);
        $team = Team::withTrashed()->findorfail($id);
        $countJadwal = Jadwal::withTrashed()->where('team_id', $team->id)->count();
        if ($countJadwal >= 1) {
            $jadwal = Jadwal::withTrashed()->where('team_id', $team->id)->restore();
        } else {
        }
        $countDosen = Dosen::withTrashed()->where('team_id', $team->id)->count();
        if ($countDosen >= 1) {
            $dosen = Dosen::withTrashed()->where('team_id', $team->id)->restore();
        } else {
        }
        $team->restore();
        return redirect()->back()->with('info', 'Data team berhasil direstore! (Silahkan cek data team)');
    }

    public function kill($id)
    {
        $team = Team::withTrashed()->findorfail($id);
        $countJadwal = Jadwal::withTrashed()->where('team_id', $team->id)->count();
        if ($countJadwal >= 1) {
            $jadwal = Jadwal::withTrashed()->where('team_id', $team->id)->forceDelete();
        } else {
        }
        $countDosen = Dosen::withTrashed()->where('team_id', $team->id)->count();
        if ($countDosen >= 1) {
            $dosen = Dosen::withTrashed()->where('team_id', $team->id)->forceDelete();
        } else {
        }
        $team->forceDelete();
        return redirect()->back()->with('success', 'Data team berhasil dihapus secara permanent');
    }

    public function getTeamJson(Request $request)
    {
        $jadwal = Jadwal::OrderBy('team_id', 'asc')->where('kelas_id', $request->kelas_id)->get();
        $jadwal = $jadwal->groupBy('team_id');

        foreach ($jadwal as $val => $data) {
            $newForm[] = array(
                'team' => $data[0]->pelajaran($val)->nama_team,
                'dosen' => $data[0]->pengajar($data[0]->dosen_id)->id
            );
        }

        return response()->json($newForm);
    }
}
