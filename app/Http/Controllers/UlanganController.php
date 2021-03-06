<?php

namespace App\Http\Controllers;

use Auth;
use App\Dosen;
use App\Mhs;
use App\Kelas;
use App\Jadwal;
use App\Nilai;
use App\Ulangan;
use App\Rapot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class UlanganController extends Controller
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
        return view('dosen.ulangan.kelas', compact('kelas', 'dosen'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.ulangan.home', compact('kelas'));
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
            if ($request->ulha_1 && $request->ulha_2 && $request->uts && $request->ulha_3 && $request->uas) {
                $nilai = ($request->ulha_1 + $request->ulha_2 + $request->uts + $request->ulha_3 + (2 * $request->uas)) / 6;
                $nilai = (int) $nilai;
                $deskripsi = Nilai::where('dosen_id', $request->dosen_id)->first();
                $isi = Nilai::where('dosen_id', $request->dosen_id)->count();
                if ($isi >= 1) {
                    if ($nilai > 90) {
                        Rapot::create([
                            'mhs_id' => $request->mhs_id,
                            'kelas_id' => $request->kelas_id,
                            'dosen_id' => $request->dosen_id,
                            'team_id' => $dosen->team_id,
                            'p_nilai' => $nilai,
                            'p_predikat' => 'A',
                            'p_deskripsi' => $deskripsi->deskripsi_a,
                        ]);
                    } else if ($nilai > 80) {
                        Rapot::create([
                            'mhs_id' => $request->mhs_id,
                            'kelas_id' => $request->kelas_id,
                            'dosen_id' => $request->dosen_id,
                            'team_id' => $dosen->team_id,
                            'p_nilai' => $nilai,
                            'p_predikat' => 'B',
                            'p_deskripsi' => $deskripsi->deskripsi_b,
                        ]);
                    } else if ($nilai > 70) {
                        Rapot::create([
                            'mhs_id' => $request->mhs_id,
                            'kelas_id' => $request->kelas_id,
                            'dosen_id' => $request->dosen_id,
                            'team_id' => $dosen->team_id,
                            'p_nilai' => $nilai,
                            'p_predikat' => 'C',
                            'p_deskripsi' => $deskripsi->deskripsi_c,
                        ]);
                    } else {
                        Rapot::create([
                            'mhs_id' => $request->mhs_id,
                            'kelas_id' => $request->kelas_id,
                            'dosen_id' => $request->dosen_id,
                            'team_id' => $dosen->team_id,
                            'p_nilai' => $nilai,
                            'p_predikat' => 'D',
                            'p_deskripsi' => $deskripsi->deskripsi_d,
                        ]);
                    }
                } else {
                    return response()->json(['error' => 'Tolong masukkan deskripsi predikat anda terlebih dahulu!']);
                }
            } else {
            }
            Ulangan::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'mhs_id' => $request->mhs_id,
                    'kelas_id' => $request->kelas_id,
                    'dosen_id' => $request->dosen_id,
                    'team_id' => $dosen->team_id,
                    'ulha_1' => $request->ulha_1,
                    'ulha_2' => $request->ulha_2,
                    'uts' => $request->uts,
                    'ulha_3' => $request->ulha_3,
                    'uas' => $request->uas,
                ]
            );
            return response()->json(['success' => 'Nilai ulangan mhs berhasil ditambahkan!']);
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
        return view('dosen.ulangan.nilai', compact('dosen', 'kelas', 'mhs'));
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
        return view('admin.ulangan.index', compact('kelas', 'mhs'));
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

    public function ulangan($id)
    {
        $id = Crypt::decrypt($id);
        $mhs = Mhs::findorfail($id);
        $kelas = Kelas::findorfail($mhs->kelas_id);
        $jadwal = Jadwal::orderBy('team_id')->where('kelas_id', $kelas->id)->get();
        $team = $jadwal->groupBy('team_id');
        return view('admin.ulangan.show', compact('team', 'mhs', 'kelas'));
    }

    public function mhs()
    {
        $mhs = Mhs::where('no_induk', Auth::user()->no_induk)->first();
        $kelas = Kelas::findorfail($mhs->kelas_id);
        $jadwal = Jadwal::where('kelas_id', $kelas->id)->orderBy('team_id')->get();
        $team = $jadwal->groupBy('team_id');
        return view('mhs.ulangan', compact('mhs', 'kelas', 'team'));
    }
}
