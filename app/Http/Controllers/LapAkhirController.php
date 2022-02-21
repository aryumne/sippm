<?php

namespace App\Http\Controllers;

use App\Models\LapAkhir;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class LapAkhirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Laporan Akhir";
        $proposal = Proposal::all();
        $akhirs = LapAkhir::all();

        //ambil tahun sekarang
        $getYear = date("Y");
        if (Auth::user()->role_id == 2) {
            $proposal = Proposal::where('user_id', Auth::user()->id)->whereYear('tanggal_usul', $getYear)->get();
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
            $proposal = Proposal::whereIn('id', $proposal_id)->whereYear('tanggal_usul', $getYear)->get();
            //ambil laporan akhir yang pengusul upload
            $akhirs = LapAkhir::whereIn('proposal_id', $proposal_id)->get();
        }

        return view('proposal.akhir',
            [
                'title' => $title,
                'proposal' => $proposal,
                'akhirs' => $akhirs,
            ]);

    }

    public function store(Request $request)
    {
        if (!Gate::allows('upload_laporan_akhir')) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'proposal_id' => ['required', 'numeric', 'unique:lap_akhirs'],
            'tanggal_upload' => ['required'],
            'path_akhir' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'path_keuangan' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ], [
            'proposal_id.unique' => 'Laporan akhir dari proposal ini sudah ditambahkan',
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        //ubah format inputan tanggal upload string ke datetime
        $tanggal_upload = strtotime($request->tanggal_upload);
        $date = date('Y-m-d', $tanggal_upload);

        //upload file laporan akhir dengan original filename
        $path_akhir = $request->file('path_akhir');
        $filenameAkhir = $path_akhir->getClientOriginalName();
        $cekfilenameAkhir = LapAkhir::where('path_akhir', 'laporan-akhir/' . str_replace(" ", "-", $filenameAkhir))->get();
        if (count($cekfilenameAkhir) != 0) {
            Alert::toast('File laporan akhir ini sudah ada', 'error');
            return back()->withInput();
        }
        //upload file laporan keuangan dengan original filename
        $path_keuangan = $request->file('path_keuangan');
        $filenameKeuangan = $path_keuangan->getClientOriginalName();
        $cekfilenameKeuangan = LapAkhir::where('path_keuangan', 'laporan-akhir/' . str_replace(" ", "-", $filenameKeuangan))->get();
        if (count($cekfilenameKeuangan) != 0) {
            Alert::toast('File laporan akhir ini sudah ada', 'error');
            return back()->withInput();
        }

        $path_akhir = $path_akhir->storeAs('laporan-akhir', str_replace(" ", "-", $filenameAkhir));
        $path_keuangan = $path_keuangan->storeAs('laporan-keuangan', str_replace(" ", "-", $filenameKeuangan));

        LapAkhir::create([
            'proposal_id' => $request->proposal_id,
            'tanggal_upload' => $date,
            'path_akhir' => $path_akhir,
            'path_keuangan' => $path_keuangan,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan Akhir berhasil ditambahkan', 'success');
        return back();

    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('upload_laporan_akhir')) {
            abort(403);
        }

        if (Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return redirect()->intended('login');
        }
        $akhir = LapAkhir::find($id);
        if (Auth::user()->role_id == 1) {
            $rules = [
                'tanggal_upload' => ['required'],
            ];
        }

        if ($request->path_akhir != null) {
            $rules['path_akhir'] = ['required', 'mimes:pdf', 'file', 'max:2048'];
        }
        if ($request->path_keuangan != null) {
            $rules['path_keuangan'] = ['required', 'mimes:pdf', 'file', 'max:2048'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        if (Auth::user()->role_id == 1) {
            //ambil dan ubah format tanggal usul
            $date = $request->tanggal_upload;
        }

        if (Auth::user()->role_id == 2) {
            //ambil dan ubah format tanggal usul
            $date = $akhir->tanggal_upload;
        }

        //ambil original filename dari file yang diupload
        //cek filename laporan akhir sudah ada atau belum
        //kalau ada dan input file juga kosong maka save kembali file yang sebelumnya
        $path_akhir = $request->file('path_akhir');
        if ($path_akhir != null) {
            $filename = $path_akhir->getClientOriginalName();
            //query file proposal sudah ada atau tidak
            $cekfilename = LapAkhir::where('path_akhir', 'laporan-akhir/' . str_replace(" ", "-", $filename))->get();
            if (count($cekfilename) != 0) {
                Alert::toast('File laporan akhir sudah ada', 'error');
                return back()->withInput();
            }
            $path_akhir = $path_akhir->storeAs('laporan-akhir', str_replace(" ", "-", $filename));
        } else {
            $path_akhir = $akhir->path_akhir;
        }

        //cek filename laporan keuangan sudah ada atau belum
        //kalau ada dan input file juga kosong maka save kembali file yang sebelumnya
        $path_keuangan = $request->file('path_keuangan');
        if ($path_keuangan != null) {
            $filenameKeuangan = $path_keuangan->getClientOriginalName();
            //query file proposal sudah ada atau tidak
            $cekfilenameKeuangan = LapAkhir::where('path_keuangan', 'laporan-akhir/' . str_replace(" ", "-", $filenameKeuangan))->get();
            if (count($cekfilenameKeuangan) != 0) {
                Alert::toast('File laporan keuangan sudah ada', 'error');
                return back()->withInput();
            }
            $path_keuangan = $path_keuangan->storeAs('laporan-akhir', str_replace(" ", "-", $filenameKeuangan));
        } else {
            $path_keuangan = $akhir->path_keuangan;
        }

        LapAkhir::findOrFail($id)->update([
            'proposal_id' => $request->proposal_id,
            'tanggal_upload' => $date,
            'path_akhir' => $path_akhir,
            'path_keuangan' => $path_keuangan,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan akhir berhasil diubah', 'success');
        return back();

    }

}
