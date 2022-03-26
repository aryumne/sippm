<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Jenis_jurnal;
use App\Models\LapPublikasi;
use Illuminate\Http\Request;
use App\Models\TimExternPublikasi;
use App\Models\TimInternPublikasi;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class LapPublikasiController extends Controller
{

    public function index()
    {
        $title = "Luaran Publikasi";
        $lapPublikasis = LapPublikasi::where('user_id', Auth::user()->id)->get();
        return view('pengusul.luaranPublikasi', [
            'title' => $title,
            'lapPublikasis' => $lapPublikasis,
        ]);
    }

    public function create()
    {
        $title = "Tambah Luaran Publikasi";
        $jenisJurnals = Jenis_jurnal::all();
        $dosens = Dosen::where('nidn', 'not like', '%ADMIN%')->get();
        return view('pengusul.createLuaranPublikasi', [
            'title' => $title,
            'jenisJurnals' => $jenisJurnals,
            'dosens' => $dosens,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string', 'unique:lap_publikasis'],
            'nama' => ['required', 'string'],
            'laman' => ['required', 'string'],
            'tahun' => ['required', 'numeric', 'digits:4'],
            'jenis_jurnal_id' => ['required'],
            'path_publikasi' => ['required', 'file', 'mimes:pdf', 'max:8192'],
        ]);

        if($validator->fails())
        {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        //Ambil original filename dari file yang diupload
        $pathPublikasi = $request->file('path_publikasi');
        $fileName = str_replace(" ", "-", $pathPublikasi->getClientOriginalName());
        //cek apakah file dengan nama yang sama sudah ada didalam database
        $cekFileName = LapPublikasi::where('path_publikasi', 'laporan-publikasi/'.$fileName)->get();
        if(count($cekFileName) != 0)
        {
            Alert::toast('File proposal sudah ada', 'error');
            return back()->withInput();
        }

        $judul = $request->judul;
        $nidn_anggota = $request->nidn_anggota;
        $nama_anggota = $request->nama_anggota;
        $asal_anggota = $request->asal_anggota;
        //Cek Apakah ketua dari dalam UNIPA atau dari luar
        if($request->checkKetua == null)
        {
            //ketua dari dalam UNIPA
            //cek data ada yang sama atau tidak
            $lapPublikasis = LapPublikasi::where('judul', 'like', '%'.$judul.'%')->get();
            //(opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
            if(count($lapPublikasis) > 0)
            {
                //kalau ada, cek data ini apakah diketuai oleh inputan yang dipilih
                foreach($lapPublikasis as $publikasi)
                {
                    $ketuaPublikasi = TimInternPublikasi::where('lap_publikasi_id', $publikasi->id)->where('nidn', $request->nidn_ketua)->where('isLeader', true)->get();
                    //kalau ada, redirect ke detail data yang sama.
                    if($ketuaPublikasi) {
                        Alert::toast('Gagal menyimpan, Data yang diinputkan sama dengan data ini', 'warning');
                        return redirect()->route('luaran-publikasi.show', $publikasi);
                    }
                }
            }

            //cek apakah ketua juga ditambahkan sebagai anggota atau tidak
            foreach($nidn_anggota as $intern)
            {
                if($request->nidn_ketua == $intern)
                {
                    Alert::toast('Ketua tidak bisa menjabat sebagai anggota dalam satu tim', 'error');
                    return back()->withInput();
                }
            }
        } else {
            //Ketua dari luar UNIPA
            //cek data ada yang sama atau tidak
            $lapPublikasis = LapPublikasi::where('judul', 'like', '%'.$judul.'%')->get();
            //(opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
            //kalau ada, cek data ini apakah diketuai oleh inputan yang diisi
            if(count($lapPublikasis) > 0)
            {
                foreach($lapPublikasis as $publikasi)
                {
                    $ketuaPublikasi = TimExternPublikasi::where('lap_publikasi_id', $publikasi->id)->where('nama', $request->nama_ketua)->where('isLeader', true)->get();
                    //kalau ada, redirect ke detail data yang sama.
                    if($ketuaPublikasi) {
                        Alert::toast('Gagal menyimpan, Data yang diinputkan sama dengan data ini', 'warning');
                        return redirect()->route('luaran-publikasi.show', $publikasi);
                    }
                }
            }

            //cek apakah ketua juga ditambahkan sebagai anggota atau tidak
            foreach($nama_anggota as $extern)
            {
                //cek kesamaan inputan ketua dan anggota luar
                $similiar = similar_text(strtolower($request->nama_ketua), strtolower($extern));
                $hasil = $similiar/strlen($request->nama_ketua) * 100;
                //jika tingkat kesamaan inputan 85% ke atas maka kembalikan inputan
                if((int)$hasil >= 80)
                {
                    Alert::toast('Ketua tidak bisa menjabat sebagai anggota dalam satu tim', 'error');
                    return back()->withInput();
                }
            }
        }

        //upload file ke folder laporan-publikasi
        $pathPublikasi = $pathPublikasi->storeAs('laporan-publikasi', $fileName);
        //simpan data ke tabel lap_publikasis
        $newPublikasi = LapPublikasi::create([
            'judul' => $judul,
            'nama' => $request->nama,
            'laman' => $request->laman,
            'tahun' => $request->tahun,
            'jenis_jurnal_id' => $request->jenis_jurnal_id,
            'path_publikasi' => $pathPublikasi,
            'user_id' => Auth::user()->id,
        ]);

        //simpah data ketua
        if($request->checkKetua == null)
        {
            TimInternPublikasi::create([
                'lap_publikasi_id' => $newPublikasi->id,
                'nidn' => str_pad($request->nidn_ketua, 10, "0", STR_PAD_LEFT),
                'isLeader' => true,
            ]);
        } else {
            TimExternPublikasi::create([
                'lap_publikasi_id' => $newPublikasi->id,
                'nama' => $request->nama_ketua,
                'asal_institusi' => $request->asal_ketua,
                'isLeader' => true,
            ]);
        }

        //simpan data anggota
        foreach($nidn_anggota as $intern)
        {
            TimInternPublikasi::create([
                'lap_publikasi_id' => $newPublikasi->id,
                'nidn' => str_pad($intern, 10, "0", STR_PAD_LEFT),
                'isLeader' => false,
            ]);
        }

        if(count($nama_anggota) >= 1 && $nama_anggota[0] != null) {
            for($i = 0; $i < count($nama_anggota); $i++ )
            {
                TimExternPublikasi::create([
                    'lap_publikasi_id' => $newPublikasi->id,
                    'nama' => $nama_anggota[$i],
                    'asal_institusi' => $asal_anggota[$i],
                    'isLeader' => false,
                ]);
            }
        }

         Alert::success('Tersimpan', 'Luaran Publikasi telah ditambahkan');
         return redirect()->route('luaran-publikasi.show', $newPublikasi->id);

    }

    public function show($id)
    {
        dd(LapPublikasi::find($id));
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
