<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\LapNaskah;
use Illuminate\Http\Request;
use App\Models\TimExternNaskah;
use App\Models\TimInternNaskah;
use App\Http\Controllers\Controller;
use App\Models\Peruntukan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class LapNaskahController extends Controller
{
    public function index()
    {
        $title = "Luaran Naskah Akademik";
        $lapNaskahs = LapNaskah::all();
        if (Auth::user()->role_id >= 2) {
            // ambil data Naskah Akademik yang user punya baik itu sebagai pengupload maupun hanya berkontribusi
            $lapNaskahs = LapNaskah::wherehas('timIntern', function ($query) {
                $query->where('tim_intern_naskahs.nidn', Auth::user()->nidn);
            })->orWhere('user_id', Auth::user()->id)->get();
        }
        return view('naskah.luaranNaskah', [
            'title' => $title,
            'lapNaskahs' => $lapNaskahs,
        ]);
    }

    public function create()
    {
        $title = "Tambah Luaran Naskah Akademik";
        $peruntukans = Peruntukan::all();
        $dosens = Dosen::where('nidn', 'not like', '%ADMIN%')->get();
        return view('naskah.createLuaranNaskah', [
            'title' => $title,
            'peruntukans' => $peruntukans,
            'dosens' => $dosens,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string', 'unique:lap_naskahs'],
            'tahun' => ['required', 'numeric', 'digits:4'],
            'peruntukan_id' => ['required'],
            'path_naskah' => ['required', 'file', 'mimes:pdf', 'max:8192'],
        ], [
            'judul.unique' => "Judul artikel ini sudah ada",
            'path_naskah.mimes' => "Type file harus pdf",
            'path_naskah.max' => "File maksimal 8 MB",
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        //Ambil original filename dari file yang diupload
        $pathNaskah = $request->file('path_naskah');
        $fileName = str_replace(" ", "-", $pathNaskah->getClientOriginalName());
        //cek apakah file dengan nama yang sama sudah ada didalam database
        $cekFileName = LapNaskah::where('path_naskah', 'naskah-akademik/' . $fileName)->get();
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
            $lapNaskahs = LapNaskah::where('judul', 'like', '%' . $judul . '%')->get();
            //(opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
            if (count($lapNaskahs) > 0) {
                //kalau ada, cek data ini apakah diketuai oleh inputan yang dipilih
                foreach ($lapNaskahs as $naskah) {
                    $ketuaNaskah = TimInternNaskah::where('lap_naskah_id', $naskah->id)->where('nidn', $request->nidn_ketua)->where('isLeader', true)->get();
                    //kalau ada, redirect ke detail data yang sama.
                    if ($ketuaNaskah) {
                        Alert::toast('Gagal menyimpan, Data yang diinputkan sama dengan data ini', 'warning');
                        return redirect()->route('luaran-naskah.show', $naskah);
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
            $lapNaskahs = LapNaskah::where('judul', 'like', '%' . $judul . '%')->get();
            //(opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
            //kalau ada, cek data ini apakah diketuai oleh inputan yang diisi
            if (count($lapNaskahs) > 0) {
                foreach ($lapNaskahs as $naskah) {
                    $ketuaNaskah = TimExternNaskah::where('lap_naskah_id', $naskah->id)->where('nama', $request->nama_ketua)->where('isLeader', true)->get();
                    //kalau ada, redirect ke detail data yang sama.
                    if ($ketuaNaskah) {
                        Alert::toast('Kami melihat data yang sama, mungkin data ini yang ada maksud.', 'warning');
                        return redirect()->route('luaran-naskah.show', $naskah);
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

        //upload file ke folder naskah-akademik
        $pathNaskah = $pathNaskah->storeAs('naskah-akademik', $fileName);
        //simpan data ke tabel lap_naskahs
        $newNaskah = LapNaskah::create([
            'judul' => $judul,
            'tahun' => $request->tahun,
            'peruntukan_id' => $request->peruntukan_id,
            'path_naskah' => $pathNaskah,
            'user_id' => Auth::user()->id,
        ]);

        //simpah data ketua
        if ($request->checkKetua == null) {
            TimInternNaskah::create([
                'lap_naskah_id' => $newNaskah->id,
                'nidn' => str_pad($request->nidn_ketua, 10, "0", STR_PAD_LEFT),
                'isLeader' => true,
            ]);
        } else {
            TimExternNaskah::create([
                'lap_naskah_id' => $newNaskah->id,
                'nama' => $request->nama_ketua,
                'asal_institusi' => $request->asal_ketua,
                'isLeader' => true,
            ]);
        }

        //simpan data anggota
        if ($nidn_anggota != null) {
            foreach ($nidn_anggota as $intern) {
                TimInternNaskah::create([
                    'lap_naskah_id' => $newNaskah->id,
                    'nidn' => str_pad($intern, 10, "0", STR_PAD_LEFT),
                    'isLeader' => false,
                ]);
            }
        }


        if ($nama_anggota != null) {
            for ($i = 0; $i < count($nama_anggota); $i++) {
                TimExternNaskah::create([
                    'lap_naskah_id' => $newNaskah->id,
                    'nama' => $nama_anggota[$i],
                    'asal_institusi' => $asal_anggota[$i],
                    'isLeader' => false,
                ]);
            }
        }

        Alert::success('Tersimpan', 'Luaran Naskah Akademik telah ditambahkan');
        return redirect()->route('luaran-naskah.show', $newNaskah->id);
    }

    public function show($id)
    {
        $title = "Detail Luaran Naskah Akademik";
        $lapNaskah = LapNaskah::find($id);
        if ($lapNaskah == null) {
            return abort(404);
        }
        if (Auth::user()->role_id >= 2) {
            $isUserAnggota = TimInternNaskah::where('lap_naskah_id', $lapNaskah->id)->where('nidn', Auth::user()->nidn)->get();
            if ($lapNaskah->user_id != Auth::user()->id && count($isUserAnggota) < 1) {
                return abort(403);
            }
        }
        return view('naskah.showLuaranNaskah', [
            'title' => $title,
            'lapNaskah' => $lapNaskah,
        ]);
    }

    public function edit($id)
    {
        $title = "Edit Luaran Naskah Akademik";
        $lapNaskah = LapNaskah::find($id);
        if ($lapNaskah == null) {
            return abort(404);
        }
        $peruntukans = Peruntukan::all();
        $dosens = Dosen::where('nidn', 'not like', '%ADMIN%')->get();
        //cek user yang bukan admin
        if (Auth::user()->role_id >= 2) {
            // abort user yang bukan pemilik atau pengupload data ini
            foreach ($lapNaskah->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua dan bukan pengunggah maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn && $lapNaskah->user_id != Auth::user()->id) {
                        return abort(403);
                    }
                }
            }
        }
        return view('naskah.editLuaranNaskah', [
            'title' => $title,
            'peruntukans' => $peruntukans,
            'dosens' => $dosens,
            'lapNaskah' => $lapNaskah,
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $lapNaskah = LapNaskah::find($id);
        if ($lapNaskah == null) {
            return abort(404);
        }
        //cek user yang bukan admin
        if (Auth::user()->role_id >= 2) {
            // abort user yang bukan pemilik atau pengupload data ini
            foreach ($lapNaskah->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua dan bukan pengunggah maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn && $lapNaskah->user_id != Auth::user()->id) {
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
            'peruntukan_id' => ['required'],
        ];

        //cek apakah ada perubahan pada judul artikel, kalau ada maka tambahkan validator
        if ($request->judul != $lapNaskah->judul) {
            $rules['judul'] = ['required', 'string', 'unique:lap_naskahs'];
        }
        // cek apakah ada file unggahan
        if ($request->path_naskah != null) {
            $rules['path_naskah'] = ['required', 'file', 'mimes:pdf', 'max:8192'];
        }

        $validator = Validator::make($request->all(), $rules, [
            'judul.unique' => "Judul artikel ini sudah ada",
            'path_naskah.mimes' => "Type file harus pdf",
            'path_naskah.max' => "File maksimal 8 MB",
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
            if ($request->judul != $lapNaskah->judul) {
                $lapNaskahs = LapNaskah::where('judul', 'like', '%' . $judul . '%')->get();
                // (opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
                if (count($lapNaskahs) > 0) {
                    //kalau ada, cek data ini apakah diketuai oleh inputan yang dipilih
                    foreach ($lapNaskahs as $naskah) {
                        $ketuaNaskah = TimInternNaskah::where('lap_naskah_id', $naskah->id)->where('nidn', $request->nidn_ketua)->where('isLeader', true)->first();
                        //kalau ada, redirect ke detail data yang sama.
                        if ($naskah->id != $id && $ketuaNaskah) {
                            Alert::toast('Gagal menyimpan, Data yang diinputkan sama dengan data ini', 'warning');
                            return redirect()->route('luaran-naskah.show', $naskah);
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
            if ($request->judul != $lapNaskah->judul) {
                $lapNaskahs = LapNaskah::where('judul', 'like', '%' . $judul . '%')->get();
                // (opsional)cek data yang sama menggunakan method similar_text dari php jika query diatas kurang meyakinkan
                // kalau ada, cek data ini apakah diketuai oleh inputan yang diisi
                if (count($lapNaskahs) > 0) {
                    foreach ($lapNaskahs as $naskah) {
                        $ketuaNaskah = TimExternNaskah::where('lap_naskah_id', $naskah->id)->where('nama', $request->nama_ketua)->where('isLeader', true)->first();
                        //kalau ada, redirect ke detail data yang sama.
                        if ($naskah->id != $id && $ketuaNaskah) {
                            Alert::toast('Kami melihat data yang sama, mungkin data ini yang anda maksud.', 'warning');
                            return redirect()->route('luaran-naskah.show', $naskah);
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
        // upload file ke folder naskah-akademik
        $pathNaskah = $request->file('path_naskah');
        if ($pathNaskah != null) {
            Storage::delete($lapNaskah->path_naskah);
            $fileName = str_replace(" ", "-", $pathNaskah->getClientOriginalName());
            $pathNaskah = $pathNaskah->storeAs('naskah-akademik', $fileName);
        } else {
            $pathNaskah = $lapNaskah->path_naskah;
        }

        //simpan data ke tabel lap_naskahs
        LapNaskah::findOrFail($id)->update([
            'judul' => $judul,
            'tahun' => $request->tahun,
            'peruntukan_id' => $request->peruntukan_id,
            'path_naskah' => $pathNaskah,
        ]);

        // dd($request->all());
        // hapus tim intern UNIPA lama
        TimInternNaskah::where('lap_naskah_id', $id)->delete();
        // hapus tim Extern UNIPA lama
        TimExternNaskah::where('lap_naskah_id', $id)->delete();

        //simpah data ketua baru update
        if ($request->checkKetua == null) {
            TimInternNaskah::create([
                'lap_naskah_id' => $id,
                'nidn' => str_pad($request->nidn_ketua, 10, "0", STR_PAD_LEFT),
                'isLeader' => true,
            ]);
        } else {
            TimExternNaskah::create([
                'lap_naskah_id' => $id,
                'nama' => $request->nama_ketua,
                'asal_institusi' => $request->asal_ketua,
                'isLeader' => true,
            ]);
        }

        //simpan data anggota baru update
        if ($nidn_anggota != null) {
            foreach ($nidn_anggota as $intern) {
                TimInternNaskah::create([
                    'lap_naskah_id' => $id,
                    'nidn' => str_pad($intern, 10, "0", STR_PAD_LEFT),
                    'isLeader' => false,
                ]);
            }
        }

        if ($nama_anggota != null) {
            for ($i = 0; $i < count($nama_anggota); $i++) {
                TimExternNaskah::create([
                    'lap_naskah_id' => $id,
                    'nama' => $nama_anggota[$i],
                    'asal_institusi' => $asal_anggota[$i],
                    'isLeader' => false,
                ]);
            }
        }


        Alert::success('Tersimpan', 'Perubahan data luaran naskah akademik telah disimpan');
        return redirect()->route('luaran-naskah.show', $id);
    }

    public function destroy($id)
    {
        $lapNaskah = LapNaskah::find($id);
        if ($lapNaskah == null) {
            return abort(404);
        }
        if (Auth::user()->role_id >= 2) {
            // abort user yang bukan pemilik atau pengupload data ini
            foreach ($lapNaskah->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua atau bukan pengunggah maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn && $lapNaskah->user_id != Auth::user()->id) {
                        return abort(403);
                    }
                }
            }
        }
        // hapus data tim dari dalam UNIPA
        $anggotaIntern = TimInternNaskah::where('lap_naskah_id', $id)->get();
        foreach ($anggotaIntern as $intern) {
            $intern->delete();
        }
        // hapus data tim darl luar UNIPA
        $anggotaExtern = TimExternNaskah::where('lap_naskah_id', $id)->get();
        foreach ($anggotaExtern as $extern) {
            $extern->delete();
        }
        // hapus file yang sudah diupload
        Storage::delete($lapNaskah->path_naskah);
        // hapus data publikasi
        $lapNaskah->delete();
        Alert::success('Success', 'Data Naskah Akademik telah dihapus!');
        return redirect()->route('luaran-naskah.index');
    }
}
