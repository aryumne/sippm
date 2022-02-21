<?php

namespace App\Http\Controllers;

use App\Models\LapKemajuan;
use App\Models\Monev;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class LapKemajuanController extends Controller
{

    public function index()
    {
        $title = "Laporan Kemajuan";
        $proposal = Proposal::all();
        $kemajuans = LapKemajuan::all();

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
            //ambil laporan kemajuan yang pengusul upload
            $kemajuans = LapKemajuan::whereIn('proposal_id', $proposal_id)->get();
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
        if (!Gate::allows('upload_laporan_kemajuan')) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'proposal_id' => ['required', 'numeric', 'unique:lap_kemajuans'],
            'tanggal_upload' => ['required'],
            'path_kemajuan' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ], [
            'proposal_id.unique' => 'Laporan kemajuan dari proposal ini sudah ditambahkan',
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        //ubah format inputan tanggal upload string ke datetime
        $tanggal_upload = strtotime($request->tanggal_upload);
        $date = date('Y-m-d', $tanggal_upload);

        //upload file dengan original file name
        $path_kemajuan = $request->file('path_kemajuan');
        $filename = $path_kemajuan->getClientOriginalName();
        $cekfilename = LapKemajuan::where('path_kemajuan', 'laporan-kemajuan/' . str_replace(" ", "-", $filename))->get();
        if (count($cekfilename) != 0) {
            Alert::toast('File laporan sudah ada', 'error');
            return back()->withInput();
        }

        $path_kemajuan = $path_kemajuan->storeAs('laporan-kemajuan', str_replace(" ", "-", $filename));

        LapKemajuan::create([
            'proposal_id' => $request->proposal_id,
            'tanggal_upload' => $date,
            'path_kemajuan' => $path_kemajuan,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan Kemajuan berhasil ditambahkan', 'success');
        return back();
    }

    public function show($id)
    {
        if (Auth::user()->role_id != 1) {
            return redirect()->intended('login');
        }

        $title = "Detail Laporan Kemajuan";
        $kemajuan = LapKemajuan::find($id);
        $monev = Monev::where('lap_kemajuan_id', $id)->first();
        return view('proposal.showKemajuan', [
            'title' => $title,
            'kemajuan' => $kemajuan,
            'monev' => $monev,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('upload_laporan_kemajuan')) {
            abort(403);
        }

        if (Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return redirect()->intended('login');
        }
        $kemajuan = LapKemajuan::find($id);
        if (Auth::user()->role_id == 1) {
            $rules = [
                'tanggal_upload' => ['required'],
            ];

        }

        if ($request->path_kemajuan != null) {
            $rules['path_kemajuan'] = ['required', 'mimes:pdf', 'file', 'max:2048'];
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
            $date = $kemajuan->tanggal_upload;
        }

        //ambil original filename dari file yang diupload
        $path_kemajuan = $request->file('path_kemajuan');
        if ($path_kemajuan != null) {
            $filename = $path_kemajuan->getClientOriginalName();
            //query file proposal sudah ada atau tidak
            $cekfilename = LapKemajuan::where('path_kemajuan', 'laporan-kemajuan/' . str_replace(" ", "-", $filename))->get();
            if (count($cekfilename) != 0) {
                Alert::toast('File laporan sudah ada', 'error');
                return back()->withInput();
            }
            $path_kemajuan = $path_kemajuan->storeAs('laporan-kemajuan', str_replace(" ", "-", $filename));
        } else {
            $path_kemajuan = $kemajuan->path_kemajuan;
        }

        LapKemajuan::findOrFail($id)->update([
            'proposal_id' => $request->proposal_id,
            'tanggal_upload' => $date,
            'path_kemajuan' => $path_kemajuan,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan Kemajuan berhasil diubah', 'success');
        return back();

    }

}
