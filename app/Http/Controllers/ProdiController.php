<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ProdiController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_prodi' => ['required', 'unique:prodis'],
            'faculty_id' => ['required'],
        ],
            [
                'nama_prodi.unique' => 'Prodi ini sudah ada',
            ], );

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        Prodi::create($request->all());
        Alert::success('Berhasil', 'Prodi baru berhasil ditambahkan');
        return back();

    }

    public function update(Request $request, Prodi $prodi)
    {
        if ($prodi->nama_prodi != $request->nama_prodi) {
            $validator = Validator::make($request->all(), [
                'nama_prodi' => ['required', 'unique:prodis'],
                'faculty_id' => ['required'],
            ],
                [
                    'nama_prodi.unique' => 'Prodi ini sudah ada',
                ], );

            if ($validator->fails()) {
                Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
                return back()->withErrors($validator)->withInput();
            }
            $prodi->nama_prodi = $request->nama_prodi;
        }
        $prodi->save();
        Alert::success('Berhasil', 'Prodi berhasil diubah');
        return back();

    }

    public function destroy($id)
    {
        //
    }
}
