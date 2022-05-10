<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Faculty;
use App\Models\Kegiatan;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use App\Models\TimInternKegiatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class KegiatanController extends Controller
{

    public function index($jenis)
    {
        $sumberDana = SumberDana::all();
        $faculties = Faculty::all();
        if ($jenis == "penelitian") {
            $title = "Daftar Penelitian";
            $kegiatan = Kegiatan::where('jenis_kegiatan', true);
            $dataKegiatans = $kegiatan->FilterPenelitian(request(['faculty_id', 'tahun', 'sumber_dana']))->get();
            if (Auth::user()->role_id >= 2) {
                $kegiatan = Kegiatan::whereHas('timIntern', function ($query) {
                    $query->where('tim_intern_kegiatans.nidn', Auth::user()->nidn)->where('jenis_kegiatan', true);
                })->orwhere('user_id', Auth::user()->id);
                $kegiatan->where('jenis_kegiatan', true);
                // FIlter jika ada request
                $dataKegiatans = $kegiatan->FilterPenelitian(request(['faculty_id', 'tahun', 'sumber_dana']))->get();
            }
            return view('kegiatan.kegiatans', [
                'title' => $title,
                'jenis' => $jenis,
                'dataKegiatans' => $dataKegiatans,
                'sumberDana' => $sumberDana,
                'faculties' => $faculties,
            ]);
        } else if ($jenis == "pkm") {
            $title = "Daftar PkM";
            $kegiatan = Kegiatan::where('jenis_kegiatan', false);
            $dataKegiatans = $kegiatan->FilterPenelitian(request(['faculty_id', 'tahun', 'sumber_dana']))->get();
            if (Auth::user()->role_id >= 2) {
                $kegiatan = Kegiatan::whereHas('timIntern', function ($query) {
                    $query->where('tim_intern_kegiatans.nidn', Auth::user()->nidn)->where('jenis_kegiatan', false);
                })->orwhere('user_id', Auth::user()->id);
                $kegiatan->where('jenis_kegiatan', false);
                // FIlter jika ada request
                $dataKegiatans = $kegiatan->FilterPenelitian(request(['faculty_id', 'tahun', 'sumber_dana']))->get();
            }
            return view('kegiatan.kegiatans', [
                'title' => $title,
                'jenis' => $jenis,
                'dataKegiatans' => $dataKegiatans,
                'sumberDana' => $sumberDana,
                'faculties' => $faculties,
            ]);
        } else {
            return abort(404);
        }
    }

    public function create($jenis)
    {
        $title = "Tambah " . $jenis;
        $sumberDana = SumberDana::all();
        $dosens = Dosen::where('nidn', 'not like', '%ADMIN%')->get();
        return view('kegiatan.createKegiatan', [
            'title' => $title,
            'jenis' => $jenis,
            'dosens' => $dosens,
            'sumberDana' => $sumberDana,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'jenisKegiatan' => ['required'],
            'judul' => ['required', 'string', 'unique:kegiatans,judul_kegiatan'],
            'sumberDana' => ['required', 'numeric'],
            'dana' => ['required'],
            'tahun' => ['required', 'string'],
            'nidn_ketua' => ['required', 'string'],
            'nidn_anggota' => ['array'],
            'nidn_anggota.*' => ['string', 'digits:10'],
            'path_kegiatan' => ['required', 'file', 'mimes:pdf', 'max:8192'],
        ], [
            'judul.unique' => 'Judul kegiatan ini sudah ada',
            'required' => 'Tidak boleh kosong.',
            'file' => 'Type file harus .pdf.',
            'max' => 'Ukuran file maksimal 8 Mb.',
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        // ambil data ketua dari nidn yang diinputkan
        $dataUser = Dosen::find($request->nidn_ketua);
        // remove "." dari input jumlah dana
        $dana = str_replace(".", "", $request->dana);
        // Jenis kegiatan
        $jenisKegiatan = $request->jenisKegiatan == "penelitian" ? true : false;
        // ambil file yang diunggah
        $path_kegiatan = $request->file('path_kegiatan');
        $filename = $path_kegiatan->getClientOriginalName();
        $cekFileName = Kegiatan::where('path_kegiatan', 'laporan-kegiatan/' . str_replace(" ", "-", $filename))->get();
        if (count($cekFileName) != 0) {
            Alert::toast('File ini sudah ada', 'error');
            return back()->withInput();
        }

        //ambil data nidn anggota
        $agt = $request->nidn_anggota;
        //cek kesamaan nidn pengusul dan anggota yang dipilih
        if ($agt != null) {
            foreach ($agt as $nidn_anggota) {
                if ($nidn_anggota == $request->nidn_ketua) {
                    Alert::toast('Ketua tidak bisa menjabat sebagai anggota sekaligus', 'error');
                    return back()->withErrors($validator)->withInput();
                }
            }
        }

        $path_kegiatan = $path_kegiatan->storeAs('laporan-kegiatan', str_replace(" ", "-", $filename));

        $kegiatan = Kegiatan::create([
            'judul_kegiatan' => $request->judul,
            'sumber_id' => $request->sumberDana,
            'jumlah_dana' => $dana,
            'jenis_kegiatan' => $jenisKegiatan,
            'path_kegiatan' => $path_kegiatan,
            'prodi_id' => $dataUser->prodi_id,
            'tahun' => $request->tahun,
            'user_id' => Auth::user()->id,
        ]);

        TimInternKegiatan::create([
            'nidn' => str_pad($request->nidn_ketua, 10, "0", STR_PAD_LEFT),
            'kegiatan_id' => $kegiatan->id,
            'isLeader' => true,
        ]);

        if ($agt != null) {
            foreach ($agt as $nidn_anggota) {
                if ($kegiatan) {
                    TimInternKegiatan::create([
                        'nidn' => str_pad($nidn_anggota, 10, "0", STR_PAD_LEFT),
                        'kegiatan_id' => $kegiatan->id,
                        'isLeader' => false,
                    ]);
                }
            }
        }

        if ($jenisKegiatan == 1) {
            Alert::success('Berhasil', 'Laporan Penelitian berhasil ditambahkan');
        } else {
            Alert::success('Berhasil', 'Laporan PKM berhasil ditambahkan');
        }
        return redirect()->route('kegiatan.show', $kegiatan);
    }

    public function show(Kegiatan $kegiatan)
    {
        $jenis = "";
        if ($kegiatan->jenis_kegiatan == true) {
            $jenis = "Penelitian";
        } else {
            $jenis = "PkM";
        }

        // kick user yang bukan bagian dari tim pelaksana kecuali admin
        if (Auth::user()->role_id >= 2) {
            $isUserTim = TimInternKegiatan::where('kegiatan_id', $kegiatan->id)->where('nidn', Auth::user()->nidn)->get();
            if ($kegiatan->user_id != Auth::user()->id && count($isUserTim) < 1) {
                return abort(403);
            }
        }
        $title = "Detail " . $jenis;
        return view(
            'kegiatan.showKegiatan',
            [
                'title' => $title,
                'jenis' => $jenis,
                'kegiatan' => $kegiatan,
            ]
        );
    }

    public function edit(Kegiatan $kegiatan)
    {
        // cek apakah user ini pengusul
        if (Auth::user()->role_id >= 2) {
            // cek ketua dari data ini
            foreach ($kegiatan->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn) {
                        return abort(403);
                    }
                }
            }
        }
        $jenis = "";
        if ($kegiatan->jenis_kegiatan == 1) {
            $jenis = "Penelitian";
        } else {
            $jenis = "PkM";
        }
        $title = "Edit Data " . $jenis;
        $sumberDana = SumberDana::all();
        $dosens = Dosen::where('nidn', 'not like', '%ADMIN%')->get();

        return view(
            'kegiatan.editKegiatan',
            [
                'title' => $title,
                'jenis' => $jenis,
                'kegiatan' => $kegiatan,
                'dosens' => $dosens,
                'sumberDana' => $sumberDana,
            ]
        );
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        // cek apakah user ini pengusul atau reviewer
        if (Auth::user()->role_id >= 2) {
            // cek ketua dari data ini
            foreach ($kegiatan->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn) {
                        return abort(403);
                    }
                }
            }
        }
        $rules = [
            'dana' => ['required'],
            'tahun' => ['required', 'string'],
            'sumberDana' => ['required', 'numeric'],
            'nidn_anggota' => ['array'],
            'nidn_anggota.*' => ['string', 'digits:10'],
        ];

        if ($request->path_kegiatan != null) {
            $rules['path_kegiatan'] = ['required', 'file', 'mimes:pdf', 'max:8192'];
        }

        if ($request->judul != $kegiatan->judul_kegiatan) {
            $rules['judul'] = ['required', 'string', 'unique:kegiatans,judul_kegiatan'];
        }

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'judul.unique' => 'Judul kegiatan ini sudah ada',
                'required' => 'Tidak boleh kosong.',
                'file' => 'Type file harus .pdf.',
                'max' => 'Ukuran file maksimal 8 Mb.',
            ]
        );

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        // ambil data ketua
        $dataUser = Dosen::find($request->nidn_ketua);

        // remove "." dari inputan jumlah dana
        $dana = str_replace(".", "", $request->dana);

        $path_kegiatan = $request->file('path_kegiatan');
        if ($path_kegiatan != null) {
            Storage::delete($kegiatan->path_kegiatan);
            $fileName = $path_kegiatan->getClientOriginalName();
            $path_kegiatan = $path_kegiatan->storeAs('laporan-kegiatan', str_replace(" ", "-", $fileName));
        } else {
            $path_kegiatan = $kegiatan->path_kegiatan;
        }

        if ($request->nidn_anggota != null) {
            foreach ($request->nidn_anggota as $nidn_anggota) {
                if ($nidn_anggota == $request->nidn_ketua) {
                    Alert::toast('Ketua tidak bisa menjabat sebagai anggota sekaligus', 'error');
                    return back()->withErrors($validator)->withInput();
                }
            }
        }

        $kegiatan->update([
            'sumber_id' => $request->sumberDana,
            'judul_kegiatan' => $request->judul,
            'jumlah_dana' => $dana,
            'path_kegiatan' => $path_kegiatan,
            'prodi_id' => $dataUser->prodi_id,
            'tahun' => $request->tahun,
            'user_id' => Auth::user()->id,
        ]);

        //delete tim pelaksana kegiatan ini di database anggota kegiatan
        foreach ($kegiatan->timIntern as $dsn) {
            TimInternKegiatan::where('nidn', $dsn->pivot->nidn)->where('kegiatan_id', $kegiatan->id)->delete();
        }

        TimInternKegiatan::create([
            'kegiatan_id' => $kegiatan->id,
            'nidn' => str_pad($request->nidn_ketua, 10, "0", STR_PAD_LEFT),
            'isLeader' => true,
        ]);

        if ($request->nidn_anggota != null) {
            foreach ($request->nidn_anggota as $agt) {
                TimInternKegiatan::create([
                    'kegiatan_id' => $kegiatan->id,
                    'nidn' => str_pad($agt, 10, "0", STR_PAD_LEFT),
                    'isLeader' => false,
                ]);
            }
        }

        if ($request->jenis_kegiatan == 1) {
            Alert::success('Berhasil', 'Laporan Penelitian berhasil diubah');
        } else {
            Alert::success('Berhasil', 'Laporan PKM berhasil diubah');
        }
        return redirect()->route('kegiatan.show', $kegiatan);
    }

    public function destroy(Kegiatan $kegiatan)
    {
        // cek apakah user ini pengusul atau reviewer
        if (Auth::user()->role_id >= 2) {
            // cek ketua dari data ini
            foreach ($kegiatan->timIntern as $ketua) {
                if ($ketua->pivot->isLeader == false) {
                    // kalau user bukan ketua maka jangan berikan akses
                    if (Auth::user()->nidn == $ketua->nidn) {
                        return abort(403);
                    }
                }
            }
        }
        $jenis = $kegiatan->jenis_kegiatan == true ? 'penelitian' : 'pkm';
        // hapus data anggota
        $anggotaIntern = TimInternKegiatan::where('kegiatan_id', $kegiatan->id)->get();
        foreach ($anggotaIntern as $intern) {
            $intern->delete();
        }

        // hapus file unggahan
        Storage::delete($kegiatan->path_kegiatan);

        // hapus data kegiatan
        $kegiatan->delete();

        // redirect ke halaman utama
        Alert::success('Success', 'Data Publikasi telah dihapus!');
        return redirect()->route('kegiatan.index', $jenis);
    }
}
