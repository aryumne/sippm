<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Hki;
use App\Models\Jenis_hki;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Null_;

class HkiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Daftar Usulan Hki";
        $hki = Hki::all();
        $proposal = Proposal::all();
        $jenisHki = Jenis_hki::all();

        // dd($jenisHki);
        return view('proposal.Hki', [
            'title' => $title,
            'proposal' => $proposal,
            'jenisHki' => $jenisHki,
            'Hki' => $hki,
        ]);
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'proposal_id' => ['required', 'numeric'],
            'jenis' => ['required', 'numeric'],
            'tanggal_upload' => ['required', 'string'],
            'path_hki' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $tanggal_upload = $request->tanggal_upload;
        date('Y-m-d H:i:s');

        $path_Hki = $request->file('path_hki');
        $filename = $path_Hki->getClientOriginalName();
        $path_Hki = $path_Hki->storeAs('laporan-hki', str_replace(" ", "-", $filename));

        Hki::create([
            'proposal_id' => $request->proposal_id,
            'jenis_hki_id' => $request->jenis,
            'path_hki' => $path_Hki,
            'tanggal_upload' => $tanggal_upload,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan Hki berhasil ditambahkan', 'success');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $hki = Hki::find($id);
        // dd($hki);
        $rules = [
            'proposal_id' => ['required', 'numeric'],
            'jenis' => ['required', 'numeric'],
            'tanggal_upload' => ['required', 'string'],
        ];

        if ($hki->path_hki != $request->path_hki) {
            $rules['path_hki'] = ['required', 'file', 'mimes:pdf', 'max:2048'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $tanggal_upload = $request->tanggal_upload;
        date('Y-m-d H:i:s');

        $path_Hki = $request->file('path_hki');
        if ($path_Hki != NULL) {
            $fileName = $path_Hki->getClientOriginalName();
            $cekFileName = Hki::where('path_hki', 'laporan-Hki/' . str_replace(" ", "-", $fileName))->get();
            if (count($cekFileName) != 0) {
                Alert::toast('File Sudah Ada!', 'error');
                return back()->withInput();
            }
            $path_Hki = $path_Hki->storeAs('laporan-Hki', str_replace(" ", "-", $fileName));
        } else {
            $path_Hki = $hki->path_hki;
        }

        Hki::findOrFail($id)->update([
            'proposal_id' => $request->proposal_id,
            'jenis_hki_id' => $request->jenis,
            'path_hki' => $path_Hki,
            'tanggal_upload' => $tanggal_upload,
            'user_id' => Auth::user()->id
        ]);

        Alert::success('Laporan Hki berhasil diubah', 'success');
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
        $hki = Hki::find($id);
        Storage::delete($hki->path_hki);

        Hki::findOrFail($id)->delete();
        Alert::success('Laporan Hki berhasil dihapus', 'success');
        return back();
    }
}
