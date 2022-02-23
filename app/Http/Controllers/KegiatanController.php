<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kegiatan;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($kegiatan)
    {
        $dataKegiatan = Kegiatan::all();
        $sumberDana = SumberDana::all();

        if ($kegiatan == "penelitian") {
            $title = "Daftar Penelitian";
            $data = 1;
            $dataKegiatan = Kegiatan::where('jenis_kegiatan', $data)->get();

            if (Auth::user()->role_id == 2) {
                $dataKegiatan = Kegiatan::where('jenis_kegiatan', $data)->where('user_id', Auth::user()->nidn)->get();
            }
            return view('proposal.penelitian', [
                'title' => $title,
                'penelitian' => $dataKegiatan,
                'sumberDana' => $sumberDana,
            ]);
        } else if ($kegiatan == "pkm") {
            $title = "Daftar Pkm";
            $data = 2;
            $dataKegiatan = Kegiatan::where('jenis_kegiatan', $data)->get();
            if (Auth::user()->role_id == 2) {
                $dataKegiatan = Kegiatan::where('jenis_kegiatan', $data)->where('user_id', Auth::user()->nidn)->get();
            }
            return view('proposal.pkm', [
                'title' => $title,
                'pkm' => $dataKegiatan,
                'sumberDana' => $sumberDana,
            ]);
        } else {
            return abort(404);
        }
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
        if ($request->jenis_kegiatan == 1) {

            $validator = Validator::make($request->all(), [
                'judul' => ['required', 'string'],
                'dana' => ['required', 'numeric'],
                'tanggal_kegiatan' => ['required', 'string'],
                'path_kegiatan' => ['required', 'file', 'mimes:pdf', 'max:2048'],
                'sumberDana' => ['required', 'numeric'],
            ]);

            if ($validator->fails()) {
                Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
                return back()->withErrors($validator)->withInput();
            }

            $date = strtotime($request->tanggal_kegiatan);
            $date = date('Y-m-d', $date);
            $penelitian = 1;
            $path_kegiatan = $request->file('path_kegiatan');
            $filename = $path_kegiatan->getClientOriginalName();
            $path_kegiatan = $path_kegiatan->storeAs('laporan-penelitian', str_replace(" ", "-", $filename));

            Kegiatan::create([
                'sumber_id' => $request->sumberDana,
                'judul_kegiatan' => $request->judul,
                'jumlah_dana' => $request->dana,
                'jenis_kegiatan' => $penelitian,
                'path_kegiatan' => $path_kegiatan,
                'tanggal_kegiatan' => $date,
                'user_id' => Auth::user()->nidn,
            ]);

            Alert::success('Laporan Penelitian berhasil ditambahkan', 'success');
            return back();
        } else if ($request->jenis_kegiatan == 2) {

            $validator = Validator::make($request->all(), [
                'judul' => ['required', 'string'],
                'dana' => ['required', 'numeric'],
                'tanggal_kegiatan' => ['required', 'string'],
                'path_kegiatan' => ['required', 'file', 'mimes:pdf', 'max:2048'],
                'sumberDana' => ['required', 'numeric'],
            ]);

            if ($validator->fails()) {
                Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
                return back()->withErrors($validator)->withInput();
            }

            $date = strtotime($request->tanggal_kegiatan);
            $date = date('Y-m-d', $date);
            $penelitian = 2;
            $path_kegiatan = $request->file('path_kegiatan');
            $filename = $path_kegiatan->getClientOriginalName();
            $path_kegiatan = $path_kegiatan->storeAs('laporan-pkm', str_replace(" ", "-", $filename));

            Kegiatan::create([
                'sumber_id' => $request->sumberDana,
                'judul_kegiatan' => $request->judul,
                'jumlah_dana' => $request->dana,
                'jenis_kegiatan' => $penelitian,
                'path_kegiatan' => $path_kegiatan,
                'tanggal_kegiatan' => $date,
                'user_id' => Auth::user()->nidn,
            ]);

            Alert::success('Laporan PKM berhasil ditambahkan', 'success');
            return back();
        } else {
            return abort(404);
        }
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
        if ($request->jenis_kegiatan == 1) {

            $kegiatan = Kegiatan::find($id);

            $rules = [
                'judul' => ['required', 'string'],
                'dana' => ['required', 'numeric'],
                'tanggal_kegiatan' => ['required', 'string'],
                'sumberDana' => ['required', 'numeric'],
            ];

            if ($kegiatan->path_kegiatan != $request->path_kegiatan) {
                $rules['path_kegiatan'] = ['required', 'file', 'mimes:pdf', 'max:2048'];
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
                return back()->withErrors($validator)->withInput();
            }

            $date = strtotime($request->tanggal_upload);
            $date = date('Y-m-d', $date);
            $penelitian = 1;

            $path_kegiatan = $request->file('path_kegiatan');
            if ($path_kegiatan != NULL) {
                $fileName = $path_kegiatan->getClientOriginalName();
                $cekFileName = Kegiatan::where('path_kegiatan', 'laporan-kegiatan/' . str_replace(" ", "-", $fileName))->get();
                if (count($cekFileName) != 0) {
                    Alert::toast('File Sudah Ada!', 'error');
                    return back()->withInput();
                }
                $path_kegiatan = $path_kegiatan->storeAs('laporan-kegiatan', str_replace(" ", "-", $fileName));
            } else {
                $path_kegiatan = $kegiatan->path_kegiatan;
            }

            Kegiatan::findOrFail($id)->update([
                'sumber_id' => $request->sumberDana,
                'judul_kegiatan' => $request->judul,
                'jumlah_dana' => $request->dana,
                'jenis_kegiatan' => $penelitian,
                'path_kegiatan' => $path_kegiatan,
                'tanggal_kegiatan' => $date,
                'user_id' => Auth::user()->nidn,
            ]);

            Alert::success('Laporan Penelitian berhasil diubah', 'success');
            return back();
        } else if ($request->jenis_kegiatan == 2) {
            $kegiatan = Kegiatan::find($id);

            $rules = [
                'judul' => ['required', 'string'],
                'dana' => ['required', 'numeric'],
                'tanggal_kegiatan' => ['required', 'string'],
                'sumberDana' => ['required', 'numeric'],
            ];

            if ($kegiatan->path_kegiatan != $request->path_kegiatan) {
                $rules['path_kegiatan'] = ['required', 'file', 'mimes:pdf', 'max:2048'];
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
                return back()->withErrors($validator)->withInput();
            }

            $date = strtotime($request->tanggal_upload);
            $date = date('Y-m-d', $date);
            $penelitian = 2;

            $path_kegiatan = $request->file('path_kegiatan');
            if ($path_kegiatan != NULL) {
                $fileName = $path_kegiatan->getClientOriginalName();
                $cekFileName = Kegiatan::where('path_kegiatan', 'laporan-kegiatan/' . str_replace(" ", "-", $fileName))->get();
                if (count($cekFileName) != 0) {
                    Alert::toast('File Sudah Ada!', 'error');
                    return back()->withInput();
                }
                $path_kegiatan = $path_kegiatan->storeAs('laporan-kegiatan', str_replace(" ", "-", $fileName));
            } else {
                $path_kegiatan = $kegiatan->path_kegiatan;
            }

            Kegiatan::findOrFail($id)->update([
                'sumber_id' => $request->sumberDana,
                'judul_kegiatan' => $request->judul,
                'jumlah_dana' => $request->dana,
                'jenis_kegiatan' => $penelitian,
                'path_kegiatan' => $path_kegiatan,
                'tanggal_kegiatan' => $date,
                'user_id' => Auth::user()->nidn,
            ]);

            Alert::success('Laporan PKM berhasil diubah', 'success');
            return back();
        } else {
            return abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kegiatan = Kegiatan::find($id);
        Storage::delete($kegiatan->path_kegiatan);
        Kegiatan::findOrFail($id)->delete();

        if ($kegiatan->jenis_kegiatan == 1) {
            Alert::success('Laporan Penelitian berhasil dihapus', 'success');
        } else if ($kegiatan->jenis_kegiatan == 2) {
            Alert::success('Laporan PKM berhasil dihapus', 'success');
        } else {
            return abort(404);
        }

        return back();
    }
}
