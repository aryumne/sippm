<?php

namespace App\Http\Controllers;

use App\Models\Jenis_hki;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class JenisHkiController extends Controller
{
    public function index()
    {
        $title = "Jenis HKI";
        $jenisHkis = Jenis_hki::all();
        return view('master.jenisHki', [
            'title' => $title,
            'jenisHkis' => $jenisHkis,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hki' => ['required', 'unique:jenis_hkis', 'string'],
        ], [
            'hki.unique' => 'Jenis HKI sudah ada',
        ],);
        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }
        Jenis_hki::create([
            'hki' => $request->hki,
        ]);
        Alert::success('Berhasil', 'Jenis HKI baru telah ditambahkan');
        return back();
    }

    public function update(Request $request, $id)
    {
        $jenisHki = Jenis_hki::find($id);
        if ($jenisHki->hki != $request->hki) {
            $validator = Validator::make(
                $request->all(),
                [
                    'hki' => ['required', 'unique:jenis_hkis', 'string'],
                ],
                [
                    'hki.unique' => 'Jenis HKI sudah ada',
                ],
            );

            if ($validator->fails()) {
                Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
                return back()->withErrors($validator)->withInput();
            }
            $jenisHki->hki = $request->hki;
        }
        $jenisHki->save();
        Alert::success('Berhasil', 'Jenis HKI berhasil diubah');
        return back();
    }
}
