<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Faculty;
use App\Models\Jabatan;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = Auth::user()->nidn;
        $title = "Profile User";
        $dosen = Dosen::where('nidn', $id)->get();
        $jabatan = Jabatan::all();
        $fakultas = Faculty::all();
        $prodi = Prodi::all();

        // dd($dosen);
        //buat tabel join
        // $prodi = Prodi::join('faculties', 'prodis.faculty_id', '=', 'faculties.id')->get();
        // dd($prodi);
        return view('user', [
            'title' => $title,
            'data' => $dosen,
            'jabatan' => $jabatan,
            'fakultas' => $fakultas,
            'prodi' => $prodi,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nidn)
    {

        $rules = [
            'nidn' => ['required', 'string'],
            'nama' => ['required', 'string'],
            'jabatan' => ['required', 'numeric'],
            'fakultas' => ['required', 'numeric'],
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
        $fakultas = $request->fakultas;
        Prodi::where('id', $prodi)->update(['faculty_id' => $fakultas]);
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
