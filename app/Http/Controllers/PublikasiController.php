<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Jenis_jurnal;
use App\Models\Proposal;
use App\Models\Publikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

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
        $dosen = Dosen::all();
        $jj = Jenis_jurnal::all();

        // dd($proposal);

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
            $publikasi = Publikasi::whereIn('proposal_id', $proposal_id)->get();
        }

        return view('proposal.publikasi', [
            'title' => $title,
            'proposal' => $proposal,
            'jj' => $jj,
            'dosen' => $dosen,
            'publikasi' => $publikasi,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'proposal_id' => ['required', 'numeric'],
            'judul' => ['required', 'string'],
            'nama' => ['required', 'string'],
            'jenis' => ['required', 'numeric'],
            'path_publikasi' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'tanggal_upload' => ['required', 'string'],
        ], [
            'required' => 'Tidak boleh kosong.',
            'file' => 'Type file harus .pdf.',
            'max' => 'Ukuran file maksimal 2 Mb.',
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }
        // $nama = $request->nama;
        // $gabungNama = implode(" - ", $nama);
        // dd($gabungNama);

        $date = strtotime($request->tanggal_upload);
        $date = date('Y-m-d', $date);

        $path_publikasi = $request->file('path_publikasi');
        $filename = $path_publikasi->getClientOriginalName();
        $path_publikasi = $path_publikasi->storeAs('laporan-publikasi', str_replace(" ", "-", $filename));

        Publikasi::create([
            'proposal_id' => $request->proposal_id,
            'judul_artikel' => $request->judul,
            'nama_jurnal' => $request->nama,
            'jenis_jurnal_id' => $request->jenis,
            'path_jurnal' => $path_publikasi,
            'tanggal_upload' => $date,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan publikasi berhasil ditambahkan', 'success');
        return back();
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $publikasi = Publikasi::find($id);

        // dd($publikasi);

        $rules = [
            'proposal_id' => ['required', 'numeric'],
            'judul' => ['required', 'string'],
            'nama' => ['required', 'string'],
            'jenis' => ['required', 'numeric'],
            'tanggal_upload' => ['required', 'string'],
        ];

        if ($publikasi->path_jurnal != $request->path_jurnal) {
            $rules['path_jurnal'] = ['required', 'file', 'mimes:pdf', 'max:2048'];
        }

        $validator = Validator::make($request->all(), $rules, [
        [
            'required' => 'Tidak boleh kosong.',
            'file' => 'Type file harus .pdf.',
            'max' => 'Ukuran file maksimal 2 Mb.',
        ]
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $date = strtotime($request->tanggal_upload);
        $date = date('Y-m-d', $date);

        $path_publikasi = $request->file('path_jurnal');
        if ($path_publikasi != null) {
            $fileName = $path_publikasi->getClientOriginalName();
            $cekFileName = publikasi::where('path_jurnal', 'laporan-publikasi/' . str_replace(" ", "-", $fileName))->get();
            // if (count($cekFileName) != 0) {
            //     Alert::toast('File Sudah Ada!', 'error');
            //     return back()->withInput();
            // }
            $path_publikasi = $path_publikasi->storeAs('laporan-publikasi', str_replace(" ", "-", $fileName));
        } else {
            $path_publikasi = $publikasi->path_jurnal;
        }

        publikasi::findOrFail($id)->update([
            'proposal_id' => $request->proposal_id,
            'judul_artikel' => $request->judul,
            'nama_jurnal' => $request->nama,
            'jenis_jurnal_id' => $request->jenis,
            'path_jurnal' => $path_publikasi,
            'tanggal_upload' => $date,
            'user_id' => Auth::user()->id,
        ]);

        Alert::success('Laporan publikasi berhasil diubah', 'success');
        return back();
    }

    public function destroy($id)
    {
        $publikasi = Publikasi::find($id);
        Storage::delete($publikasi->path_jurnal);
        Publikasi::findOrFail($id)->delete();
        Alert::success('Laporan Publikasi berhasil dihapus', 'success');
        return back();
    }
}
