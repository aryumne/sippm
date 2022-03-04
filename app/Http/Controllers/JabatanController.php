<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Daftar Jabatan";
        $jabatans = Jabatan::all();
        return view('master.jabatan', [
            'title' => $title,
            'jabatans' => $jabatans,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jabatan' => ['required', 'unique:jabatans', 'string'],
        ], [
            'nama_jabatan.unique' => 'Jabatan ini sudah ada',
        ], );
        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }
        Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
        ]);
        Alert::success('Berhasil', 'Data Jabatan baru telah ditambahkan');
        return back();
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        if ($jabatan->nama_jabatan != $request->nama_jabatan) {
            $validator = Validator::make($request->all(), [
                'nama_jabatan' => ['required', 'unique:jabatans'],
            ],
                [
                    'nama_jabatan.unique' => 'Jabatan ini sudah ada',
                ], );

            if ($validator->fails()) {
                Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
                return back()->withErrors($validator)->withInput();
            }
            $jabatan->nama_jabatan = $request->nama_jabatan;
        }
        $jabatan->save();
        Alert::success('Berhasil', 'jabatan berhasil diubah');
        return back();

    }

}
