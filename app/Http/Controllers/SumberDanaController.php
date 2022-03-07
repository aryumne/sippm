<?php

namespace App\Http\Controllers;

use App\Models\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class SumberDanaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Daftar Sumber Dana";
        $sumberDana = SumberDana::all();
        return view('master.sumberDana', [
            'title' => $title,
            'sumberDana' => $sumberDana,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sumber' => ['required', 'unique:sumber_danas', 'string'],
        ], [
            'sumber.unique' => 'Sumber Dana ini sudah ada',
        ],);
        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }
        SumberDana::create([
            'sumber' => $request->sumber,
        ]);
        Alert::success('Berhasil', 'Data Sumber Dana baru telah ditambahkan');
        return back();
    }

    public function update(Request $request, $id)
    {
        $sumberDana = SumberDana::find($id);
        if ($sumberDana->sumber != $request->sumber) {
            $validator = Validator::make(
                $request->all(),
                [
                    'sumber' => ['required', 'unique:sumber_danas'],
                ],
                [
                    'sumber.unique' => 'Sumber Dana ini sudah ada',
                ],
            );

            if ($validator->fails()) {
                Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
                return back()->withErrors($validator)->withInput();
            }
            $sumberDana->sumber = $request->sumber;
        }
        $sumberDana->save();
        Alert::success('Berhasil', 'Sumber Dana berhasil diubah');
        return back();
    }
}
