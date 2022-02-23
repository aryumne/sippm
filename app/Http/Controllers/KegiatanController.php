<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class KegiatanController extends Controller
{

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

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string'],
            'dana' => ['required'],
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
        $jenisKegiatan = $request->jenis_kegiatan;
        $dana = str_replace(".", "", $request->dana);
        $path_kegiatan = $request->file('path_kegiatan');
        $filename = $path_kegiatan->getClientOriginalName();
        $cekFileName = Kegiatan::where('path_kegiatan', 'laporan-kegiatan/' . str_replace(" ", "-", $filename))->get();
        if (count($cekFileName) != 0) {
            Alert::toast('File ini sudah ada', 'error');
            return back()->withInput();
        }

        $path_kegiatan = $path_kegiatan->storeAs('laporan-kegiatan', str_replace(" ", "-", $filename));

        Kegiatan::create([
            'sumber_id' => $request->sumberDana,
            'judul_kegiatan' => $request->judul,
            'jumlah_dana' => $dana,
            'jenis_kegiatan' => $jenisKegiatan,
            'path_kegiatan' => $path_kegiatan,
            'tanggal_kegiatan' => $date,
            'user_id' => Auth::user()->nidn,
        ]);

        if ($jenisKegiatan == 1) {
            Alert::success('Berhasil', 'Laporan Penelitian berhasil ditambahkan');
        } else {
            Alert::success('Berhasil', 'Laporan PKM berhasil ditambahkan');
        }
        return back();

    }

    public function update(Request $request, $id)
    {

        $kegiatan = Kegiatan::find($id);

        $rules = [
            'judul' => ['required', 'string'],
            'dana' => ['required'],
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

        $date = $request->tanggal_kegiatan;
        $jenisKegiatan = $request->jenis_kegiatan;
        $dana = str_replace(".", "", $request->dana);

        $path_kegiatan = $request->file('path_kegiatan');
        if ($path_kegiatan != null) {
            $fileName = $path_kegiatan->getClientOriginalName();
            $cekFileName = Kegiatan::where('path_kegiatan', 'laporan-kegiatan/' . str_replace(" ", "-", $fileName))->get();
            // if (count($cekFileName) != 0) {
            //     Alert::toast('File Sudah Ada!', 'error');
            //     return back()->withInput();
            // }
            $path_kegiatan = $path_kegiatan->storeAs('laporan-kegiatan', str_replace(" ", "-", $fileName));
        } else {
            $path_kegiatan = $kegiatan->path_kegiatan;
        }

        Kegiatan::findOrFail($id)->update([
            'sumber_id' => $request->sumberDana,
            'judul_kegiatan' => $request->judul,
            'jumlah_dana' => $dana,
            'jenis_kegiatan' => $jenisKegiatan,
            'path_kegiatan' => $path_kegiatan,
            'tanggal_kegiatan' => $date,
            'user_id' => Auth::user()->nidn,
        ]);

        if ($request->jenis_kegiatan == 1) {
            Alert::success('Berhasil', 'Laporan Penelitian berhasil diubah');
        } else {
            Alert::success('Berhasil', 'Laporan PKM berhasil diubah');
        }
        return back();
    }

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
