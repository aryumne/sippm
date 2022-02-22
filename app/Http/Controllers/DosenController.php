<?php

namespace App\Http\Controllers;

use App\Imports\DosenImport;
use App\Models\Dosen;
use App\Models\Jabatan;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Dosen $dosen)
    {
        // dd($dosen);
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
}
