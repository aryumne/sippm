<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Hki;
use App\Models\Jenis_hki;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

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
        $dosen = Dosen::all();

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
            $hki = Hki::whereIn('proposal_id', $proposal_id)->get();
        }

        // dd($dosen);

        return view('proposal.Hki', [
            'title' => $title,
            'proposal' => $proposal,
            'jenisHki' => $jenisHki,
            'dosen' => $dosen,
            'Hki' => $hki,
        ]);
    }

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

        $date = strtotime($request->tanggal_upload);
        $date = date('Y-m-d', $date);

        $path_Hki = $request->file('path_hki');
        $filename = $path_Hki->getClientOriginalName();
        $path_Hki = $path_Hki->storeAs('laporan-hki', str_replace(" ", "-", $filename));

        Hki::create([
            'proposal_id' => $request->proposal_id,
            'jenis_hki_id' => $request->jenis,
            'path_hki' => $path_Hki,
            'tanggal_upload' => $date,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan Hki berhasil ditambahkan', 'success');
        return back();
    }

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

        $date = strtotime($request->tanggal_upload);
        $date = date('Y-m-d', $date);

        $path_Hki = $request->file('path_hki');
        if ($path_Hki != null) {
            $fileName = $path_Hki->getClientOriginalName();
            $cekFileName = Hki::where('path_hki', 'laporan-Hki/' . str_replace(" ", "-", $fileName))->get();
            // if (count($cekFileName) != 0) {
            //     Alert::toast('File Sudah Ada!', 'error');
            //     return back()->withInput();
            // }
            $path_Hki = $path_Hki->storeAs('laporan-Hki', str_replace(" ", "-", $fileName));
        } else {
            $path_Hki = $hki->path_hki;
        }

        Hki::findOrFail($id)->update([
            'proposal_id' => $request->proposal_id,
            'jenis_hki_id' => $request->jenis,
            'path_hki' => $path_Hki,
            'tanggal_upload' => $date,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan Hki berhasil diubah', 'success');
        return back();
    }

    public function destroy($id)
    {
        $hki = Hki::find($id);
        Storage::delete($hki->path_hki);

        Hki::findOrFail($id)->delete();
        Alert::success('Laporan Hki berhasil dihapus', 'success');
        return back();
    }
}
