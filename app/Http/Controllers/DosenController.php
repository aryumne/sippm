<?php

namespace App\Http\Controllers;

use App\Imports\DosenImport;
use App\Models\Dosen;
use App\Models\Jabatan;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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

    public function edit()
    {
        $title = "Profile User";
        $nidn = Auth::user()->nidn;
        $dosen = Dosen::where('nidn', $nidn)->get();
        $jabatan = Jabatan::all();
        $prodi = Prodi::all();

        return view('auth.user', [
            'title' => $title,
            'data' => $dosen,
            'jabatan' => $jabatan,
            'prodi' => $prodi,
        ]);
    }

    public function update(Request $request, Dosen $dosen)
    {
        // dd($request->all());
        $rules = [
            'nama' => ['required', 'string'],
            'jabatan_id' => ['required', 'numeric'],
            'prodi_id' => ['required', 'numeric'],
            'handphone' => ['required', 'string'],
            'email' => ['required', 'email'],
        ];

        if ($request->nidn != $dosen->nidn) {
            $rules['nidn'] = ['required', 'unique:dosens,nidn'];
        }

        $validator = Validator::make($request->all(), $rules, [
            'nidn.unique' => 'NIDN sudah terdaftar',
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        Dosen::findOrFail($dosen->nidn)->update([
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'jabatan_id' => $request->jabatan_id,
            'prodi_id' => $request->prodi_id,
            'handphone' => $request->handphone,
            'email' => $request->email,
        ]);

        User::where('nidn', $dosen->nidn)->update([
            'nidn' => $request->nidn,
        ]);

        Alert::success('Data Profile berhasil diubah', 'success');
        return redirect()->route('dosen.show', $request->nidn);
    }
    public function updateProfile(Request $request)
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
            'email' => $request->email,
        ]);

        Alert::success('Data Profile berhasil diubah', 'success');
        return back();
    }

}
