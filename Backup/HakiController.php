<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Haki;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Null_;

class HakiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Daftar Usulan Haki";
        $haki = Haki::all();
        $proposal = Proposal::all();

        return view('proposal.haki', [
            'title' => $title,
            'proposal' => $proposal,
            'haki' => $haki
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
            'jenis' => ['required', 'string'],
            'tanggal_upload' => ['required', 'string'],
            'path_haki' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $tanggal_upload = $request->tanggal_upload;
        date('Y-m-d H:i:s');

        $path_haki = $request->file('path_haki');
        $filename = $path_haki->getClientOriginalName();
        $path_haki = $path_haki->storeAs('laporan-haki', str_replace(" ", "-", $filename));

        Haki::create([
            'proposal_id' => $request->proposal_id,
            'jenis_haki' => $request->jenis,
            'path_haki' => $path_haki,
            'tanggal_upload' => $tanggal_upload,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan Haki berhasil ditambahkan', 'success');
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
        $haki = Haki::find($id);
        $rules = [
            'proposal_id' => ['required', 'numeric'],
            'jenis' => ['required', 'string'],
            'tanggal_upload' => ['required', 'string'],
        ];

        if ($haki->path_haki != $request->path_haki) {
            $rules['path_haki'] = ['required', 'file', 'mimes:pdf', 'max:2048'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $tanggal_upload = $request->tanggal_upload;
        date('Y-m-d H:i:s');

        $path_haki = $request->file('path_haki');
        if ($path_haki != NULL) {
            $fileName = $path_haki->getClientOriginalName();
            $cekFileName = Haki::where('path_haki', 'laporan-haki/' . str_replace(" ", "-", $fileName))->get();
            if (count($cekFileName) != 0) {
                Alert::toast('File Sudah Ada!', 'error');
                return back()->withInput();
            }
            $path_haki = $path_haki->storeAs('laporan-haki', str_replace(" ", "-", $fileName));
        } else {
            $path_haki = $haki->path_haki;
        }

        Haki::findOrFail($id)->update([
            'proposal_id' => $request->proposal_id,
            'jenis_haki' => $request->jenis,
            'path_haki' => $path_haki,
            'tanggal_upload' => $tanggal_upload,
            'user_id' => Auth::user()->id
        ]);

        Alert::success('Laporan Haki berhasil diubah', 'success');
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
        $haki = Haki::find($id);
        Storage::delete($haki->path_haki);

        Haki::findOrFail($id)->delete();
        Alert::success('Laporan Haki berhasil dihapus', 'success');
        return back();
    }
}
