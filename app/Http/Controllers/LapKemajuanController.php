<?php

namespace App\Http\Controllers;

use App\Models\LapKemajuan;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class LapKemajuanController extends Controller
{

    public function index()
    {
        $title = "Laporan Kemajuan";
        $proposal = Proposal::all();

        //ambil tahun sekarang
        $getYear = date("Y");
        if (Auth::user()->role_id == 2) {
            $proposal = Proposal::where('user_id', Auth::user()->id)->whereYear('tanggal_usul', $getYear)->get();
            $kemajuans = LapKemajuan::where('user_id', Auth::user()->id)->get();
        }


        return view(
            'proposal.kemajuan',
            [
                'title' => $title,
                'proposal' => $proposal,
                'kemajuans' => $kemajuans,
            ]
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'proposal_id' => ['required', 'numeric', 'unique:lap_kemajuans'],
            'tanggal_upload' => ['required', 'string'],
            'path_kemajuan' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        //ubah format inputan tanggal upload string ke datetime
        $tanggal_upload = $request->tanggal_upload;
        date('Y-m-d H:i:s');

        //upload file dengan original file name
        $path_kemajuan = $request->file('path_kemajuan');
        $filename = $path_kemajuan->getClientOriginalName();
        $path_kemajuan = $path_kemajuan->storeAs('laporan-kemajuan', str_replace(" ", "-", $filename));

        LapKemajuan::create([
            'proposal_id' => $request->proposal_id,
            'tanggal_upload' => $tanggal_upload,
            'path_kemajuan' => $path_kemajuan,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan Kemajuan berhasil ditambahkan', 'success');
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
        //
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
