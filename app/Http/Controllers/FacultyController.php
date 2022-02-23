<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Data Fakultas dan Prodi";
        $prodis = Prodi::all();
        $faculties = Faculty::all();
        return view('master.prodiFakultas', [
            'title' => $title,
            'prodis' => $prodis,
            'faculties' => $faculties,
        ]);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_faculty' => ['required', 'unique:faculties'],
        ],
            [
                'unique' => 'Nama fakultas ini sudah ada',
            ],
        );

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        Faculty::create($request->all());
        Alert::success('Berhasil', 'Fakultas baru berhasil ditambahkan');
        return back();

    }

    public function update(Request $request, Faculty $faculty)
    {
        if ($faculty->nama_faculty != $request->nama_faculty) {
            $validator = Validator::make($request->all(), [
                'nama_faculty' => ['required', 'unique:faculties'],
            ],
                [
                    'nama_faculty.unique' => 'Fakultas ini sudah ada',
                ], );

            if ($validator->fails()) {
                Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
                return back()->withErrors($validator)->withInput();
            }
            $faculty->nama_faculty = $request->nama_faculty;
        }
        $faculty->save();
        Alert::success('Berhasil', 'Fakultas berhasil diubah');
        return back();

    }

}
