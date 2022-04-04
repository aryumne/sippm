<?php

namespace App\Http\Controllers;

use App\Models\TimInternKegiatan;
use App\Models\Dosen;
use App\Models\Faculty;
use App\Models\Kegiatan;
use App\Models\SumberDana;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class KegiatanController extends Controller
{

    public function index($jenis)
    {
        $sumberDana = SumberDana::all();
        $faculties = Faculty::all();
        $dosen = Dosen::where('nidn', 'not like', '%ADMIN%')->get();

        if ($jenis == "penelitian") {
            $title = "Daftar Penelitian";
            $kegiatan = Kegiatan::where('jenis_kegiatan', 1);
            $dataKegiatans = $kegiatan->FilterPenelitian(request(['faculty_id', 'tahun_kegiatan', 'sumber_dana']))->get();
            if (Auth::user()->role_id == 2) {
                $kegiatan = Kegiatan::whereHas('timIntern', function ($query) {
                    $query->where('tim_intern_kegiatans.nidn', Auth::user()->nidn)->where('jenis_kegiatan', 1);
                })->orwhere('user_id', Auth::user()->id);
                // FIlter jika ada request
                $dataKegiatans = $kegiatan->FilterPenelitian(request(['faculty_id', 'tahun_kegiatan', 'sumber_dana']))->get();

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
            $kegiatan = Kegiatan::where('jenis_kegiatan', 2);
            $dataKegiatans = $kegiatan->FilterPenelitian(request(['faculty_id', 'tahun_kegiatan', 'sumber_dana']))->get();
            if (Auth::user()->role_id == 2) {
                $kegiatan = Kegiatan::whereHas('timIntern', function ($query) {
                    $query->where('tim_intern_kegiatans.nidn', Auth::user()->nidn)->where('jenis_kegiatan', 2);
                })->orwhere('user_id', Auth::user()->id);
                // FIlter jika ada request
                $dataKegiatans = $kegiatan->FilterPenelitian(request(['faculty_id', 'tahun_kegiatan', 'sumber_dana']))->get();

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
        dd($jenis);
        $jenis = "";
        if($kegiatan->jenis_kegiatan == 1) {
            $jenis = "Penelitian";
        } else {
            $jenis = "PkM";
        }
        $title = "Tambah ". $jenis;
        $sumberDana = SumberDana::all();
        $faculties = Faculty::all();
        $dosen = Dosen::where('nidn', 'not like', '%ADMIN%')->get();
        return view('proposal.pkm', [
            'title' => $title,
            'jenis' => $jenis,
            'dosen' => $dosen,
            'pkm' => $dataKegiatan,
            'sumberDana' => $sumberDana,
            'faculties' => $faculties,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string'],
            'dana' => ['required'],
            'jenisKegiatan' => ['required'],
            'tanggal_kegiatan' => ['required', 'string'],
            'path_kegiatan' => ['required', 'file', 'mimes:pdf', 'max:8192'],
            'sumberDana' => ['required', 'numeric'],
            'nidn_anggota' => ['array'],
            'nidn_anggota.*' => ['string', 'digits:10'],
        ], [
            'required' => 'Tidak boleh kosong.',
            'file' => 'Type file harus .pdf.',
            'max' => 'Ukuran file maksimal 8 Mb.',
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $dataUser = Dosen::find(Auth::user()->nidn);
        $date = strtotime($request->tanggal_kegiatan);
        $date = date('Y-m-d', $date);
        $jenisKegiatan = $request->jenisKegiatan;
        $dana = str_replace(".", "", $request->dana);
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
        if($agt != null)
        {
            foreach ($agt as $nidn_anggota) {
                if ($nidn_anggota == Auth::user()->nidn) {
                    Alert::toast('Ketua tidak bisa jadi ', 'error');
                    return back()->withErrors($validator)->withInput();
                }
            }
        }

        $path_kegiatan = $path_kegiatan->storeAs('laporan-kegiatan', str_replace(" ", "-", $filename));

        $kegiatan = Kegiatan::create([
            'sumber_id' => $request->sumberDana,
            'judul_kegiatan' => $request->judul,
            'jumlah_dana' => $dana,
            'jenis_kegiatan' => $jenisKegiatan,
            'path_kegiatan' => $path_kegiatan,
            'prodi_id' => $dataUser->prodi_id,
            'tanggal_kegiatan' => $date,
            'user_id' => Auth::user()->id,
        ]);

        if($agt != null)
        {
            foreach ($agt as $nidn_anggota) {
                if ($kegiatan) {
                    AnggotaKegiatan::create([
                        'nidn' => str_pad($nidn_anggota, 10, "0", STR_PAD_LEFT),
                        'kegiatan_id' => $kegiatan->id,
                    ]);
                }
            }
        }

        if ($jenisKegiatan == 1) {
            Alert::success('Berhasil', 'Laporan Penelitian berhasil ditambahkan');
        } else {
            Alert::success('Berhasil', 'Laporan PKM berhasil ditambahkan');
        }
        return back();

    }

    public function show(Kegiatan $kegiatan)
    {
        $jenis = "";
        if($kegiatan->jenis_kegiatan == 1) {
             $jenis = "Penelitian";
        } else {
            $jenis = "PkM";
        }
        $title = "Detail ". $jenis;
        return view('proposal.showKegiatan',
            [
                'title' => $title,
                'jenis' => $jenis,
                'kegiatan' => $kegiatan,
            ]);

    }
    public function edit(Kegiatan $kegiatan)
    {
        if (Auth::user()->id != $kegiatan->user_id) {
            return abort(403);
        }
        $jenis = "";
        if($kegiatan->jenis_kegiatan == 1) {
            $jenis = "Penelitian";
        } else {
            $jenis = "PkM";
        }
        $title = "Edit Data ". $jenis;
        $dosen = Dosen::where('nidn', 'not like', '%ADMIN%')->get();
        $sumberDana = SumberDana::all();

        return view('proposal.editKegiatan',
            [
                'title' => $title,
                'jenis' => $jenis,
                'kegiatan' => $kegiatan,
                'dosen' => $dosen,
                'sumberDana' => $sumberDana,
            ]);

    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        if (Auth::user()->id != $kegiatan->user_id) {
            return abort(403);
        }
        $rules = [
            'judul' => ['required', 'string'],
            'dana' => ['required'],
            'tanggal_kegiatan' => ['required', 'string'],
            'sumberDana' => ['required', 'numeric'],
            'nidn_anggota' => ['array'],
            'nidn_anggota.*' => ['string', 'digits:10'],
        ];

        if ($request->path_kegiatan != null) {
            $rules['path_kegiatan'] = ['required', 'file', 'mimes:pdf', 'max:8192'];
        }

        $validator = Validator::make($request->all(), $rules,
        [
            'required' => 'Tidak boleh kosong.',
            'file' => 'Type file harus .pdf.',
            'max' => 'Ukuran file maksimal 8 Mb.',
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $dataUser = Dosen::find(Auth::user()->nidn);
        $date = $request->tanggal_kegiatan;
        $dana = str_replace(".", "", $request->dana);

        $path_kegiatan = $request->file('path_kegiatan');
        if ($path_kegiatan != null) {
            $fileName = $path_kegiatan->getClientOriginalName();
            $path_kegiatan = $path_kegiatan->storeAs('laporan-kegiatan', str_replace(" ", "-", $fileName));
        } else {
            $path_kegiatan = $kegiatan->path_kegiatan;
        }

        if($request->nidn_anggota != null)
        {
            foreach ($request->nidn_anggota as $nidn_anggota) {
                if ($nidn_anggota == Auth::user()->nidn) {
                    Alert::toast('Ketua tidak bisa jadi ', 'error');
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
            'tanggal_kegiatan' => $date,
            'user_id' => Auth::user()->id,
        ]);

        //delete tim pelaksana kegiatan ini di database anggota kegiatan
        foreach ($kegiatan->anggotaKegiatan as $dsn) {
            AnggotaKegiatan::where('nidn', $dsn->pivot->nidn)->where('kegiatan_id', $kegiatan->id)->delete();
        }

        if($request->nidn_anggota != null)
        {
            foreach ($request->nidn_anggota as $agt) {
                AnggotaKegiatan::create([
                    'kegiatan_id' => $kegiatan->id,
                    'nidn' => str_pad($agt, 10, "0", STR_PAD_LEFT),
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

}
