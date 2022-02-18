<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Publikasi;
use Illuminate\Support\Facades\Storage;

class PublikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Daftar Usulan Publikasi";
        $publikasi = Publikasi::all();
        $proposal = Proposal::all();

        return view('proposal.publikasi', [
            'title' => $title,
            'proposal' => $proposal,
            'publikasi' => $publikasi
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
            'judul' => ['required', 'string'],
            'nama' => ['required', 'string'],
            'jenis' => ['required', 'string'],
            'path_publikasi' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'tanggal_upload' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }
        // $nama = $request->nama;
        // $gabungNama = implode(" - ", $nama);
        // dd($gabungNama);

        $tanggal_upload = $request->tanggal_upload;
        date('Y-m-d H:i:s');

        $path_publikasi = $request->file('path_publikasi');
        $filename = $path_publikasi->getClientOriginalName();
        $path_publikasi = $path_publikasi->storeAs('laporan-publikasi', str_replace(" ", "-", $filename));



        Publikasi::create([
            'proposal_id' => $request->proposal_id,
            'judul_jurnal' => $request->judul,
            'nama_artikel' => $request->nama,
            'jenis_jurnal' => $request->jenis,
            'path_jurnal' => $path_publikasi,
            'tanggal_upload' => $tanggal_upload,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan publikasi berhasil ditambahkan', 'success');
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
        $publikasi = Publikasi::find($id);

        // dd($publikasi);

        $rules = [
            'proposal_id' => ['required', 'numeric'],
            'judul' => ['required', 'string'],
            'nama' => ['required', 'string'],
            'jenis' => ['required', 'string'],
            'tanggal_upload' => ['required', 'string'],
        ];

        if ($publikasi->path_jurnal != $request->path_jurnal) {
            $rules['path_jurnal'] = ['required', 'file', 'mimes:pdf', 'max:2048'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $tanggal_upload = $request->tanggal_upload;
        date('Y-m-d H:i:s');

        $path_publikasi = $request->file('path_jurnal');
        if ($path_publikasi != NULL) {
            $fileName = $path_publikasi->getClientOriginalName();
            $cekFileName = publikasi::where('path_jurnal', 'laporan-publikasi/' . str_replace(" ", "-", $fileName))->get();
            if (count($cekFileName) != 0) {
                Alert::toast('File Sudah Ada!', 'error');
                return back()->withInput();
            }
            $path_publikasi = $path_publikasi->storeAs('laporan-publikasi', str_replace(" ", "-", $fileName));
        } else {
            $path_publikasi = $publikasi->path_jurnal;
        }

        publikasi::findOrFail($id)->update([
            'proposal_id' => $request->proposal_id,
            'judul_jurnal' => $request->judul,
            'nama_artikel' => $request->nama,
            'jenis_jurnal' => $request->jenis,
            'path_jurnal' => $path_publikasi,
            'tanggal_upload' => $tanggal_upload,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan publikasi berhasil diubah', 'success');
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
        $publikasi = Publikasi::find($id);
        Storage::delete($publikasi->path_jurnal);
        Publikasi::findOrFail($id)->delete();
        Alert::success('Laporan Publikasi berhasil dihapus', 'success');
        return back();
    }
}
