<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;

class PengusulController extends Controller
{
    public function index()
    {
        $title = "Dashboard Pengusul";
        return view('pengusul.dashboard-pengusul', ['title' => $title]);
    }

    public function daftarUsulan()
    {
        $title = "Daftar Usulan Proposal";
        $dosen = Dosen::all();
        $user = Auth::user()->nidn;
        $pengusul = Dosen::where('nidn', $user)->get();
        $usulan = Proposal::where('user_id', Auth::user()->id)->get();
        return view('proposal.usulan', [
            'title' => $title,
            'dosen' => $dosen,
            'pengusul' => $pengusul,
            'usulan' => $usulan,
        ]);
    }

    public function lapKemajuan()
    {
        $title = "Laporan Kemajuan";
        return view('proposal.kemajuan', [
            'title' => $title,
        ]);
    }
    public function lapAkhir()
    {
        $title = "Laporan Akhir";
        return view('proposal.akhir', [
            'title' => $title,
        ]);
    }
}
