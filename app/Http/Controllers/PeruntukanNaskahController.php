<?php

namespace App\Http\Controllers;

use App\Models\Peruntukan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class PeruntukanNaskahController extends Controller
{
    public function index()
    {
        $title = "Jenis Peruntukan";
        $peruntukanNaskahs = Peruntukan::all();
        return view('master.peruntukanNaskah', [
            'title' => $title,
            'peruntukanNaskahs' => $peruntukanNaskahs,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_peruntukan' => ['required', 'unique:peruntukans', 'string'],
        ], [
            'nama_peruntukan.unique' => 'Jenis Peruntukan sudah ada',
        ],);
        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }
        Peruntukan::create([
            'nama_peruntukan' => $request->nama_peruntukan,
        ]);
        Alert::success('Berhasil', 'Jenis Peruntukan baru telah ditambahkan');
        return back();
    }

    public function update(Request $request, $id)
    {
        $peruntukanNaskah = Peruntukan::find($id);
        if ($peruntukanNaskah->nama_peruntukan != $request->nama_peruntukan) {
            $validator = Validator::make(
                $request->all(),
                [
                    'nama_peruntukan' => ['required', 'unique:peruntukans', 'string'],
                ],
                [
                    'nama_peruntukan.unique' => 'Jenis Peruntukan sudah ada',
                ],
            );

            if ($validator->fails()) {
                Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
                return back()->withErrors($validator)->withInput();
            }
            $peruntukanNaskah->nama_peruntukan = $request->nama_peruntukan;
        }
        $peruntukanNaskah->save();
        Alert::success('Berhasil', 'Jenis Peruntukan berhasil diubah');
        return back();
    }
}
