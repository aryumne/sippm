<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Audit;
use App\Models\Dosen;
use App\Models\Faculty;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ProposalController extends Controller
{

    public function index()
    {
        $title = "Daftar Usulan Proposal";
        $dosen = Dosen::all();
        $user = Auth::user()->nidn;
        $faculties = Faculty::all();

        //using scope collection dalam model Proposal untuk query filter
        $usulan = Proposal::Filter(request(['faculty_id', 'tahun_usul']))->get();

        if (Auth::user()->role_id == 2) {
            $usulan = Dosen::where('nidn', $user)->get();
        }

        // manual filter without scope
        // if (request('tahun_usul') && request('faculty_id')) {
        //     $fakultas = Faculty::find(request('faculty_id'));
        //     $usulan = $fakultas->proposal()->whereYear('tanggal_usul', request('tahun_usul'))->get();
        //     // dd($usulan);
        // } else if (!request('tahun_usul') && request('faculty_id')) {
        //     $fakultas = Faculty::find(request('faculty_id'));
        //     $usulan = $fakultas->proposal;
        // } else if (request('tahun_usul') && !request('faculty_id')) {
        //     $usulan = Proposal::whereYear('tanggal_usul', request('tahun_usul'))->get();
        // }

        return view('proposal.usulan', [
            'title' => $title,
            'dosen' => $dosen,
            'usulan' => $usulan,
            'faculties' => $faculties,
        ]);
    }

    public function store(Request $request)
    {
        if (!Gate::allows('pengusulan_proposal')) {
            abort(403);
        }
        $validator = Validator::make($request->all(), [
            'nidn_pengusul' => ['required', 'numeric'],
            'judul' => ['required', 'string', 'unique:proposals'],
            'tanggal_usul' => ['required'],
            'status' => ['required'],
            'path_proposal' => ['required', 'mimes:pdf', 'file', 'max:2048'],
            'nidn_anggota' => ['required', 'array', 'min:2', 'max:2'],
            'nidn_anggota.*' => ['required', 'string', 'digits:10'],
        ], [
            'judul.unique' => 'Judul proposal ini sudah ada',
            'path_proposal.file' => 'Tipe file yang diupload harus .pdf',
            'path_proposal.max' => 'Ukuran maksimal file 2Mb',
        ]);

        // dd($request->all());
        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $user_id = Auth::user()->id;
        $nidn_pengusul = $request->nidn_pengusul;
        $pengusul = Dosen::find($nidn_pengusul);
        //ambil tahun sekarang
        $getYear = date("Y");

        //ambil data nidn anggota
        $agt = $request->nidn_anggota;

        //cek kesamaan nidn pengusul dan anggota yang dipilih
        foreach ($agt as $nidn_anggota) {
            if ($nidn_anggota == $nidn_pengusul) {
                Alert::toast('1 Tim harus terdiri dari 3 orang yang berbeda', 'error');
                return back()->withErrors($validator)->withInput();
            }
        }

        //ambil original filename dari file yang diupload
        $path_proposal = $request->file('path_proposal');
        $filename = $path_proposal->getClientOriginalName();

        //query file proposal sudah ada atau tidak
        $cekfilename = Proposal::where('path_proposal', 'proposal/' . str_replace(" ", "-", $filename))->get();
        if (count($cekfilename) != 0) {
            Alert::toast('File proposal sudah ada', 'error');
            return back()->withInput();
        }

        //query cek pengusul sudah mengusulkan tahun ini atau belum
        if (Auth::user()->role_id == 2) {
            $getuserIdPengusul = Proposal::where('user_id', $user_id)->whereYear('created_at', $getYear)->get();
            //query cek anggota 1
            $getAgt1 = Anggota::where('nidn', $agt[0])->whereYear('created_at', $getYear)->get();
            //query cek anggota 2
            $getAgt2 = Anggota::where('nidn', $agt[1])->whereYear('created_at', $getYear)->get();

            if (count($getuserIdPengusul) >= 1) {
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
        }
        $path_proposal = $path_proposal->storeAs('proposal', str_replace(" ", "-", $filename));

        //ambil dan ubah format tanggal usul
        $date = strtotime($request->tanggal_usul);
        $date = date('Y-m-d', $date);

        $id_proposal = Proposal::create([
            'judul' => $request->judul,
            'tanggal_usul' => $date,
            'path_proposal' => $path_proposal,
            'user_id' => $user_id,
            'status' => $request->status,
            'prodi_id' => $pengusul->prodi_id,
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
        if (Auth::user()->role_id != 1) {
            return redirect()->intended('/');
        }
        $title = "Detail Proposal";
        $proposal = Proposal::find($id);
        $audit_ids = collect([]);
        foreach ($proposal->reviewer as $reviewer) {
            $audit_ids->push($reviewer->pivot->id);
        }
        $audits = Audit::whereIn('id', $audit_ids)->get();
        // foreach ($audits as $a) {
        //     echo 'Hasil = ' . ($a->hasil == null ? 'Kosong' : $a->hasil) . '<br>';
        //     echo 'Reviewer = ' . $a->user->dosen->nama . '<br>';
        //     echo 'Proposal = ' . $a->proposal . '<br>';
        // }

        return view('proposal.showProposal',
            [
                'title' => $title,
                'proposal' => $proposal,
                'audits' => $audits,
            ]);
    }

    public function edit($id)
    {
        if (!Gate::allows('pengusulan_proposal')) {
            abort(403);
        }

        $isLeader = Anggota::where('proposal_id', $id)->where('isLeader', 1)->first();
        if (Auth::user()->role_id != 1) {
            if (Auth::user()->nidn != $isLeader->nidn) {
                return redirect()->intended('login');
            }
        }
        $title = "Edit Data Proposal";
        $proposal = Proposal::find($id);
        $dosen = Dosen::all();

        return view('proposal.editProposal', [
            'title' => $title,
            'proposal' => $proposal,
            'dosen' => $dosen,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('pengusulan_proposal')) {
            abort(403);
        }

        $proposal = Proposal::find($id);
        $rules = [
            'nidn_anggota' => ['required', 'array', 'min:2', 'max:2'],
            'nidn_anggota.*' => ['required', 'string', 'digits:10'],
        ];
        if (Auth::user()->role_id == 1) {
            $rules['tanggal_usul'] = ['required'];
            $rules['nidn_pengusul'] = ['required'];
            $rules['status'] = ['required'];
        }
        if ($request->judul != $proposal->judul) {
            $rules['judul'] = ['required', 'string', 'unique:proposals'];
        }
        if ($request->path_proposal != null) {
            $rules['path_proposal'] = ['required', 'mimes:pdf', 'file', 'max:2048'];
        }

        // dd($request->all());
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        if (Auth::user()->role_id == 1) {
            //ambil dan ubah format tanggal usul
            $date = $request->tanggal_usul;
            $status = $request->status;
            $nidn_pengusul = $request->nidn_pengusul;
        }

        if (Auth::user()->role_id == 2) {
            //ambil dan ubah format tanggal usul
            $date = $proposal->tanggal_usul;
            $status = $proposal->status;
            $nidn_pengusul = Auth::user()->nidn;
        }
        //cek nidn pengusul dan anggota ada yang sama atau tidak
        foreach ($request->nidn_anggota as $agt) {
            if ($agt == $nidn_pengusul) {
                Alert::toast('1 Tim harus terdiri dari 3 orang yang berbeda', 'error');
                return back()->withErrors($validator)->withInput();
            }
        }
        //ambil data pengusul
        $pengusul = Dosen::find($nidn_pengusul);

        //ambil tahun dari tanggal pengusulan
        $getYear = date("Y", strtotime($date));
        $agt = $request->nidn_anggota;

        //query cek pengusul sudah mengusulkan tahun ini atau belum
        $getnidnPengusul = Anggota::where('nidn', $nidn_pengusul)->whereYear('created_at', $getYear)->where('isLeader', 1)->get();
        // dd(count($getnidnPengusul));
        //query cek anggota 1
        $getAgt1 = Anggota::where('nidn', $agt[0])->whereYear('created_at', $getYear)->get();
        //query cek anggota 2
        $getAgt2 = Anggota::where('nidn', $agt[1])->whereYear('created_at', $getYear)->get();

        if (count($getnidnPengusul) > 1) {
            Alert::toast('Pengusulan hanya dapat dilakukan sekali dalam satu periode', 'error');
            return back()->withInput();
        }
        if (count($getAgt1) > 2) {
            Alert::toast($agt[0] . ' sudah terdaftar 2 kali di periode ini', 'error');
            return back()->withInput();
        }
        if (count($getAgt2) > 2) {
            Alert::toast($agt[1] . ' sudah terdaftar anggota 2 kali di periode ini', 'error');
            return back()->withInput();
        }

        //ambil original filename dari file yang diupload
        $path_proposal = $request->file('path_proposal');
        if ($path_proposal != null) {
            $filename = $path_proposal->getClientOriginalName();
            //query file proposal sudah ada atau tidak
            $cekfilename = Proposal::where('path_proposal', 'proposal/' . str_replace(" ", "-", $filename))->get();
            // if (count($cekfilename) != 0) {
            //     Alert::toast('File proposal sudah ada', 'error');
            //     return back()->withInput();
            // }
            $path_proposal = $path_proposal->storeAs('proposal', str_replace(" ", "-", $filename));
        } else {
            $path_proposal = $proposal->path_proposal;
        }

        Proposal::findOrFail($id)->update([
            'judul' => $request->judul,
            'tanggal_usul' => $date,
            'path_proposal' => $path_proposal,
            'user_id' => Auth::user()->id,
            'status' => $status,
            'prodi_id' => $pengusul->prodi_id,
        ]);

        //delete tim pengusul proposal ini di database anggota
        foreach ($proposal->dosen as $dsn) {
            Anggota::where('nidn', $dsn->pivot->nidn)->where('proposal_id', $id)->delete();
        }

        Anggota::create([
            'proposal_id' => $id,
            'nidn' => str_pad($nidn_pengusul, 10, "0", STR_PAD_LEFT),
            'isLeader' => 1,
        ]);

        foreach ($request->nidn_anggota as $agt) {
            Anggota::create([
                'proposal_id' => $id,
                'nidn' => str_pad($agt, 10, "0", STR_PAD_LEFT),
                'isLeader' => 0,
            ]);
        }
        Alert::success('Data berhasil diubah', 'success');
        return Auth::user()->id == 1 ? redirect()->route('usulan.index') : redirect()->route('usulan.show', $id);
    }

}
