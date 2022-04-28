<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\LapHki;
use App\Models\Jenis_hki;
use App\Models\TimExternHki;
use App\Models\TimInternHki;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class LapHkiController extends Controller
{

    public function index()
    {
        $title = "Luaran HKI";
        $lapHkis = LapHki::all();
        if (Auth::user()->role_id >= 2) {
            // ambil data hki yang user punya baik itu sebagai pengupload maupun hanya berkontribusi
            $lapHkis = LapHki::wherehas('timIntern', function ($query) {
                $query->where('tim_intern_hkis.nidn', Auth::user()->nidn);
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

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        //Ambil original filename dari file yang diupload
        $pathHki = $request->file('path_hki');
        $fileName = str_replace(" ", "-", $pathHki->getClientOriginalName());
        //cek apakah file dengan nama yang sama sudah ada didalam database
        $cekFileName = LapHki::where('path_hki', 'laporan-hki/' . $fileName)->get();
        if (count($cekFileName) != 0) {
            Alert::toast('File proposal sudah ada', 'error');
            return back()->withInput();
        }

        $judul = $request->judul;
        $nidn_anggota = $request->nidn_anggota;
        $nama_anggota = $request->nama_anggota;
        $asal_anggota = $request->asal_anggota;
        //Cek Apakah ketua dari dalam UNIPA atau dari luar
        if ($request->checkKetua == null) {
            //ketua dari dalam UNIPA
            //cek data ada yang sama atau tidak
            $lapHkis = LapHki::where('judul', 'like', '%' . $judul . '%')->get();
            //(opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
            if (count($lapHkis) > 0) {
                //kalau ada, cek data ini apakah diketuai oleh inputan yang dipilih
                foreach ($lapHkis as $hki) {
                    $ketuaHki = TimInternHki::where('lap_hki_id', $hki->id)->where('nidn', $request->nidn_ketua)->where('isLeader', true)->get();
                    //kalau ada, redirect ke detail data yang sama.
                    if ($ketuaHki) {
                        Alert::toast('Gagal menyimpan, Data yang diinputkan sama dengan data ini', 'warning');
                        return redirect()->route('luaran-hki.show', $hki);
                    }
                }
            }

            //cek apakah ketua juga ditambahkan sebagai anggota atau tidak
            if ($nidn_anggota != null) {
                foreach ($nidn_anggota as $intern) {
                    if ($request->nidn_ketua == $intern) {
                        Alert::toast('Ketua tidak bisa menjabat sebagai anggota dalam satu tim', 'error');
                        return back()->withInput();
                    }
                }
            }
        } else {
            //Ketua dari luar UNIPA
            //cek data ada yang sama atau tidak
            $lapHkis = LapHki::where('judul', 'like', '%' . $judul . '%')->get();
            //(opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
            //kalau ada, cek data ini apakah diketuai oleh inputan yang diisi
            if (count($lapHkis) > 0) {
                foreach ($lapHkis as $hki) {
                    $ketuaHki = TimExternHki::where('lap_hki_id', $hki->id)->where('nama', $request->nama_ketua)->where('isLeader', true)->get();
                    //kalau ada, redirect ke detail data yang sama.
                    if ($ketuaHki) {
                        Alert::toast('Kami melihat data yang sama, mungkin data ini yang ada maksud.', 'warning');
                        return redirect()->route('luaran-hki.show', $hki);
                    }
                }
            }

            // cek apakah ada kontribusi dari dosen UNIPA atau tidak
            if ($nidn_anggota != null) {
                if (Auth::user()->role_id >= 2) {
                    // termasuk didalam tim atau tidak
                    if (!in_array(Auth::user()->nidn, $nidn_anggota)) {
                        // kalau tidak kembalikan user ke form tambah
                        Alert::toast('Sebagai pengunggah anda harus menjadi bagian dari tim', 'warning');
                        return back()->withInput();
                    }
                }
            } else {
                //kalau anggota kosong
                // cek jika user selain admin
                if (Auth::user()->role_id >= 2) {
                    // kalau tidak ada kontribusi user dalam TIM kembalikan user ke form tambah
                    Alert::toast('Sebagai pengunggah anda harus menjadi bagian dari tim', 'warning');
                    return back()->withInput();
                }

                // Untuk admin
                // kalau tidak ada kontribusi dosen UNIPA kembalikan ke form tambah
                Alert::toast('Minimal 1 penulis dalam tim harus dari dalam UNIPA.', 'warning');
                return back()->withInput();
            }

            //cek apakah ketua juga ditambahkan sebagai anggota atau tidak
            if ($nama_anggota != null) {
                foreach ($nama_anggota as $extern) {
                    //cek kesamaan inputan ketua dan anggota luar
                    $similiar = similar_text(strtolower($request->nama_ketua), strtolower($extern));
                    $hasil = $similiar / strlen($request->nama_ketua) * 100;
                    //jika tingkat kesamaan inputan 85% ke atas maka kembalikan inputan
                    if ((int)$hasil >= 80) {
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
        if ($request->checkKetua == null) {
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
        if ($nidn_anggota != null) {
            foreach ($nidn_anggota as $intern) {
                TimInternHki::create([
                    'lap_hki_id' => $newHki->id,
                    'nidn' => str_pad($intern, 10, "0", STR_PAD_LEFT),
                    'isLeader' => false,
                ]);
            }
        }


        if ($nama_anggota != null) {
            for ($i = 0; $i < count($nama_anggota); $i++) {
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
        // dd(LapHki::find($id));
        $title = "Detail Luaran HKI";
        $lapHki = LapHki::find($id);
        if ($lapHki == null) {
            return abort(404);
        }
        if (Auth::user()->role_id >= 2) {
            $isUserAnggota = TimInternHki::where('lap_hki_id', $lapHki->id)->where('nidn', Auth::user()->nidn)->get();
            if ($lapHki->user_id != Auth::user()->id && count($isUserAnggota) < 1) {
                return abort(403);
            }
        }
        return view('hki.showLuaranHki', [
            'title' => $title,
            'lapHki' => $lapHki,
        ]);
    }

    public function edit($id)
    {
        $title = "Edit Luaran HKI";
        $lapHki = LapHki::find($id);
        if ($lapHki == null) {
            return abort(404);
        }
        $jenisHkis = Jenis_hki::all();
        $dosens = Dosen::where('nidn', 'not like', '%ADMIN%')->get();
        //cek user yang bukan admin
        if (Auth::user()->role_id >= 2) {
            // abort user yang bukan pemilik atau pengupload data ini
            foreach ($lapHki->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua dan bukan pengunggah maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn && $lapHki->user_id != Auth::user()->id) {
                        return abort(403);
                    }
                }
            }
        }
        return view('hki.editLuaranHki', [
            'title' => $title,
            'jenisHkis' => $jenisHkis,
            'dosens' => $dosens,
            'lapHki' => $lapHki,
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $lapHki = LapHki::find($id);
        if ($lapHki == null) {
            return abort(404);
        }
        //cek user yang bukan admin
        if (Auth::user()->role_id >= 2) {
            // abort user yang bukan pemilik atau pengupload data ini
            foreach ($lapHki->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua dan bukan pengunggah maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn && $lapHki->user_id != Auth::user()->id) {
                        return abort(403);
                    }
                }
            }
        }
        // cek ada perubahan judul atau perubahan nama ketua
        // kalau ada perubahan di salah satunya baru dilakukakan pengecekan ada data yang sama atau tidak
        // cek ada isi file unggahan atau tidak
        // kalau ada hapus yang lama lalu upload yang baru diinputkan
        // simpah perubahan data lap publikasi
        // hapus semua anggota yang ada lalu tambahkan kembali data anggota yang baru diinputkan
        $rules = [
            'tahun' => ['required', 'numeric', 'digits:4'],
            'jenis_hki_id' => ['required'],
        ];

        //cek apakah ada perubahan pada judul artikel, kalau ada maka tambahkan validator
        if ($request->judul != $lapHki->judul) {
            $rules['judul'] = ['required', 'string', 'unique:lap_hkis'];
        }
        // cek apakah ada file unggahan
        if ($request->path_hki != null) {
            $rules['path_hki'] = ['required', 'file', 'mimes:pdf', 'max:8192'];
        }

        $validator = Validator::make($request->all(), $rules, [
            'judul.unique' => "Judul artikel ini sudah ada",
            'path_hki.mimes' => "Type file harus pdf",
            'path_hki.max' => "File maksimal 8 MB",
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $judul = $request->judul;
        $nidn_anggota = $request->nidn_anggota;
        $nama_anggota = $request->nama_anggota;
        $asal_anggota = $request->asal_anggota;

        //Cek Apakah ketua dari dalam UNIPA atau dari luar
        if ($request->checkKetua == null) {
            // ketua dari dalam UNIPA
            // Jika ada perubahan judu; cek apakah judul ini sudah dinputkan sebelumnya atau tidak
            if ($request->judul != $lapHki->judul) {
                $lapHkis = LapHki::where('judul', 'like', '%' . $judul . '%')->get();
                // (opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
                if (count($lapHkis) > 0) {
                    //kalau ada, cek data ini apakah diketuai oleh inputan yang dipilih
                    foreach ($lapHkis as $hki) {
                        $ketuaHki = TimInternHki::where('lap_hki_id', $hki->id)->where('nidn', $request->nidn_ketua)->where('isLeader', true)->get();
                        //kalau ada, redirect ke detail data yang sama.
                        if ($ketuaHki) {
                            Alert::toast('Gagal menyimpan, Data yang diinputkan sama dengan data ini', 'warning');
                            return redirect()->route('luaran-hki.show', $hki);
                        }
                    }
                }
            }

            // cek apakah ketua juga ditambahkan sebagai anggota atau tidak
            if ($nidn_anggota != null) {
                foreach ($nidn_anggota as $intern) {
                    if ($request->nidn_ketua == $intern) {
                        Alert::toast('Ketua tidak bisa menjabat sebagai anggota dalam satu tim', 'error');
                        return back()->withInput();
                    }
                }
            }
        } else {
            // Ketua dari luar UNIPA
            // Jika ada perubahan judu; cek apakah judul ini sudah dinputkan sebelumnya atau tidak
            if ($request->judul != $lapHki->judul) {
                $lapHkis = LapHki::where('judul', 'like', '%' . $judul . '%')->get();
                // (opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
                // kalau ada, cek data ini apakah diketuai oleh inputan yang diisi
                if (count($lapHkis) > 0) {
                    foreach ($lapHkis as $hki) {
                        $ketuaHki = TimExternHki::where('lap_hki_id', $hki->id)->where('nama', $request->nama_ketua)->where('isLeader', true)->get();
                        //kalau ada, redirect ke detail data yang sama.
                        if ($ketuaHki) {
                            Alert::toast('Kami melihat data yang sama, mungkin data ini yang ada maksud.', 'warning');
                            return redirect()->route('luaran-hki.show', $hki);
                        }
                    }
                }
            }

            // cek apakah ada kontribusi dari dosen UNIPA atau tidak
            if ($nidn_anggota != null) {
                if (Auth::user()->role_id >= 2) {
                    // termasuk didalam tim atau tidak
                    if (!in_array(Auth::user()->nidn, $nidn_anggota)) {
                        // kalau tidak kembalikan user ke form tambah
                        Alert::toast('Sebagai pengunggah anda harus menjadi bagian dari tim', 'warning');
                        return back()->withInput();
                    }
                }
            } else {
                //kalau anggota kosong
                // cek jika user selain admin
                if (Auth::user()->role_id >= 2) {
                    // kalau tidak ada kontribusi user dalam TIM kembalikan user ke form tambah
                    Alert::toast('Sebagai pengunggah anda harus menjadi bagian dari tim', 'warning');
                    return back()->withInput();
                }

                // Untuk admin
                // kalau tidak ada kontribusi dosen UNIPA kembalikan ke form tambah
                Alert::toast('Kami tidak melihat kontribusi dosen UNIPA dalam tim ini', 'warning');
                return back()->withInput();
            }

            // cek apakah ketua juga ditambahkan sebagai anggota atau tidak
            if ($nama_anggota != null) {
                foreach ($nama_anggota as $extern) {
                    //cek kesamaan inputan ketua dan anggota luar
                    $similiar = similar_text(strtolower($request->nama_ketua), strtolower($extern));
                    $hasil = $similiar / strlen($request->nama_ketua) * 100;
                    //jika tingkat kesamaan inputan 85% ke atas maka kembalikan inputan
                    if ((int)$hasil >= 80) {
                        Alert::toast('Ketua tidak bisa menjabat sebagai anggota dalam satu tim', 'error');
                        return back()->withInput();
                    }
                }
            }
        }

        // Ambil original filename dari file yang diupload
        // upload file ke folder laporan-publikasi
        $pathHki = $request->file('path_hki');
        if ($pathHki != null) {
            Storage::delete($lapHki->path_hki);
            $fileName = str_replace(" ", "-", $pathHki->getClientOriginalName());
            $pathHki = $pathHki->storeAs('laporan-hki', $fileName);
        } else {
            $pathHki = $lapHki->path_hki;
        }

        //simpan data ke tabel lap_publikasis
        LapHki::findOrFail($id)->update([
            'judul' => $judul,
            'tahun' => $request->tahun,
            'jenis_hki_id' => $request->jenis_hki_id,
            'path_hki' => $pathHki,
        ]);

        // dd($request->all());
        // hapus tim intern UNIPA lama
        TimInternHki::where('lap_hki_id', $id)->delete();
        // hapus tim Extern UNIPA lama
        TimExternHki::where('lap_hki_id', $id)->delete();

        //simpah data ketua baru update
        if ($request->checkKetua == null) {
            TimInternHki::create([
                'lap_hki_id' => $id,
                'nidn' => str_pad($request->nidn_ketua, 10, "0", STR_PAD_LEFT),
                'isLeader' => true,
            ]);
        } else {
            TimExternHki::create([
                'lap_hki_id' => $id,
                'nama' => $request->nama_ketua,
                'asal_institusi' => $request->asal_ketua,
                'isLeader' => true,
            ]);
        }

        //simpan data anggota baru update
        if ($nidn_anggota != null) {
            foreach ($nidn_anggota as $intern) {
                TimInternHki::create([
                    'lap_hki_id' => $id,
                    'nidn' => str_pad($intern, 10, "0", STR_PAD_LEFT),
                    'isLeader' => false,
                ]);
            }
        }

        if ($nama_anggota != null) {
            for ($i = 0; $i < count($nama_anggota); $i++) {
                TimExternHki::create([
                    'lap_hki_id' => $id,
                    'nama' => $nama_anggota[$i],
                    'asal_institusi' => $asal_anggota[$i],
                    'isLeader' => false,
                ]);
            }
        }


        Alert::success('Tersimpan', 'Perubahan data luaran HKI telah disimpan');
        return redirect()->route('luaran-hki.show', $id);
    }

    public function destroy($id)
    {
        $lapHki = LapHki::find($id);
        if ($lapHki == null) {
            return abort(404);
        }
        if (Auth::user()->role_id >= 2) {
            // abort user yang bukan pemilik atau pengupload data ini
            foreach ($lapHki->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua atau bukan pengunggah maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn && $lapHki->user_id != Auth::user()->id) {
                        return abort(403);
                    }
                }
            }
        }
        // hapus data tim dari dalam UNIPA
        $anggotaIntern = TimInternHki::where('lap_hki_id', $id)->get();
        foreach ($anggotaIntern as $intern) {
            $intern->delete();
        }
        // hapus data tim darl luar UNIPA
        $anggotaExtern = TimExternHki::where('lap_hki_id', $id)->get();
        foreach ($anggotaExtern as $extern) {
            $extern->delete();
        }
        // hapus file yang sudah diupload
        Storage::delete($lapHki->path_hki);
        // hapus data publikasi
        $lapHki->delete();
        Alert::success('Success', 'Data HKI telah dihapus!');
        return redirect()->route('luaran-hki.index');
    }
}
