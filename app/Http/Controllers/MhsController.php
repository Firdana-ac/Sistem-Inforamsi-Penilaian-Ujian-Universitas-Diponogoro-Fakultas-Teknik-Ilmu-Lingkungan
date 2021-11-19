<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use App\Kelas;
use App\Mhs;
use App\Exports\MhsExport;
use App\Imports\MhsImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;

class MhsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kelas = Kelas::OrderBy('nama_kelas', 'asc')->get();
        return view('admin.mhs.index', compact('kelas'));
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
            'no_induk' => 'required|string|unique:mhs',
            'nama_mhs' => 'required',
            'jk' => 'required',
            'kelas_id' => 'required'
        ]);

        if ($request->foto) {
            $foto = $request->foto;
            $new_foto = date('siHdmY') . "_" . $foto->getClientOriginalName();
            $foto->move('uploads/mhs/', $new_foto);
            $nameFoto = 'uploads/mhs/' . $new_foto;
        } else {
            if ($request->jk == 'L') {
                $nameFoto = 'uploads/mhs/52471919042020_male.jpg';
            } else {
                $nameFoto = 'uploads/mhs/50271431012020_female.jpg';
            }
        }

        Mhs::create([
            'no_induk' => $request->no_induk,
            'nis' => $request->nis,
            'nama_mhs' => $request->nama_mhs,
            'jk' => $request->jk,
            'kelas_id' => $request->kelas_id,
            'telp' => $request->telp,
            'tmp_lahir' => $request->tmp_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'foto' => $nameFoto
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan data mhs baru!');
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
        $mhs = Mhs::findorfail($id);
        return view('admin.mhs.details', compact('mhs'));
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
        $mhs = Mhs::findorfail($id);
        $kelas = Kelas::all();
        return view('admin.mhs.edit', compact('mhs', 'kelas'));
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
            'nama_mhs' => 'required',
            'jk' => 'required',
            'kelas_id' => 'required'
        ]);

        $mhs = Mhs::findorfail($id);
        $user = User::where('no_induk', $mhs->no_induk)->first();
        if ($user) {
            $user_data = [
                'name' => $request->nama_mhs
            ];
            $user->update($user_data);
        } else {
        }
        $mhs_data = [
            'nis' => $request->nis,
            'nama_mhs' => $request->nama_mhs,
            'jk' => $request->jk,
            'kelas_id' => $request->kelas_id,
            'telp' => $request->telp,
            'tmp_lahir' => $request->tmp_lahir,
            'tgl_lahir' => $request->tgl_lahir,
        ];
        $mhs->update($mhs_data);

        return redirect()->route('mhs.index')->with('success', 'Data mhs berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mhs = Mhs::findorfail($id);
        $countUser = User::where('no_induk', $mhs->no_induk)->count();
        if ($countUser >= 1) {
            $user = User::where('no_induk', $mhs->no_induk)->first();
            $mhs->delete();
            $user->delete();
            return redirect()->back()->with('warning', 'Data mhs berhasil dihapus! (Silahkan cek trash data mhs)');
        } else {
            $mhs->delete();
            return redirect()->back()->with('warning', 'Data mhs berhasil dihapus! (Silahkan cek trash data mhs)');
        }
    }

    public function trash()
    {
        $mhs = Mhs::onlyTrashed()->get();
        return view('admin.mhs.trash', compact('mhs'));
    }

    public function restore($id)
    {
        $id = Crypt::decrypt($id);
        $mhs = Mhs::withTrashed()->findorfail($id);
        $countUser = User::withTrashed()->where('no_induk', $mhs->no_induk)->count();
        if ($countUser >= 1) {
            $user = User::withTrashed()->where('no_induk', $mhs->no_induk)->first();
            $mhs->restore();
            $user->restore();
            return redirect()->back()->with('info', 'Data mhs berhasil direstore! (Silahkan cek data mhs)');
        } else {
            $mhs->restore();
            return redirect()->back()->with('info', 'Data mhs berhasil direstore! (Silahkan cek data mhs)');
        }
    }

    public function kill($id)
    {
        $mhs = Mhs::withTrashed()->findorfail($id);
        $countUser = User::withTrashed()->where('no_induk', $mhs->no_induk)->count();
        if ($countUser >= 1) {
            $user = User::withTrashed()->where('no_induk', $mhs->no_induk)->first();
            $mhs->forceDelete();
            $user->forceDelete();
            return redirect()->back()->with('success', 'Data mhs berhasil dihapus secara permanent');
        } else {
            $mhs->forceDelete();
            return redirect()->back()->with('success', 'Data mhs berhasil dihapus secara permanent');
        }
    }

    public function ubah_foto($id)
    {
        $id = Crypt::decrypt($id);
        $mhs = Mhs::findorfail($id);
        return view('admin.mhs.ubah-foto', compact('mhs'));
    }

    public function update_foto(Request $request, $id)
    {
        $this->validate($request, [
            'foto' => 'required'
        ]);

        $mhs = Mhs::findorfail($id);
        $foto = $request->foto;
        $new_foto = date('s' . 'i' . 'H' . 'd' . 'm' . 'Y') . "_" . $foto->getClientOriginalName();
        $mhs_data = [
            'foto' => 'uploads/mhs/' . $new_foto,
        ];
        $foto->move('uploads/mhs/', $new_foto);
        $mhs->update($mhs_data);

        return redirect()->route('mhs.index')->with('success', 'Berhasil merubah foto!');
    }

    public function view(Request $request)
    {
        $mhs = Mhs::OrderBy('nama_mhs', 'asc')->where('kelas_id', $request->id)->get();

        foreach ($mhs as $val) {
            $newForm[] = array(
                'kelas' => $val->kelas->nama_kelas,
                'no_induk' => $val->no_induk,
                'nama_mhs' => $val->nama_mhs,
                'jk' => $val->jk,
                'foto' => $val->foto
            );
        }

        return response()->json($newForm);
    }

    public function cetak_pdf(Request $request)
    {
        $mhs = Mhs::OrderBy('nama_mhs', 'asc')->where('kelas_id', $request->id)->get();
        $kelas = Kelas::findorfail($request->id);

        $pdf = PDF::loadView('mhs-pdf', ['mhs' => $mhs, 'kelas' => $kelas]);
        return $pdf->stream();
        // return $pdf->stream('jadwal-pdf.pdf');
    }

    public function kelas($id)
    {
        $id = Crypt::decrypt($id);
        $mhs = Mhs::where('kelas_id', $id)->OrderBy('nama_mhs', 'asc')->get();
        $kelas = Kelas::findorfail($id);
        return view('admin.mhs.show', compact('mhs', 'kelas'));
    }

    public function export_excel()
    {
        return Excel::download(new MhsExport, 'mhs.xlsx');
    }

    public function import_excel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('file');
        $nama_file = rand() . $file->getClientOriginalName();
        $file->move('file_mhs', $nama_file);
        Excel::import(new MhsImport, public_path('/file_mhs/' . $nama_file));
        return redirect()->back()->with('success', 'Data Mhs Berhasil Diimport!');
    }

    public function deleteAll()
    {
        $mhs = Mhs::all();
        if ($mhs->count() >= 1) {
            Mhs::whereNotNull('id')->delete();
            Mhs::withTrashed()->whereNotNull('id')->forceDelete();
            return redirect()->back()->with('success', 'Data table mhs berhasil dihapus!');
        } else {
            return redirect()->back()->with('warning', 'Data table mhs kosong!');
        }
    }
}
