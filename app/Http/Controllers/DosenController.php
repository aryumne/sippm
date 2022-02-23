<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Faculty;
use App\Models\Jabatan;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Imports\DosenImport;
use Maatwebsite\Excel\Facades\Excel;

use RealRashid\SweetAlert\Facades\Alert;

class DosenController extends Controller
{

    public function index()
    {
        $title = "Daftar Dosen";
        $dosens = Dosen::all();
        $prodis = Prodi::all()->sortBy('nama_prodi');
        $jabatans = Jabatan::all();
        return view('master.dosen', [
            'title' => $title,
            'dosens' => $dosens,
            'prodis' => $prodis,
            'jabatans' => $jabatans,
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nidn' => ['required', 'unique:dosens', 'numeric'],
            'nama' => ['required', 'string'],
            'jabatan_id' => ['required'],
            'prodi_id' => ['required'],
            'email' => ['required', 'email', 'max:255', 'unique:dosens'],
            'handphone' => ['required', 'numeric'],
        ], [
            'nidn.unique' => 'NIDN ini sudah terdaftar',
            'email.unique' => 'Email ini sudah terdaftar',
        ]);

        // dd($request->all());
        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        Dosen::create($request->all());
        Alert::success('Berhasil', 'Data dosen baru telah ditambahkan');
        return back();
    }

    public function import(Request $request)
    {
        $file = $request->file('file_excel');
        $fileName = $file->getClientOriginalName();
        $getPath = $file->move('DataExcel', $fileName);
        Excel::import(new DosenImport, $getPath);
        Alert::success('Berhasil', 'Data berhasil di import');
        return back();
    }

    public function show(Dosen $dosen)
    {
        $title = "Data $dosen->nama";
        $prodis = Prodi::all()->sortBy('nama_prodi');
        $jabatans = Jabatan::all();
        return view('master.showDosen', [
            'title' => $title,
            'dosen' => $dosen,
            'prodis' => $prodis,
            'jabatans' => $jabatans,
        ]);
    }

    public function edit($id)
    {
        $id = Auth::user()->nidn;
        $title = "Profile User";
        $dosen = Dosen::where('nidn', $id)->get();
        $jabatan = Jabatan::all();
        $fakultas = Faculty::all();
        $prodi = Prodi::all();

        return view('user', [
            'title' => $title,
            'data' => $dosen,
            'jabatan' => $jabatan,
            'fakultas' => $fakultas,
            'prodi' => $prodi,
        ]);
    }

    public function update(Request $request, $id)
    {

        $rules = [
            'nidn' => ['required', 'string'],
            'nama' => ['required', 'string'],
            'jabatan' => ['required', 'numeric'],
            'prodi' => ['required', 'numeric'],
            'noHp' => ['required', 'string'],
            'email' => ['required', 'email'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $nidn = Auth::user()->nidn;
        $prodi = $request->prodi;
        Dosen::findOrFail($nidn)->update([
            'nama' => $request->nama,
            'jabatan_id' => $request->jabatan,
            'prodi_id' => $prodi,
            'handphone' => $request->noHp,
            'email' => $request->email
        ]);

        Alert::success('Data Profile berhasil diubah', 'success');
        // return redirect()->route('dosen.show', $nidn);
        return back();
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
}
