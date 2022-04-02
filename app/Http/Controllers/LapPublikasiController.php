<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Jenis_jurnal;
use App\Models\LapPublikasi;
use Illuminate\Http\Request;
use App\Models\TimExternPublikasi;
use App\Models\TimInternPublikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        ], [
            'judul.unique' => "Judul artikel ini sudah ada",
            'path_publikasi.mimes' => "Type file harus pdf",
            'path_publikasi.max' => "File maksimal 8 MB",
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
                        Alert::toast('Kami melihat data yang sama, mungkin data ini yang ada maksud.', 'warning');
                        return redirect()->route('luaran-publikasi.show', $publikasi);
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
        if($nidn_anggota != null)
        {
            foreach($nidn_anggota as $intern)
            {
                TimInternPublikasi::create([
                    'lap_publikasi_id' => $newPublikasi->id,
                    'nidn' => str_pad($intern, 10, "0", STR_PAD_LEFT),
                    'isLeader' => false,
                ]);
            }
        }


        if($nama_anggota != null)
        {
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
        $title = "Detail Luaran Publikasi";
        $lapPublikasi = LapPublikasi::find($id);
        if(Auth::user()->role_id == 2)
        {
            $isUserAnggota = TimInternPublikasi::where('lap_publikasi_id', $lapPublikasi->id)->where('nidn', Auth::user()->nidn)->get();
            if($lapPublikasi->user_id != Auth::user()->id && count($isUserAnggota) < 1)
            {
                return abort(403);
            }
        }
        return view('pengusul.showLuaranPublikasi', [
            'title' => $title,
            'lapPublikasi' => $lapPublikasi,
        ]);

    }

    public function edit($id)
    {
        $title = "Tambah Luaran Publikasi";
        $lapPublikasi = LapPublikasi::find($id);
        $jenisJurnals = Jenis_jurnal::all();
        $dosens = Dosen::where('nidn', 'not like', '%ADMIN%')->get();
        if(Auth::user()->role_id == 2)
        {
            if($lapPublikasi->user_id != Auth::user()->id)
            {
                return abort(403);
            }
            $isUserAnggota = TimInternPublikasi::where('lap_publikasi_id', $lapPublikasi->id)->where('nidn', Auth::user()->nidn)->get();
            if(count($isUserAnggota) < 1)
            {
                return abort(403);
            }
        }
        return view('pengusul.editLuaranPublikasi', [
            'title' => $title,
            'jenisJurnals' => $jenisJurnals,
            'dosens' => $dosens,
            'lapPublikasi' => $lapPublikasi,
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $lapPublikasi = LapPublikasi::find($id);
        // cek ada perubahan judul atau perubahan nama ketua
        // kalau ada perubahan di salah satunya baru dilakukakan pengecekan ada data yang sama atau tidak
        // cek ada isi file unggahan atau tidak
        // kalau ada hapus yang lama lalu upload yang baru diinputkan
        // simpah perubahan data lap publikasi
        // hapus semua anggota yang ada lalu tambahkan kembali data anggota yang baru diinputkan
        $rules = [
            'nama' => ['required', 'string'],
            'laman' => ['required', 'string'],
            'tahun' => ['required', 'numeric', 'digits:4'],
            'jenis_jurnal_id' => ['required'],
        ];

        //cek apakah ada perubahan pada judul artikel, kalau ada maka tambahkan validator
        if($request->judul != $lapPublikasi->judul)
        {
            $rules['judul'] = ['required', 'string', 'unique:lap_publikasis'];
        }
        // cek apakah ada file unggahan
        if($request->path_publikasi != null) {
            $rules['path_publikasi'] = ['required', 'file', 'mimes:pdf', 'max:8192'];
        }

        $validator = Validator::make($request->all(), $rules, [
            'judul.unique' => "Judul artikel ini sudah ada",
            'path_publikasi.mimes' => "Type file harus pdf",
            'path_publikasi.max' => "File maksimal 8 MB",
        ]);

        if($validator->fails())
        {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $judul = $request->judul;
        $nidn_anggota = $request->nidn_anggota;
        $nama_anggota = $request->nama_anggota;
        $asal_anggota = $request->asal_anggota;
        if($request->judul != $lapPublikasi->judul)
        {
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
                            Alert::toast('Kami melihat data yang sama, mungkin data ini yang ada maksud.', 'warning');
                            return redirect()->route('luaran-publikasi.show', $publikasi);
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
            }

            //Ambil original filename dari file yang diupload
            //upload file ke folder laporan-publikasi
            $pathPublikasi = $request->file('path_publikasi');
            if($pathPublikasi != null)
        {
            Storage::delete($lapPublikasi->path_publikasi);
            $fileName = str_replace(" ", "-", $pathPublikasi->getClientOriginalName());
            $pathPublikasi = $pathPublikasi->storeAs('laporan-publikasi', $fileName);
        } else {
            $pathPublikasi = $lapPublikasi->path_publikasi;
        }

        //simpan data ke tabel lap_publikasis
        LapPublikasi::findOrFail($id)->update([
            'judul' => $judul,
            'nama' => $request->nama,
            'laman' => $request->laman,
            'tahun' => $request->tahun,
            'jenis_jurnal_id' => $request->jenis_jurnal_id,
            'path_publikasi' => $pathPublikasi,
            'user_id' => Auth::user()->id,
        ]);

        // dd($request->all());
        // hapus tim intern UNIPA lama
        TimInternPublikasi::where('lap_publikasi_id', $id)->delete();
        // hapus tim Extern UNIPA lama
        TimExternPublikasi::where('lap_publikasi_id', $id)->delete();

        //simpah data ketua baru update
        if($request->checkKetua == null)
        {
            TimInternPublikasi::create([
                'lap_publikasi_id' => $id,
                'nidn' => str_pad($request->nidn_ketua, 10, "0", STR_PAD_LEFT),
                'isLeader' => true,
            ]);
        } else {
            TimExternPublikasi::create([
                'lap_publikasi_id' => $id,
                'nama' => $request->nama_ketua,
                'asal_institusi' => $request->asal_ketua,
                'isLeader' => true,
            ]);
        }

        //simpan data anggota baru update
        if($nidn_anggota != null)
        {
            foreach($nidn_anggota as $intern)
            {
                TimInternPublikasi::create([
                    'lap_publikasi_id' => $id,
                    'nidn' => str_pad($intern, 10, "0", STR_PAD_LEFT),
                    'isLeader' => false,
                ]);
            }
        }

        if($nama_anggota != null)
        {
                for($i = 0; $i < count($nama_anggota); $i++ )
                {
                    TimExternPublikasi::create([
                        'lap_publikasi_id' => $id,
                        'nama' => $nama_anggota[$i],
                        'asal_institusi' => $asal_anggota[$i],
                        'isLeader' => false,
                    ]);
                }
        }


        Alert::success('Tersimpan', 'Perubahan data luaran publikasi telah disimpan');
        return redirect()->route('luaran-publikasi.show', $id);
    }

    public function destroy($id)
    {
        //
    }
}