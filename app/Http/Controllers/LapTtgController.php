<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\LapTtg;
use App\Models\TimExternTtg;
use App\Models\TimInternTtg;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class LapTtgController extends Controller
{
    public function index()
    {
        $title = "Luaran Teknologi Tepat Guna";
        $lapTtgs = LapTtg::all();
        if (Auth::user()->role_id >= 2) {
            // ambil data ttg yang user punya baik itu sebagai pengupload maupun hanya berkontribusi
            $lapTtgs = LapTtg::wherehas('timIntern', function ($query) {
                $query->where('tim_intern_ttgs.nidn', Auth::user()->nidn);
            })->orWhere('user_id', Auth::user()->id)->get();
        }
        return view('ttg.luaranTtg', [
            'title' => $title,
            'lapTtgs' => $lapTtgs,
        ]);
    }

    public function create()
    {
        $title = "Tambah Luaran Teknologi Tepat Guna";
        $dosens = Dosen::where('nidn', 'not like', '%ADMIN%')->get();
        return view('ttg.createLuaranTtg', [
            'title' => $title,
            'dosens' => $dosens,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string', 'unique:lap_ttgs'],
            'tahun_perolehan' => ['required', 'numeric', 'digits:4'],
            'tahun_penerapan' => ['numeric', 'digits:4', 'nullable'],
            'path_ttg' => ['required', 'file', 'mimes:pdf', 'max:8192'],
            'path_bukti_sertifikat' => ['file', 'mimes:pdf', 'max:8192', 'nullable'],
        ], [
            'judul.unique' => "Judul artikel ini sudah ada",
            'mimes' => "Type file harus pdf",
            'max' => "File maksimal 8 MB",
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        //Ambil original filename dari file ttg yang diupload
        $pathTtg = $request->file('path_ttg');
        $fileNameTtg = str_replace(" ", "-", $pathTtg->getClientOriginalName());

        //cek apakah file dengan nama yang sama sudah ada didalam database
        $cekFileName = LapTtg::where('path_ttg', 'laporan-ttg/' . $fileNameTtg)->get();
        if (count($cekFileName) != 0) {
            Alert::toast('File luaran sudah ada', 'error');
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
            $lapTtgs = LapTtg::where('judul', 'like', '%' . $judul . '%')->get();
            //(opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
            if (count($lapTtgs) > 0) {
                //kalau ada, cek data ini apakah diketuai oleh inputan yang dipilih
                foreach ($lapTtgs as $ttg) {
                    $ketuaTtg = TimInternTtg::where('lap_ttg_id', $ttg->id)->where('nidn', $request->nidn_ketua)->where('isLeader', true)->get();
                    //kalau ada, redirect ke detail data yang sama.
                    if ($ketuaTtg) {
                        Alert::toast('Gagal menyimpan, Data yang diinputkan sama dengan data ini', 'warning');
                        return redirect()->route('luaran-ttg.show', $ttg);
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
            $lapTtgs = LapTtg::where('judul', 'like', '%' . $judul . '%')->get();
            //(opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
            //kalau ada, cek data ini apakah diketuai oleh inputan yang diisi
            if (count($lapTtgs) > 0) {
                foreach ($lapTtgs as $ttg) {
                    $ketuaTtg = TimExternTtg::where('lap_ttg_id', $ttg->id)->where('nama', $request->nama_ketua)->where('isLeader', true)->get();
                    //kalau ada, redirect ke detail data yang sama.
                    if ($ketuaTtg) {
                        Alert::toast('Kami melihat data yang sama, mungkin data ini yang ada maksud.', 'warning');
                        return redirect()->route('luaran-ttg.show', $ttg);
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
                Alert::toast('Minimal 1 penulis dalam tim harus dari dalam UNIPA', 'warning');
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

        //upload file ke folder laporan-ttg
        $pathTtg = $pathTtg->storeAs('laporan-ttg', $fileNameTtg);
        //Ambil original filename dari file bukti sertifikat yang diupload
        $pathBukti = $request->file('path_bukti_sertifikat');
        if ($pathBukti) {
            $fileNameBukti = str_replace(" ", "-", $pathBukti->getClientOriginalName());
            $pathBukti = $pathBukti->storeAs('laporan-bukti', $fileNameBukti);
        }

        //simpan data ke tabel lap_ttgs
        $newTtg = LapTtg::create([
            'judul' => $judul,
            'tahun_perolehan' => $request->tahun_perolehan,
            'tahun_penerapan' => $request->tahun_penerapan,
            'path_ttg' => $pathTtg,
            'path_bukti_sertifikat' => $pathBukti,
            'user_id' => Auth::user()->id,
        ]);

        //simpah data ketua
        if ($request->checkKetua == null) {
            TimInternTtg::create([
                'lap_ttg_id' => $newTtg->id,
                'nidn' => str_pad($request->nidn_ketua, 10, "0", STR_PAD_LEFT),
                'isLeader' => true,
            ]);
        } else {
            TimExternTtg::create([
                'lap_ttg_id' => $newTtg->id,
                'nama' => $request->nama_ketua,
                'asal_institusi' => $request->asal_ketua,
                'isLeader' => true,
            ]);
        }

        //simpan data anggota
        if ($nidn_anggota != null) {
            foreach ($nidn_anggota as $intern) {
                TimInternTtg::create([
                    'lap_ttg_id' => $newTtg->id,
                    'nidn' => str_pad($intern, 10, "0", STR_PAD_LEFT),
                    'isLeader' => false,
                ]);
            }
        }


        if ($nama_anggota != null) {
            for ($i = 0; $i < count($nama_anggota); $i++) {
                TimExternTtg::create([
                    'lap_ttg_id' => $newTtg->id,
                    'nama' => $nama_anggota[$i],
                    'asal_institusi' => $asal_anggota[$i],
                    'isLeader' => false,
                ]);
            }
        }

        Alert::success('Tersimpan', 'Luaran Teknologi Tepat Guna telah ditambahkan');
        return redirect()->route('luaran-ttg.show', $newTtg->id);
    }

    public function show($id)
    {
        $title = "Detail Luaran Teknologi Tepat Guna";
        $lapTtg = LapTtg::find($id);
        if ($lapTtg == null) {
            return abort(404);
        }
        if (Auth::user()->role_id >= 2) {
            $isUserAnggota = TimInternTtg::where('lap_ttg_id', $lapTtg->id)->where('nidn', Auth::user()->nidn)->get();
            if ($lapTtg->user_id != Auth::user()->id && count($isUserAnggota) < 1) {
                return abort(403);
            }
        }
        return view('ttg.showLuaranTtg', [
            'title' => $title,
            'lapTtg' => $lapTtg,
        ]);
    }

    public function edit($id)
    {
        $title = "Edit Luaran Teknologi Tepat Guna";
        $lapTtg = LapTtg::find($id);
        if ($lapTtg == null) {
            return abort(404);
        }
        $dosens = Dosen::where('nidn', 'not like', '%ADMIN%')->get();
        //cek user yang bukan admin
        if (Auth::user()->role_id >= 2) {
            // abort user yang bukan pemilik atau pengupload data ini
            foreach ($lapTtg->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua dan bukan pengunggah maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn && $lapTtg->user_id != Auth::user()->id) {
                        return abort(403);
                    }
                }
            }
        }
        return view('ttg.editLuaranTtg', [
            'title' => $title,
            'dosens' => $dosens,
            'lapTtg' => $lapTtg,
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $lapTtg = LapTtg::find($id);
        if ($lapTtg == null) {
            return abort(404);
        }
        //cek user yang bukan admin
        if (Auth::user()->role_id >= 2) {
            // abort user yang bukan pemilik atau pengupload data ini
            foreach ($lapTtg->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua dan bukan pengunggah maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn && $lapTtg->user_id != Auth::user()->id) {
                        return abort(403);
                    }
                }
            }
        }
        // cek ada perubahan judul atau perubahan nama ketua
        // kalau ada perubahan di salah satunya baru dilakukakan pengecekan ada data yang sama atau tidak
        // cek ada isi file unggahan atau tidak
        // kalau ada hapus yang lama lalu upload yang baru diinputkan
        // simpah perubahan data lap Ttg
        // hapus semua anggota yang ada lalu tambahkan kembali data anggota yang baru diinputkan
        $rules = [
            'tahun_perolehan' => ['required', 'numeric', 'digits:4'],
            'tahun_penerapan' => ['numeric', 'digits:4', 'nullable'],
            'path_bukti_sertifikat' => ['file', 'mimes:pdf', 'max:8192', 'nullable'],
        ];

        //cek apakah ada perubahan pada judul artikel, kalau ada maka tambahkan validator
        if ($request->judul != $lapTtg->judul) {
            $rules['judul'] = ['required', 'string', 'unique:lap_ttgs'];
        }

        // cek apakah ada file unggahan
        if ($request->path_ttg != null) {
            $rules['path_ttg'] = ['required', 'file', 'mimes:pdf', 'max:8192'];
        }

        $validator = Validator::make($request->all(), $rules, [
            'judul.unique' => "Judul artikel ini sudah ada",
            'mimes' => "Type file harus pdf",
            'max' => "File maksimal 8 MB",
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
            if ($request->judul != $lapTtg->judul) {
                $lapTtgs = LapTtg::where('judul', 'like', '%' . $judul . '%')->get();
                // (opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
                if (count($lapTtgs) > 0) {
                    //kalau ada, cek data ini apakah diketuai oleh inputan yang dipilih
                    foreach ($lapTtgs as $ttg) {
                        $ketuaTtg = TimInternTtg::where('lap_ttg_id', $ttg->id)->where('nidn', $request->nidn_ketua)->where('isLeader', true)->first();
                        //kalau ada, redirect ke detail data yang sama.
                        if ($ttg->id != $id && $ketuaTtg) {
                            Alert::toast('Gagal menyimpan, Data yang diinputkan sama dengan data ini', 'warning');
                            return redirect()->route('luaran-ttg.show', $ttg);
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
            if ($request->judul != $lapTtg->judul) {
                $lapTtgs = LapTtg::where('judul', 'like', '%' . $judul . '%')->get();
                // (opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
                // kalau ada, cek data ini apakah diketuai oleh inputan yang diisi
                if (count($lapTtgs) > 0) {
                    foreach ($lapTtgs as $ttg) {
                        $ketuaTtg = TimExternTtg::where('lap_ttg_id', $ttg->id)->where('nama', $request->nama_ketua)->where('isLeader', true)->first();
                        //kalau ada, redirect ke detail data yang sama.
                        if ($ttg->id != $id && $ketuaTtg) {
                            Alert::toast('Kami melihat data yang sama, mungkin data ini yang anda maksud.', 'warning');
                            return redirect()->route('luaran-ttg.show', $ttg);
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
        // upload file ke folder laporan-ttg
        $pathTtg = $request->file('path_ttg');
        if ($pathTtg != null) {
            Storage::delete($lapTtg->path_ttg);
            $fileName = str_replace(" ", "-", $pathTtg->getClientOriginalName());
            $pathTtg = $pathTtg->storeAs('laporan-ttg', $fileName);
        } else {
            $pathTtg = $lapTtg->path_ttg;
        }

        $pathBukti = $request->file('path_bukti_sertifikat');
        if ($pathBukti != null) {
            Storage::delete($lapTtg->path_bukti_sertifikat);
            $fileName = str_replace(" ", "-", $pathBukti->getClientOriginalName());
            $pathBukti = $pathBukti->storeAs('laporan-bukti', $fileName);
        } else {
            $pathBukti = $lapTtg->path_bukti_sertifikat;
        }

        //simpan data ke tabel lap_ttgs
        LapTtg::findOrFail($id)->update([
            'judul' => $judul,
            'tahun_perolehan' => $request->tahun_perolehan,
            'tahun_penerapan' => $request->tahun_penerapan,
            'path_ttg' => $pathTtg,
            'path_bukti_sertifikat' => $pathBukti,
        ]);

        // dd($request->all());
        // hapus tim intern UNIPA lama
        TimInternTtg::where('lap_ttg_id', $id)->delete();
        // hapus tim Extern UNIPA lama
        TimExternTtg::where('lap_ttg_id', $id)->delete();

        //simpah data ketua baru update
        if ($request->checkKetua == null) {
            TimInternTtg::create([
                'lap_ttg_id' => $id,
                'nidn' => str_pad($request->nidn_ketua, 10, "0", STR_PAD_LEFT),
                'isLeader' => true,
            ]);
        } else {
            TimExternTtg::create([
                'lap_ttg_id' => $id,
                'nama' => $request->nama_ketua,
                'asal_institusi' => $request->asal_ketua,
                'isLeader' => true,
            ]);
        }

        //simpan data anggota baru update
        if ($nidn_anggota != null) {
            foreach ($nidn_anggota as $intern) {
                TimInternTtg::create([
                    'lap_ttg_id' => $id,
                    'nidn' => str_pad($intern, 10, "0", STR_PAD_LEFT),
                    'isLeader' => false,
                ]);
            }
        }

        if ($nama_anggota != null) {
            for ($i = 0; $i < count($nama_anggota); $i++) {
                TimExternTtg::create([
                    'lap_ttg_id' => $id,
                    'nama' => $nama_anggota[$i],
                    'asal_institusi' => $asal_anggota[$i],
                    'isLeader' => false,
                ]);
            }
        }


        Alert::success('Tersimpan', 'Perubahan data luaran teknologi tepat guna telah disimpan');
        return redirect()->route('luaran-ttg.show', $id);
    }

    public function destroy($id)
    {
        $lapTtg = LapTtg::find($id);
        if ($lapTtg == null) {
            return abort(404);
        }
        if (Auth::user()->role_id >= 2) {
            // abort user yang bukan pemilik atau pengupload data ini
            foreach ($lapTtg->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua atau bukan pengunggah maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn && $lapTtg->user_id != Auth::user()->id) {
                        return abort(403);
                    }
                }
            }
        }
        // hapus data tim dari dalam UNIPA
        $anggotaIntern = TimInternTtg::where('lap_ttg_id', $id)->get();
        foreach ($anggotaIntern as $intern) {
            $intern->delete();
        }
        // hapus data tim darl luar UNIPA
        $anggotaExtern = TimExternTtg::where('lap_ttg_id', $id)->get();
        foreach ($anggotaExtern as $extern) {
            $extern->delete();
        }
        // hapus file yang sudah diupload
        Storage::delete($lapTtg->path_ttg);
        Storage::delete($lapTtg->path_bukti_sertifikat);
        // hapus data Ttg
        $lapTtg->delete();
        Alert::success('Success', 'Data Teknologi Tepat Guna telah dihapus!');
        return redirect()->route('luaran-ttg.index');
    }
}
