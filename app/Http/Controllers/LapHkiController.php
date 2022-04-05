<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\LapHki;
use App\Models\Jenis_hki;
use App\Models\TimExternHki;
use App\Models\TimInternHki;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class LapHkiController extends Controller
{

    public function index()
    {
        $title = "Luaran HKI";
        $lapHkis = LapHki::all();
        if(Auth::user()->role_id == 2) {
            // ambil data hki yang user punya baik itu sebagai pengupload maupun hanya berkontribusi
            $lapHkis = LapHki::wherehas('timIntern', function($query) {
                $query->where('tim_intern_publikasis.nidn', Auth::user()->nidn);
            })->orWhere('user_id', Auth::user()->id)->get();
        }
        return view('hki.luaranHkis', [
            'title' => $title,
            'lapHkis' => $lapHkis,
        ]);
    }

    public function create()
    {
        $title = "Tambah Luaran HKI";
        $jenisHkis = Jenis_hki::all();
        $dosens = Dosen::where('nidn', 'not like', '%ADMIN%')->get();
        return view('hki.createLuaranHki', [
            'title' => $title,
            'jenisHkis' => $jenisHkis,
            'dosens' => $dosens,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string', 'unique:lap_hkis'],
            'tahun' => ['required', 'numeric', 'digits:4'],
            'jenis_hki_id' => ['required'],
            'path_hki' => ['required', 'file', 'mimes:pdf', 'max:8192'],
        ], [
            'judul.unique' => "Judul artikel ini sudah ada",
            'path_hki.mimes' => "Type file harus pdf",
            'path_hki.max' => "File maksimal 8 MB",
        ]);

        if($validator->fails())
        {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        //Ambil original filename dari file yang diupload
        $pathHki = $request->file('path_hki');
        $fileName = str_replace(" ", "-", $pathHki->getClientOriginalName());
        //cek apakah file dengan nama yang sama sudah ada didalam database
        $cekFileName = LapHki::where('path_hki', 'laporan-hki/'.$fileName)->get();
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
            $lapHkis = LapHki::where('judul', 'like', '%'.$judul.'%')->get();
            //(opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
            if(count($lapHkis) > 0)
            {
                //kalau ada, cek data ini apakah diketuai oleh inputan yang dipilih
                foreach($lapHkis as $hki)
                {
                    $ketuaHki = TimInternHki::where('lap_hki_id', $hki->id)->where('nidn', $request->nidn_ketua)->where('isLeader', true)->get();
                    //kalau ada, redirect ke detail data yang sama.
                    if($ketuaHki) {
                        Alert::toast('Gagal menyimpan, Data yang diinputkan sama dengan data ini', 'warning');
                        return redirect()->route('luaran-hki.show', $hki);
                    }
                }
            }

            //cek apakah ketua juga ditambahkan sebagai anggota atau tidak
            if($nidn_anggota != null)
            {
                foreach($nidn_anggota as $intern)
                {
                    if($request->nidn_ketua == $intern)
                    {
                        Alert::toast('Ketua tidak bisa menjabat sebagai anggota dalam satu tim', 'error');
                        return back()->withInput();
                    }
                }
            }
        } else {
            //Ketua dari luar UNIPA
            //cek data ada yang sama atau tidak
            $lapHkis = LapHki::where('judul', 'like', '%'.$judul.'%')->get();
            //(opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
            //kalau ada, cek data ini apakah diketuai oleh inputan yang diisi
            if(count($lapHkis) > 0)
            {
                foreach($lapHkis as $hki)
                {
                    $ketuaHki = TimExternHki::where('lap_hki_id', $hki->id)->where('nama', $request->nama_ketua)->where('isLeader', true)->get();
                    //kalau ada, redirect ke detail data yang sama.
                    if($ketuaHki) {
                        Alert::toast('Kami melihat data yang sama, mungkin data ini yang ada maksud.', 'warning');
                        return redirect()->route('luaran-hki.show', $hki);
                    }
                }
            }

            //cek apakah ketua juga ditambahkan sebagai anggota atau tidak
            if($nama_anggota != null)
            {
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
        }

        //upload file ke folder laporan-hki
        $pathHki = $pathHki->storeAs('laporan-hki', $fileName);
        //simpan data ke tabel lap_publikasis
        $newHki = LapHki::create([
            'judul' => $judul,
            'tahun' => $request->tahun,
            'jenis_hki_id' => $request->jenis_hki_id,
            'path_hki' => $pathHki,
            'user_id' => Auth::user()->id,
        ]);

        //simpah data ketua
        if($request->checkKetua == null)
        {
            TimInternHki::create([
                'lap_hki_id' => $newHki->id,
                'nidn' => str_pad($request->nidn_ketua, 10, "0", STR_PAD_LEFT),
                'isLeader' => true,
            ]);
        } else {
            TimExternHki::create([
                'lap_hki_id' => $newHki->id,
                'nama' => $request->nama_ketua,
                'asal_institusi' => $request->asal_ketua,
                'isLeader' => true,
            ]);
        }

        //simpan data anggota
        if($nidn_anggota != null)
        {
            foreach($nidn_anggota as $intern)
            {
                TimInternHki::create([
                    'lap_hki_id' => $newHki->id,
                    'nidn' => str_pad($intern, 10, "0", STR_PAD_LEFT),
                    'isLeader' => false,
                ]);
            }
        }


        if($nama_anggota != null)
        {
                for($i = 0; $i < count($nama_anggota); $i++ )
                {
                    TimExternHki::create([
                        'lap_hki_id' => $newHki->id,
                        'nama' => $nama_anggota[$i],
                        'asal_institusi' => $asal_anggota[$i],
                        'isLeader' => false,
                    ]);
                }
        }

        Alert::success('Tersimpan', 'Luaran HKI telah ditambahkan');
        return redirect()->route('luaran-hki.show', $newHki->id);
    }

    public function show($id)
    {
        dd(LapHki::find($id));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
