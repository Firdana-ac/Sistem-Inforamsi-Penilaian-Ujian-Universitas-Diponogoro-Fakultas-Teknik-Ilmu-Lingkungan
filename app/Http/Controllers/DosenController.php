<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Dosen;
use App\Mapel;
use App\Jadwal;
use App\Absen;
use App\Kehadiran;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\DosenExport;
use App\Imports\DosenImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Nilai;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mapel = Mapel::orderBy('nama_mapel')->get();
        $max = Dosen::max('id_card');
        return view('admin.dosen.index', compact('mapel', 'max'));
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
            'id_card' => 'required',
            'nama_dosen' => 'required',
            'mapel_id' => 'required',
            'kode' => 'required|string|unique:dosen|min:2|max:3',
            'jk' => 'required'
        ]);

        if ($request->foto) {
            $foto = $request->foto;
            $new_foto = date('siHdmY') . "_" . $foto->getClientOriginalName();
            $foto->move('uploads/dosen/', $new_foto);
            $nameFoto = 'uploads/dosen/' . $new_foto;
        } else {
            if ($request->jk == 'L') {
                $nameFoto = 'uploads/dosen/35251431012020_male.jpg';
            } else {
                $nameFoto = 'uploads/dosen/23171022042020_female.jpg';
            }
        }

        $dosen = Dosen::create([
            'id_card' => $request->id_card,
            'nip' => $request->nip,
            'nama_dosen' => $request->nama_dosen,
            'mapel_id' => $request->mapel_id,
            'kode' => $request->kode,
            'jk' => $request->jk,
            'telp' => $request->telp,
            'tmp_lahir' => $request->tmp_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'foto' => $nameFoto
        ]);

        Nilai::create([
            'dosen_id' => $dosen->id
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan data dosen baru!');
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
        $dosen = Dosen::findorfail($id);
        return view('admin.dosen.details', compact('dosen'));
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
        $dosen = Dosen::findorfail($id);
        $mapel = Mapel::all();
        return view('admin.dosen.edit', compact('dosen', 'mapel'));
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
        $this->validate($request, [
            'nama_dosen' => 'required',
            'mapel_id' => 'required',
            'jk' => 'required',
        ]);

        $dosen = Dosen::findorfail($id);
        $user = User::where('id_card', $dosen->id_card)->first();
        if ($user) {
            $user_data = [
                'name' => $request->nama_dosen
            ];
            $user->update($user_data);
        } else {
        }
        $dosen_data = [
            'nama_dosen' => $request->nama_dosen,
            'mapel_id' => $request->mapel_id,
            'jk' => $request->jk,
            'telp' => $request->telp,
            'tmp_lahir' => $request->tmp_lahir,
            'tgl_lahir' => $request->tgl_lahir
        ];
        $dosen->update($dosen_data);

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dosen = Dosen::findorfail($id);
        $countJadwal = Jadwal::where('dosen_id', $dosen->id)->count();
        if ($countJadwal >= 1) {
            $jadwal = Jadwal::where('dosen_id', $dosen->id)->delete();
        } else {
        }
        $countUser = User::where('id_card', $dosen->id_card)->count();
        if ($countUser >= 1) {
            $user = User::where('id_card', $dosen->id_card)->delete();
        } else {
        }
        $dosen->delete();
        return redirect()->route('dosen.index')->with('warning', 'Data dosen berhasil dihapus! (Silahkan cek trash data dosen)');
    }

    public function trash()
    {
        $dosen = Dosen::onlyTrashed()->get();
        return view('admin.dosen.trash', compact('dosen'));
    }

    public function restore($id)
    {
        $id = Crypt::decrypt($id);
        $dosen = Dosen::withTrashed()->findorfail($id);
        $countJadwal = Jadwal::withTrashed()->where('dosen_id', $dosen->id)->count();
        if ($countJadwal >= 1) {
            $jadwal = Jadwal::withTrashed()->where('dosen_id', $dosen->id)->restore();
        } else {
        }
        $countUser = User::withTrashed()->where('id_card', $dosen->id_card)->count();
        if ($countUser >= 1) {
            $user = User::withTrashed()->where('id_card', $dosen->id_card)->restore();
        } else {
        }
        $dosen->restore();
        return redirect()->back()->with('info', 'Data dosen berhasil direstore! (Silahkan cek data dosen)');
    }

    public function kill($id)
    {
        $dosen = Dosen::withTrashed()->findorfail($id);
        $countJadwal = Jadwal::withTrashed()->where('dosen_id', $dosen->id)->count();
        if ($countJadwal >= 1) {
            $jadwal = Jadwal::withTrashed()->where('dosen_id', $dosen->id)->forceDelete();
        } else {
        }
        $countUser = User::withTrashed()->where('id_card', $dosen->id_card)->count();
        if ($countUser >= 1) {
            $user = User::withTrashed()->where('id_card', $dosen->id_card)->forceDelete();
        } else {
        }
        $dosen->forceDelete();
        return redirect()->back()->with('success', 'Data dosen berhasil dihapus secara permanent');
    }

    public function ubah_foto($id)
    {
        $id = Crypt::decrypt($id);
        $dosen = Dosen::findorfail($id);
        return view('admin.dosen.ubah-foto', compact('dosen'));
    }

    public function update_foto(Request $request, $id)
    {
        $this->validate($request, [
            'foto' => 'required'
        ]);

        $dosen = Dosen::findorfail($id);
        $foto = $request->foto;
        $new_foto = date('s' . 'i' . 'H' . 'd' . 'm' . 'Y') . "_" . $foto->getClientOriginalName();
        $dosen_data = [
            'foto' => 'uploads/dosen/' . $new_foto,
        ];
        $foto->move('uploads/dosen/', $new_foto);
        $dosen->update($dosen_data);

        return redirect()->route('dosen.index')->with('success', 'Berhasil merubah foto!');
    }

    public function mapel($id)
    {
        $id = Crypt::decrypt($id);
        $mapel = Mapel::findorfail($id);
        $dosen = Dosen::where('mapel_id', $id)->orderBy('kode', 'asc')->get();
        return view('admin.dosen.show', compact('mapel', 'dosen'));
    }

    public function absen()
    {
        $absen = Absen::where('tanggal', date('Y-m-d'))->get();
        $kehadiran = Kehadiran::limit(4)->get();
        return view('dosen.absen', compact('absen', 'kehadiran'));
    }

    public function simpan(Request $request)
    {
        $this->validate($request, [
            'id_card' => 'required',
            'kehadiran_id' => 'required'
        ]);
        $cekDosen = Dosen::where('id_card', $request->id_card)->count();
        if ($cekDosen >= 1) {
            $dosen = Dosen::where('id_card', $request->id_card)->first();
            if ($dosen->id_card == Auth::user()->id_card) {
                $cekAbsen = Absen::where('dosen_id', $dosen->id)->where('tanggal', date('Y-m-d'))->count();
                if ($cekAbsen == 0) {
                    if (date('w') != '0' && date('w') != '6') {
                        if (date('H:i:s') >= '06:00:00') {
                            if (date('H:i:s') >= '09:00:00') {
                                if (date('H:i:s') >= '16:15:00') {
                                    Absen::create([
                                        'tanggal' => date('Y-m-d'),
                                        'dosen_id' => $dosen->id,
                                        'kehadiran_id' => '6',
                                    ]);
                                    return redirect()->back()->with('info', 'Maaf sekarang sudah waktunya pulang!');
                                } else {
                                    if ($request->kehadiran_id == '1') {
                                        $terlambat = date('H') - 9 . ' Jam ' . date('i') . ' Menit';
                                        if (date('H') - 9 == 0) {
                                            $terlambat = date('i') . ' Menit';
                                        }
                                        Absen::create([
                                            'tanggal' => date('Y-m-d'),
                                            'dosen_id' => $dosen->id,
                                            'kehadiran_id' => '5',
                                        ]);
                                        return redirect()->back()->with('warning', 'Maaf anda terlambat ' . $terlambat . '!');
                                    } else {
                                        Absen::create([
                                            'tanggal' => date('Y-m-d'),
                                            'dosen_id' => $dosen->id,
                                            'kehadiran_id' => $request->kehadiran_id,
                                        ]);
                                        return redirect()->back()->with('success', 'Anda hari ini berhasil absen!');
                                    }
                                }
                            } else {
                                Absen::create([
                                    'tanggal' => date('Y-m-d'),
                                    'dosen_id' => $dosen->id,
                                    'kehadiran_id' => $request->kehadiran_id,
                                ]);
                                return redirect()->back()->with('success', 'Anda hari ini berhasil absen tepat waktu!');
                            }
                        } else {
                            return redirect()->back()->with('info', 'Maaf absensi di mulai jam 6 pagi!');
                        }
                    } else {
                        $namaHari = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
                        $d = date('w');
                        $hari = $namaHari[$d];
                        return redirect()->back()->with('info', 'Maaf sekolah hari ' . $hari . ' libur!');
                    }
                } else {
                    return redirect()->back()->with('warning', 'Maaf absensi tidak bisa dilakukan 2x!');
                }
            } else {
                return redirect()->back()->with('error', 'Maaf id card ini bukan milik anda!');
            }
        } else {
            return redirect()->back()->with('error', 'Maaf id card ini tidak terdaftar!');
        }
    }

    public function absensi()
    {
        $dosen = Dosen::all();
        return view('admin.dosen.absen', compact('dosen'));
    }

    public function kehadiran($id)
    {
        $id = Crypt::decrypt($id);
        $dosen = Dosen::findorfail($id);
        $absen = Absen::orderBy('tanggal', 'desc')->where('dosen_id', $id)->get();
        return view('admin.dosen.kehadiran', compact('dosen', 'absen'));
    }

    public function export_excel()
    {
        return Excel::download(new DosenExport, 'dosen.xlsx');
    }

    public function import_excel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('file');
        $nama_file = rand() . $file->getClientOriginalName();
        $file->move('file_dosen', $nama_file);
        Excel::import(new DosenImport, public_path('/file_dosen/' . $nama_file));
        return redirect()->back()->with('success', 'Data dosen Berhasil Diimport!');
    }

    public function deleteAll()
    {
        $dosen = Dosen::all();
        if ($dosen->count() >= 1) {
            Dosen::whereNotNull('id')->delete();
            Dosen::withTrashed()->whereNotNull('id')->forceDelete();
            return redirect()->back()->with('success', 'Data table dosen berhasil dihapus!');
        } else {
            return redirect()->back()->with('warning', 'Data table dosen kosong!');
        }
    }
}
