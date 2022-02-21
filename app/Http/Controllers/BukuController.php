<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Dosen;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Daftar Usulan Buku";
        $proposal = Proposal::all();
        $dosen = Dosen::all();
        $buku = Buku::all();

        if (Auth::user()->role_id == 2) {
            $listProposal = Proposal::all();
            //buat collection
            $collectionProposalId = collect([]);
            foreach ($listProposal as $dsn) {
                foreach ($dsn->dosen as $dosen) {
                    if ($dosen->pivot->isLeader == 1 && $dosen->pivot->nidn == Auth::user()->nidn) {
                        //simpan id proposal yang pengusul upload ke collcection
                        $collectionProposalId->push($dsn->id);
                    }
                }
            }
            //dapatkan semua isi collection
            $proposal_id = $collectionProposalId->all();
            //ambil proposal yang pengusul upload di tahun ini
            $proposal = Proposal::whereIn('id', $proposal_id)->get();
            //ambil laporan kemajuan yang pengusul upload
            $buku = Buku::whereIn('proposal_id', $proposal_id)->get();
        }

        return view('proposal.buku', [
            'title' => $title,
            'proposal' => $proposal,
            'dosen' => $dosen,
            'buku' => $buku,
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
            'penerbit' => ['required', 'string'],
            'path_buku' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'tanggal_upload' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $date = strtotime($request->tanggal_upload);
        $date = date('Y-m-d', $date);

        $path_buku = $request->file('path_buku');
        $filename = $path_buku->getClientOriginalName();
        $path_buku = $path_buku->storeAs('laporan-buku', str_replace(" ", "-", $filename));

        Buku::create([
            'proposal_id' => $request->proposal_id,
            'judul_buku' => $request->judul,
            'penerbit' => $request->penerbit,
            'path_buku' => $path_buku,
            'tanggal_upload' => $date,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan Buku berhasil ditambahkan', 'success');
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

        $buku = Buku::find($id);

        // dd($buku);

        $rules = [
            'proposal_id' => ['required', 'numeric'],
            'judul' => ['required', 'string'],
            'penerbit' => ['required', 'string'],
            'tanggal_upload' => ['required', 'string'],
        ];

        if ($buku->path_buku != $request->path_buku) {
            $rules['path_buku'] = ['required', 'file', 'mimes:pdf', 'max:2048'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $date = strtotime($request->tanggal_upload);
        $date = date('Y-m-d', $date);

        $path_buku = $request->file('path_buku');
        if ($path_buku != NULL) {
            $fileName = $path_buku->getClientOriginalName();
            $cekFileName = Buku::where('path_buku', 'laporan-buku/' . str_replace(" ", "-", $fileName))->get();
            if (count($cekFileName) != 0) {
                Alert::toast('File Sudah Ada!', 'error');
                return back()->withInput();
            }
            $path_buku = $path_buku->storeAs('laporan-buku', str_replace(" ", "-", $fileName));
        } else {
            $path_buku = $buku->path_buku;
        }

        Buku::findOrFail($id)->update([
            'proposal_id' => $request->proposal_id,
            'judul_buku' => $request->judul,
            'penerbit' => $request->penerbit,
            'path_buku' => $path_buku,
            'tanggal_upload' => $date,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan Buku berhasil diubah', 'success');
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
        $buku = Buku::find($id);
        Storage::delete($buku->path_buku);
        Buku::findOrFail($id)->delete();
        Alert::success('Laporan Buku berhasil dihapus', 'success');
        return back();
    }
}
