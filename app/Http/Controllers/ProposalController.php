<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Dosen;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ProposalController extends Controller
{

    public function index()
    {
        $title = "Daftar Usulan Proposal";
        $dosen = Dosen::all();
        $user = Auth::user()->nidn;
        $pengusul = Dosen::where('nidn', $user)->get();
        $usulan = Proposal::all();
        if (Auth::user()->role_id == 2) {
            $usulan = Proposal::where('user_id', Auth::user()->id)->get();
        }
        // foreach ($pengusul as $pgs) {
        //     echo $pgs->proposal;
        //     echo '<br>';
        //     foreach ($pgs->proposal as $pps) {
        //         echo $pps->judul;
        //         echo '<br>';
        //         echo $pps->pivot->isLeader;
        //         echo '<br>';
        //         foreach ($pps->dosen as $leader) {
        //             if ($leader->pivot->isLeader == true) {
        //                 echo $leader;
        //                 echo '<br>';
        //             }
        //         }
        //     }
        // }
        return view('proposal.usulan', [
            'title' => $title,
            'dosen' => $dosen,
            'pengusul' => $pengusul,
            'usulan' => $usulan,
        ]);

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nidn_pengusul' => ['required', 'numeric'],
            'judul' => ['required', 'string', 'unique:proposals'],
            'tanggal_usul' => ['required'],
            'status' => ['required', 'string'],
            'path_proposal' => ['required', 'mimes:pdf', 'file', 'max:2048'],
            'nidn_anggota' => ['required', 'array', 'min:2', 'max:2'],
            'nidn_anggota.*' => ['required', 'string', 'digits:10'],
        ]);

        // dd($request->all());
        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $user_id = Auth::user()->id;
        $nidn_pengusul = $request->nidn_pengusul;

        //ambil tahun sekarang
        $getYear = date("Y");

        //ambil data nidn anggota
        $agt = $request->nidn_anggota;

        //ambil original filename dari file yang diupload
        $path_proposal = $request->file('path_proposal');
        $filename = $path_proposal->getClientOriginalName();

        //query file proposal sudah ada atau tidak
        $cekfilename = Proposal::where('path_proposal', 'proposal/' . str_replace(" ", "-", $filename))->get();
        //query cek pengusul sudah mengusulkan tahun ini atau belum
        $getnidnPengusul = Anggota::where('nidn', $nidn_pengusul)->whereYear('created_at', $getYear)->get();
        //query cek anggota 1
        $getAgt1 = Anggota::where('nidn', $agt[0])->whereYear('created_at', $getYear)->get();
        //query cek anggota 2
        $getAgt2 = Anggota::where('nidn', $agt[1])->whereYear('created_at', $getYear)->get();

        if (count($cekfilename) != 0) {
            Alert::toast('Proposal sudah terdaftar', 'error');
            return back()->withInput();
        }
        if (count($getnidnPengusul) >= 1) {
            Alert::toast('Pengusulan hanya dapat dilakukan sekali dalam satu periode', 'error');
            return back()->withInput();
        }
        if (count($getAgt1) >= 2) {
            Alert::toast($agt[0] . ' sudah terdaftar 2 kali di periode ini', 'error');
            return back()->withInput();
        }
        if (count($getAgt2) >= 2) {
            Alert::toast($agt[1] . ' sudah terdaftar anggota 2 kali di periode ini', 'error');
            return back()->withInput();
        }
        $path_proposal = $path_proposal->storeAs('proposal', str_replace(" ", "-", $filename));

        //ambil dan ubah format tanggal usul
        $date = $request->tanggal_usul;
        $date = date('Y-m-d H:i:s');

        $id_proposal = Proposal::create([
            'judul' => $request->judul,
            'tanggal_usul' => $date,
            'path_proposal' => $path_proposal,
            'user_id' => $user_id,
            'status' => $request->status,
        ]);

        Anggota::create([
            'proposal_id' => $id_proposal->id,
            'nidn' => strlen($nidn_pengusul) <= 9 ? str_pad($nidn_pengusul, 10, "0", STR_PAD_LEFT) : $nidn_pengusul,
            'isLeader' => 1,
        ]);
        Anggota::create([
            'proposal_id' => $id_proposal->id,
            'nidn' => strlen($agt[0]) <= 9 ? str_pad($agt[0], 10, "0", STR_PAD_LEFT) : $agt[0],
            'isLeader' => 0,
        ]);
        Anggota::create([
            'proposal_id' => $id_proposal->id,
            'nidn' => strlen($agt[1]) <= 9 ? str_pad($agt[1], 10, "0", STR_PAD_LEFT) : $agt[1],
            'isLeader' => 0,
        ]);

        Alert::success('Proposal berhasil ditambahkan', 'success');
        return back();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
