<?php

namespace App\Http\Controllers;

use App\Models\Jenis_jurnal;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class MediaPublikasiController extends Controller
{
    public function index()
    {
        $title = "Media Publikasi";
        $mediaPublikasis = Jenis_jurnal::all();
        return view('master.mediaPublikasi', [
            'title' => $title,
            'mediaPublikasis' => $mediaPublikasis,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jurnal' => ['required', 'unique:jenis_jurnals', 'string'],
        ], [
            'jurnal.unique' => 'Media publikasi sudah ada',
        ],);
        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }
        Jenis_jurnal::create([
            'jurnal' => $request->jurnal,
        ]);
        Alert::success('Berhasil', 'Media Publikasi baru telah ditambahkan');
        return back();
    }

    public function update(Request $request, $id)
    {
        $mediaPublikasi = Jenis_jurnal::find($id);
        if ($mediaPublikasi->jurnal != $request->jurnal) {
            $validator = Validator::make(
                $request->all(),
                [
                    'jurnal' => ['required', 'unique:jenis_jurnals'],
                ],
                [
                    'jurnal.unique' => 'Media publikasi sudah ada',
                ],
            );

            if ($validator->fails()) {
                Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
                return back()->withErrors($validator)->withInput();
            }
            $mediaPublikasi->jurnal = $request->jurnal;
        }
        $mediaPublikasi->save();
        Alert::success('Berhasil', 'Media Publikasi berhasil diubah');
        return back();
    }
}
