<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LapBuku;
use App\Models\TimExternBuku;
use App\Models\TimInternBuku;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class LapBukuController extends Controller
{
    public function index()
    {
        $title = "Luaran Buku";
        $lapBukus = LapBuku::all();
        if (Auth::user()->role_id >= 2) {
            // ambil data buku yang user punya baik itu sebagai pengupload maupun hanya berkontribusi
            $lapBukus = LapBuku::wherehas('timIntern', function ($query) {
                $query->where('tim_intern_bukus.nidn', Auth::user()->nidn);
            })->orWhere('user_id', Auth::user()->id)->get();
        }
        return view('buku.luaranBuku', [
            'title' => $title,
            'lapBukus' => $lapBukus,
        ]);
    }

    public function create()
    {
        $title = "Tambah Luaran Buku";
        $dosens = Dosen::where('nidn', 'not like', '%ADMIN%')->get();
        return view('buku.createLuaranBuku', [
            'title' => $title,
            'dosens' => $dosens,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string', 'unique:lap_bukus'],
            'isbn' => ['string', 'nullable'],
            'penerbit' => ['required', 'string'],
            'tahun' => ['required', 'numeric', 'digits:4'],
            'path_buku' => ['required', 'file', 'mimes:pdf', 'max:8192'],
        ], [
            'judul.unique' => "Judul artikel ini sudah ada",
            'path_buku.mimes' => "Type file harus pdf",
            'path_buku.max' => "File maksimal 8 MB",
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        //Ambil original filename dari file yang diupload
        $pathBuku = $request->file('path_buku');
        $fileName = str_replace(" ", "-", $pathBuku->getClientOriginalName());
        //cek apakah file dengan nama yang sama sudah ada didalam database
        $cekFileName = LapBuku::where('path_buku', 'laporan-buku/' . $fileName)->get();
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
            $lapBukus = LapBuku::where('judul', 'like', '%' . $judul . '%')->get();
            //(opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
            if (count($lapBukus) > 0) {
                //kalau ada, cek data ini apakah diketuai oleh inputan yang dipilih
                foreach ($lapBukus as $buku) {
                    $ketuaBuku = TimInternBuku::where('lap_buku_id', $buku->id)->where('nidn', $request->nidn_ketua)->where('isLeader', true)->get();
                    //kalau ada, redirect ke detail data yang sama.
                    if ($ketuaBuku) {
                        Alert::toast('Gagal menyimpan, Data yang diinputkan sama dengan data ini', 'warning');
                        return redirect()->route('luaran-buku.show', $buku);
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
            $lapBukus = LapBuku::where('judul', 'like', '%' . $judul . '%')->get();
            //(opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
            //kalau ada, cek data ini apakah diketuai oleh inputan yang diisi
            if (count($lapBukus) > 0) {
                foreach ($lapBukus as $buku) {
                    $ketuaBuku = TimExternBuku::where('lap_buku_id', $buku->id)->where('nama', $request->nama_ketua)->where('isLeader', true)->get();
                    //kalau ada, redirect ke detail data yang sama.
                    if ($ketuaBuku) {
                        Alert::toast('Kami melihat data yang sama, mungkin data ini yang ada maksud.', 'warning');
                        return redirect()->route('luaran-buku.show', $buku);
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

        //upload file ke folder laporan-buku
        $pathBuku = $pathBuku->storeAs('laporan-buku', $fileName);
        //simpan data ke tabel lap_bukus
        $newBuku = LapBuku::create([
            'judul' => $judul,
            'isbn' => $request->isbn,
            'penerbit' => $request->penerbit,
            'tahun' => $request->tahun,
            'path_buku' => $pathBuku,
            'user_id' => Auth::user()->id,
        ]);

        //simpah data ketua
        if ($request->checkKetua == null) {
            TimInternBuku::create([
                'lap_buku_id' => $newBuku->id,
                'nidn' => str_pad($request->nidn_ketua, 10, "0", STR_PAD_LEFT),
                'isLeader' => true,
            ]);
        } else {
            TimExternBuku::create([
                'lap_buku_id' => $newBuku->id,
                'nama' => $request->nama_ketua,
                'asal_institusi' => $request->asal_ketua,
                'isLeader' => true,
            ]);
        }

        //simpan data anggota
        if ($nidn_anggota != null) {
            foreach ($nidn_anggota as $intern) {
                TimInternBuku::create([
                    'lap_buku_id' => $newBuku->id,
                    'nidn' => str_pad($intern, 10, "0", STR_PAD_LEFT),
                    'isLeader' => false,
                ]);
            }
        }


        if ($nama_anggota != null) {
            for ($i = 0; $i < count($nama_anggota); $i++) {
                TimExternBuku::create([
                    'lap_buku_id' => $newBuku->id,
                    'nama' => $nama_anggota[$i],
                    'asal_institusi' => $asal_anggota[$i],
                    'isLeader' => false,
                ]);
            }
        }

        Alert::success('Tersimpan', 'Luaran Buku telah ditambahkan');
        return redirect()->route('luaran-buku.show', $newBuku->id);
    }

    public function show($id)
    {
        $title = "Detail Luaran Buku";
        $lapBuku = LapBuku::find($id);
        if ($lapBuku == null) {
            return abort(404);
        }
        if (Auth::user()->role_id >= 2) {
            $isUserAnggota = TimInternBuku::where('lap_buku_id', $lapBuku->id)->where('nidn', Auth::user()->nidn)->get();
            if ($lapBuku->user_id != Auth::user()->id && count($isUserAnggota) < 1) {
                return abort(403);
            }
        }
        return view('buku.showLuaranBuku', [
            'title' => $title,
            'lapBuku' => $lapBuku,
        ]);
    }

    public function edit($id)
    {
        $title = "Edit Luaran Buku";
        $lapBuku = LapBuku::find($id);
        if ($lapBuku == null) {
            return abort(404);
        }
        $dosens = Dosen::where('nidn', 'not like', '%ADMIN%')->get();
        //cek user yang bukan admin
        if (Auth::user()->role_id >= 2) {
            // abort user yang bukan pemilik atau pengupload data ini
            foreach ($lapBuku->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua dan bukan pengunggah maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn && $lapBuku->user_id != Auth::user()->id) {
                        return abort(403);
                    }
                }
            }
        }
        return view('buku.editLuaranBuku', [
            'title' => $title,
            'dosens' => $dosens,
            'lapBuku' => $lapBuku,
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $lapBuku = LapBuku::find($id);
        if ($lapBuku == null) {
            return abort(404);
        }
        //cek user yang bukan admin
        if (Auth::user()->role_id >= 2) {
            // abort user yang bukan pemilik atau pengupload data ini
            foreach ($lapBuku->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua dan bukan pengunggah maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn && $lapBuku->user_id != Auth::user()->id) {
                        return abort(403);
                    }
                }
            }
        }
        // cek ada perubahan judul atau perubahan nama ketua
        // kalau ada perubahan di salah satunya baru dilakukakan pengecekan ada data yang sama atau tidak
        // cek ada isi file unggahan atau tidak
        // kalau ada hapus yang lama lalu upload yang baru diinputkan
        // simpah perubahan data lap Buku
        // hapus semua anggota yang ada lalu tambahkan kembali data anggota yang baru diinputkan
        $rules = [
            'isbn' => ['string', 'nullable'],
            'penerbit' => ['required', 'string'],
            'tahun' => ['required', 'numeric', 'digits:4'],
        ];

        //cek apakah ada perubahan pada judul artikel, kalau ada maka tambahkan validator
        if ($request->judul != $lapBuku->judul) {
            $rules['judul'] = ['required', 'string', 'unique:lap_bukus'];
        }
        // cek apakah ada file unggahan
        if ($request->path_buku != null) {
            $rules['path_buku'] = ['required', 'file', 'mimes:pdf', 'max:8192'];
        }

        $validator = Validator::make($request->all(), $rules, [
            'judul.unique' => "Judul artikel ini sudah ada",
            'path_buku.mimes' => "Type file harus pdf",
            'path_buku.max' => "File maksimal 8 MB",
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
            if ($request->judul != $lapBuku->judul) {
                $lapBukus = LapBuku::where('judul', 'like', '%' . $judul . '%')->get();
                // (opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
                if (count($lapBukus) > 0) {
                    //kalau ada, cek data ini apakah diketuai oleh inputan yang dipilih
                    foreach ($lapBukus as $buku) {
                        $ketuaBuku = TimInternBuku::where('lap_buku_id', $buku->id)->where('nidn', $request->nidn_ketua)->where('isLeader', true)->first();
                        //kalau ada, redirect ke detail data yang sama.
                        if ($buku->id != $id && $ketuaBuku) {
                            Alert::toast('Gagal menyimpan, Data yang diinputkan sama dengan data ini', 'warning');
                            return redirect()->route('luaran-buku.show', $buku);
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
            if ($request->judul != $lapBuku->judul) {
                $lapBukus = LapBuku::where('judul', 'like', '%' . $judul . '%')->get();
                // (opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
                // kalau ada, cek data ini apakah diketuai oleh inputan yang diisi
                if (count($lapBukus) > 0) {
                    foreach ($lapBukus as $buku) {
                        $ketuaBuku = TimExternBuku::where('lap_buku_id', $buku->id)->where('nama', $request->nama_ketua)->where('isLeader', true)->first();
                        //kalau ada, redirect ke detail data yang sama.
                        if ($buku->id != $id && $ketuaBuku) {
                            Alert::toast('Kami melihat data yang sama, mungkin data ini yang anda maksud.', 'warning');
                            return redirect()->route('luaran-buku.show', $buku);
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
        // upload file ke folder laporan-buku
        $pathBuku = $request->file('path_buku');
        if ($pathBuku != null) {
            Storage::delete($lapBuku->path_buku);
            $fileName = str_replace(" ", "-", $pathBuku->getClientOriginalName());
            $pathBuku = $pathBuku->storeAs('laporan-buku', $fileName);
        } else {
            $pathBuku = $lapBuku->path_buku;
        }

        //simpan data ke tabel lap_bukus
        LapBuku::findOrFail($id)->update([
            'judul' => $judul,
            'isbn' => $request->isbn,
            'penerbit' => $request->penerbit,
            'tahun' => $request->tahun,
            'path_buku' => $pathBuku,
        ]);

        // dd($request->all());
        // hapus tim intern UNIPA lama
        TimInternBuku::where('lap_buku_id', $id)->delete();
        // hapus tim Extern UNIPA lama
        TimExternBuku::where('lap_buku_id', $id)->delete();

        //simpah data ketua baru update
        if ($request->checkKetua == null) {
            TimInternBuku::create([
                'lap_buku_id' => $id,
                'nidn' => str_pad($request->nidn_ketua, 10, "0", STR_PAD_LEFT),
                'isLeader' => true,
            ]);
        } else {
            TimExternBuku::create([
                'lap_buku_id' => $id,
                'nama' => $request->nama_ketua,
                'asal_institusi' => $request->asal_ketua,
                'isLeader' => true,
            ]);
        }

        //simpan data anggota baru update
        if ($nidn_anggota != null) {
            foreach ($nidn_anggota as $intern) {
                TimInternBuku::create([
                    'lap_buku_id' => $id,
                    'nidn' => str_pad($intern, 10, "0", STR_PAD_LEFT),
                    'isLeader' => false,
                ]);
            }
        }

        if ($nama_anggota != null) {
            for ($i = 0; $i < count($nama_anggota); $i++) {
                TimExternBuku::create([
                    'lap_buku_id' => $id,
                    'nama' => $nama_anggota[$i],
                    'asal_institusi' => $asal_anggota[$i],
                    'isLeader' => false,
                ]);
            }
        }


        Alert::success('Tersimpan', 'Perubahan data luaran buku telah disimpan');
        return redirect()->route('luaran-buku.show', $id);
    }

    public function destroy($id)
    {
        $lapBuku = LapBuku::find($id);
        if ($lapBuku == null) {
            return abort(404);
        }
        if (Auth::user()->role_id >= 2) {
            // abort user yang bukan pemilik atau pengupload data ini
            foreach ($lapBuku->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua atau bukan pengunggah maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn && $lapBuku->user_id != Auth::user()->id) {
                        return abort(403);
                    }
                }
            }
        }
        // hapus data tim dari dalam UNIPA
        $anggotaIntern = TimInternBuku::where('lap_buku_id', $id)->get();
        foreach ($anggotaIntern as $intern) {
            $intern->delete();
        }
        // hapus data tim darl luar UNIPA
        $anggotaExtern = TimExternBuku::where('lap_buku_id', $id)->get();
        foreach ($anggotaExtern as $extern) {
            $extern->delete();
        }
        // hapus file yang sudah diupload
        Storage::delete($lapBuku->path_buku);
        // hapus data Buku
        $lapBuku->delete();
        Alert::success('Success', 'Data Buku telah dihapus!');
        return redirect()->route('luaran-buku.index');
    }
}
