<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Proposal;
use App\Models\TeknologiTepatGuna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class TeknologiTepatGunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Daftar Usulan TTG";
        $proposal = Proposal::all();
        $dosen = Dosen::all();
        $ttg = TeknologiTepatGuna::all();

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
            $ttg = TeknologiTepatGuna::whereIn('proposal_id', $proposal_id)->get();
        }

        return view('proposal.ttg', [
            'title' => $title,
            'proposal' => $proposal,
            'dosen' => $dosen,
            'ttg' => $ttg,
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
        // return $request->all();

        $validator = Validator::make($request->all(), [
            'proposal_id' => ['required', 'numeric'],
            'bidang' => ['required', 'string'],
            'path_ttg' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'tanggal_upload' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $date = strtotime($request->tanggal_upload);
        $date = date('Y-m-d', $date);

        $path_ttg = $request->file('path_ttg');
        $filename = $path_ttg->getClientOriginalName();
        $path_ttg = $path_ttg->storeAs('laporan-ttg', str_replace(" ", "-", $filename));

        TeknologiTepatGuna::create([
            'proposal_id' => $request->proposal_id,
            'bidang' => $request->bidang,
            'path_ttg' => $path_ttg,
            'tanggal_upload' => $date,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan TTG berhasil ditambahkan', 'success');
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

        $ttg = TeknologiTepatGuna::find($id);

        // dd($ttg);

        $rules = [
            'proposal_id' => ['required', 'numeric'],
            'bidang' => ['required', 'string'],
            'tanggal_upload' => ['required', 'string'],
        ];

        if ($ttg->path_ttg != $request->path_ttg) {
            $rules['path_ttg'] = ['required', 'file', 'mimes:pdf', 'max:2048'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $date = strtotime($request->tanggal_upload);
        $date = date('Y-m-d', $date);

        $path_ttg = $request->file('path_ttg');
        if ($path_ttg != NULL) {
            $fileName = $path_ttg->getClientOriginalName();
            $cekFileName = TeknologiTepatGuna::where('path_ttg', 'laporan-ttg/' . str_replace(" ", "-", $fileName))->get();
            if (count($cekFileName) != 0) {
                Alert::toast('File Sudah Ada!', 'error');
                return back()->withInput();
            }
            $path_ttg = $path_ttg->storeAs('laporan-ttg', str_replace(" ", "-", $fileName));
        } else {
            $path_ttg = $ttg->path_ttg;
        }

        TeknologiTepatGuna::findOrFail($id)->update([
            'proposal_id' => $request->proposal_id,
            'bidang' => $request->bidang,
            'path_ttg' => $path_ttg,
            'tanggal_upload' => $date,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan TTG berhasil diubah', 'success');
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
        $ttg = TeknologiTepatGuna::find($id);
        Storage::delete($ttg->path_ttg);
        TeknologiTepatGuna::findOrFail($id)->delete();
        Alert::success('Laporan TTG berhasil dihapus', 'success');
        return back();
    }
}
