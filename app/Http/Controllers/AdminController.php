<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Audit;
use App\Models\Dosen;
use App\Models\Monev;
use App\Models\Proposal;
use App\Models\LapKemajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    public function index()
    {
        $title = "Dashboard Admin";
        return view('admin.dashboard-admin', ['title' => $title]);
    }

    public function reviewers()
    {
        $title = "Daftar Akun Reviewer";
        $dosen = Dosen::all();
        $reviewers = User::where('role_id', 3)->get();
        return view('admin.reviewers', [
            'title' => $title,
            'dosen' => $dosen,
            'reviewers' => $reviewers,
        ]);
    }

    // tambah akun reviewer
    public function storeReviewer(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(),
            [
                'nidn' => ['required', 'numeric', 'unique:users'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'password' => ['required', Rules\Password::defaults()],
            ]);
        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }
        $nidn = Dosen::where('nidn', $request->nidn)->get();
        if (count($nidn) > 0) {
            User::create([
                'nidn' => $request->nidn,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 3,
            ]);
            Alert::success('Akun Berhasil Ditambahkan.', 'Varifikasi email sebelum digunakan');
            return back();

        } else {
            Alert::toast('NIDN tidak valid', 'error');
            return back();
        }
    }

    public function audits()
    {
        $title = "Penilaian Proposal";
        $reviewers = User::where('role_id', 3)->get();
        //ambil tahun sekarang
        $getYear = date("Y");
        $proposals = Proposal::whereYear('created_at', $getYear)->get();

        //ambil proposal yang belum memiliki 2 reviewer
        $newProposals = collect([]);
        foreach ($proposals as $propo) {
            $reviewersOfProposal = Audit::where('proposal_id', $propo->id)->whereYear('created_at', $getYear)->get();
            if (count($reviewersOfProposal) < 2) {
                $newProposals->push($propo);
            }
        }
        //ambil reviewer yang belum ditugaskan sebanyak 8 proposal
        $newReviewers = collect([]);
        foreach ($reviewers as $rvw) {
            $newReviewers->push($rvw);
            // $proposalReviewed = Audit::where('user_id', $rvw->id)->whereYear('created_at', $getYear)->get();
            // if (count($proposalReviewed) < 8) {
            //     $newReviewers->push($rvw);
            // }
        }

        return view('admin.audit', [
            'title' => $title,
            'reviewers' => $reviewers,
            'newReviewers' => $newReviewers,
            'newProposals' => $newProposals,
        ]);
    }

    public function hasilAudits()
    {
        $title = "Penilaian Proposal";
        $reviewerAudits = Audit::all();
        //ambil tahun sekarang
        $getYear = date("Y");
        $proposals = Proposal::whereYear('created_at', $getYear)->get();

        return view('admin.hasilAudit', [
            'title' => $title,
            'reviewerAudits' => $reviewerAudits,
            'proposals' => $proposals,
        ]);
    }

    public function auditStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required'],
            'proposal_id' => ['required', 'array'],
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $user = $request->user_id;
        $proposals = $request->proposal_id;
        $getYear = date("Y");
        // $proposalReviewed = Audit::where('user_id', $user)->whereYear('created_at', $getYear)->get();
        // if(count($proposalReviewed) >= 8)
        // {
        //     Alert::toast('Reviewer ini telah ditugaskan mereview 8 proposal', 'error');
        //     return back();
        // }

        // if(count($proposalReviewed) + count($proposals) > 8)
        // {
        //     Alert::toast('Reviewer hanya dapat ditugaskan review 8 proposal', 'error');
        //     return back();
        // }

        foreach ($proposals as $propo) {
            $reviewersOfProposal = Audit::where('proposal_id', $propo)->whereYear('created_at', $getYear)->get();
            if (count($reviewersOfProposal) >= 2) {
                Alert::toast('Proposal ini telah direview oleh 2 reviewer', 'error');
                return back();
            }

            $sameProposalReviewer = Audit::where('proposal_id', $propo)->where('user_id', $user)->get();
            if (count($sameProposalReviewer) >= 1) {
                Alert::toast('Proposal tidak dapat direview 2 kali oleh reviewer yang sama', 'error');
                return back();
            }
        }

        foreach ($proposals as $pps) {
            Audit::create([
                'user_id' => $request->user_id,
                'proposal_id' => $pps,
                'status' => 1,
            ]);
        }

        Alert::success('Success', 'Data reviewer proposal telah disimpan');
        return back();

    }

    public function auditUpdateStatus(Request $request, $id)
    {
        Audit::findOrFail($id)->update([
            'status' => $request->status,
        ]);
        if ($request->status == 0) {
            Alert::toast('Akses Reviewer di penilaian proposal ini dinonaktifkan', 'success');
        } else {
            Alert::toast('Akses Reviewer di penilaian proposal ini diaktifkan', 'success');

        }
        return back();
    }

    public function monevs()
    {
        $title = "Penilaian Proposal";
        $reviewers = User::where('role_id', 3)->get();
        //ambil tahun sekarang
        $getYear = date("Y");
        $kemajuans = LapKemajuan::whereYear('created_at', $getYear)->get();

        //ambil laporan kemajuan yang belum memiliki reviewer
        $newKemajuans = collect([]);
        foreach ($kemajuans as $kmj) {
            $reviewersOfKemajuan = Monev::where('lap_kemajuan_id', $kmj->id)->whereYear('created_at', $getYear)->get();
            if (count($reviewersOfKemajuan) == 0) {
                $newKemajuans->push($kmj);
            }
        }
        return view('admin.monev', [
            'title' => $title,
            'reviewers' => $reviewers,
            'kemajuans' => $kemajuans,
            'newKemajuans' => $newKemajuans,
        ]);
    }

    public function hasilMonevs()
    {
        $title = "Penilaian Proposal";
        //ambil tahun sekarang
        $getYear = date("Y");
        $kemajuans = LapKemajuan::whereYear('created_at', $getYear)->get();
        return view('admin.hasilMonev', [
            'title' => $title,
            'kemajuans' => $kemajuans,
        ]);
    }

    public function monevStore(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'user_id' => ['required'],
            'lap_kemajuan_id' => ['required', 'array'],
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal Menyimpan, cek kembali inputan anda', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $user = $request->user_id;
        $kemajuans = $request->lap_kemajuan_id;
        $getYear = date("Y");

        foreach ($kemajuans as $kmj) {
            $reviewersOfKemajuans = Monev::where('lap_kemajuan_id', $kmj)->whereYear('created_at', $getYear)->get();
            if (count($reviewersOfKemajuans) >= 1) {
                Alert::toast('Laporan ini telah dimonev oleh 1 reviewer', 'error');
                return back();
            }
        }

        foreach ($kemajuans as $kmj) {
            Monev::create([
                'user_id' => $request->user_id,
                'lap_kemajuan_id' => $kmj,
                'status' => 1,
            ]);
        }

        Alert::success('Success', 'Data reviewer laporan kemajuan telah disimpan');
        return back();

    }

    public function monevUpdateStatus(Request $request, $id)
    {
        Monev::findOrFail($id)->update([
            'status' => $request->status,
        ]);
        if ($request->status == 0) {
            Alert::toast('Akses Reviewer di monev laporan ini dinonaktifkan', 'success');
        } else {
            Alert::toast('Akses Reviewer di monev laporan ini diaktifkan', 'success');

        }
        return back();

    }

}
