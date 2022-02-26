<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\HasilAudit;
use App\Models\HasilMonev;
use App\Models\Monev;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ReviewerController extends Controller
{
    public function index()
    {
        $title = " Dashboard Reviewer";
        $notifications = Schedule::whereIn('jadwal_id', [4, 5])->get();
        return view('reviewer.dashboard-reviewer', [
            'title' => $title,
            'notifications' => $notifications,
        ]);
    }

    public function auditProposals()
    {
        $title = "Daftar Penilaian Proposal";
        $userId = Auth::user()->id;
        $currentYear = date("Y");
        $userAudit = Audit::where('user_id', $userId)->whereYear('created_at', $currentYear)->where('status', 1)->get();
        // dd($userAudit);
        return view('reviewer.auditProposals', [
            'title' => $title,
            'userAudit' => $userAudit,
        ]);
    }

    public function formAudit($id)
    {
        if (!Gate::allows('penilaian_proposal')) {
            abort(403);
        }

        $title = "Form Penilaian Proposal";
        //ambil data proposal dari data audit berdasarkan id audit
        $proposal = Audit::find($id);
        //ambil data tim pengusul dari data proposal diatas
        // foreach ($proposal->proposal->dosen as $dsn) {
        //     // ambil data ketua pengusul
        //     if ($dsn->pivot->isLeader == true) {
        //         //ambill prodi dan fakultasnya
        //         echo 'Ketua Peneliti : ' . $dsn->nama . '</br>';
        //         echo 'Prodi : ' . $dsn->prodi->nama_prodi . '</br>';
        //         echo 'Fakultas : ' . $dsn->prodi->faculty->nama_faculty . '</br>';
        //     }
        // }
        return view('reviewer.formAudit', [
            'title' => $title,
            'id' => $id,
            'proposal' => $proposal,
        ]);
    }

    public function editFormAudit($id)
    {
        if (!Gate::allows('penilaian_proposal')) {
            abort(403);
        }

        $title = "Edit Penilaian Proposal";
        //ambil data proposal dari data audit berdasarkan id audit
        $hasilAudit = HasilAudit::find($id);
        return view('reviewer.editAudit', [
            'title' => $title,
            'hasilAudit' => $hasilAudit,
        ]);
    }

    public function storeAudit(Request $request, $id)
    {
        if (!Gate::allows('penilaian_proposal')) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'perumusan' => 'required',
            'peluang' => 'required',
            'metode' => 'required',
            'tinjauan' => 'required',
            'kelayakan' => 'required',
            'total' => 'required',
            'komentar' => 'required',
        ]);
        if ($validator->fails()) {
            Alert::toast('Gagal menyimpan, cek kembali form penilaian', 'error');
            return back()->withErrors($validator)->withInput();
        }
        $jumlahViewed = HasilAudit::where('audit_id', $id)->get();
        if (count($jumlahViewed) == 2) {
            Alert::toast('Proposal ini sudah direview 2 kali', 'error');
            return back()->withErrors($validator)->withInput();
        }

        HasilAudit::create([
            'perumusan' => $request->perumusan,
            'peluang' => $request->peluang,
            'metode' => $request->metode,
            'tinjauan' => $request->tinjauan,
            'kelayakan' => $request->kelayakan,
            'total' => $request->total,
            'komentar' => $request->komentar,
            'audit_id' => $id,
        ]);

        Alert::success('Tersimpan', 'Data penilaian proposal telah diubah');
        return redirect()->route('reviewer.audit.proposals');

    }

    public function updateAudit(Request $request, $id)
    {
        if (!Gate::allows('penilaian_proposal')) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'perumusan' => 'required',
            'peluang' => 'required',
            'metode' => 'required',
            'tinjauan' => 'required',
            'kelayakan' => 'required',
            'total' => 'required',
            'komentar' => 'required',
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal menyimpan, cek kembali form penilaian', 'error');
            return back()->withErrors($validator)->withInput();
        }

        HasilAudit::findOrFail($id)->update([
            'perumusan' => $request->perumusan,
            'peluang' => $request->peluang,
            'metode' => $request->metode,
            'tinjauan' => $request->tinjauan,
            'kelayakan' => $request->kelayakan,
            'total' => $request->total,
            'komentar' => $request->komentar,
        ]);

        Alert::success('Tersimpan', 'Data penilaian proposal telah disimpan');
        return redirect()->route('reviewer.audit.proposals');

    }

    public function monevKemajuan()
    {
        $title = "Daftar MONEV Laporan Kemajuan ";
        $userId = Auth::user()->id;
        $currentYear = date("Y");
        $userAudit = Monev::where('user_id', $userId)->whereYear('created_at', $currentYear)->where('status', 1)->get();
        return view('reviewer.monevKemajuan', [
            'title' => $title,
            'userAudit' => $userAudit,
        ]);
    }

    public function formMonev($id)
    {
        if (!Gate::allows('monev_laporan_kemajuan')) {
            abort(403);
        }

        $title = "Form MONEV Laporan Kemajuan ";
        $monev = Monev::find($id);
        return view('reviewer.formMonev', [
            'title' => $title,
            'id' => $id,
            'monev' => $monev,
        ]);
    }

    public function editFormMonev($id)
    {
        if (!Gate::allows('monev_laporan_kemajuan')) {
            abort(403);
        }

        $title = "Edit MONEV Laporan Kemajuan ";
        $hasilMonev = HasilMonev::find($id);
        return view('reviewer.editMonev', [
            'title' => $title,
            'hasilMonev' => $hasilMonev,
        ]);
    }

    public function storeMonev(Request $request, $id)
    {
        if (!Gate::allows('monev_laporan_kemajuan')) {
            abort(403);
        }

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'luaran_wajib' => 'required',
            'komentar_luaran_wajib' => 'required',
            'luaran_tambahan' => 'required',
            'komentar_luaran_tambahan' => 'required',
            'kesesuaian' => 'required',
            'komentar_kesesuaian' => 'required',
            'komentar' => 'required',
            'total' => 'required',
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal menyimpan, cek kembali form penilaian', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $jumlahViewed = HasilMonev::where('monev_id', $id)->get();
        if (count($jumlahViewed) == 1) {
            Alert::toast('Laporan ini sudah dimonev', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $luaran_wajib['nilai'] = $request->luaran_wajib;
        $luaran_wajib['komentar'] = $request->komentar_luaran_wajib;
        $luaran_tambahan['nilai'] = $request->luaran_tambahan;
        $luaran_tambahan['komentar'] = $request->komentar_luaran_tambahan;
        $kesesuaian['nilai'] = $request->kesesuaian;
        $kesesuaian['komentar'] = $request->komentar_kesesuaian;

        HasilMonev::create([
            'luaran_wajib' => $luaran_wajib,
            'luaran_tambahan' => $luaran_tambahan,
            'kesesuaian' => $kesesuaian,
            'total' => $request->total,
            'komentar' => $request->komentar,
            'monev_id' => $id,
        ]);

        Alert::success('Tersimpan', 'Data monev laporan telah disimpan');
        return redirect()->route('reviewer.monev.kemajuan');

    }

    public function updateMonev(Request $request, $id)
    {
        if (!Gate::allows('monev_laporan_kemajuan')) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'luaran_wajib' => 'required',
            'komentar_luaran_wajib' => 'required',
            'luaran_tambahan' => 'required',
            'komentar_luaran_tambahan' => 'required',
            'kesesuaian' => 'required',
            'komentar_kesesuaian' => 'required',
            'total' => 'required',
            'komentar' => 'required',
        ]);

        if ($validator->fails()) {
            Alert::toast('Gagal menyimpan, cek kembali form penilaian', 'error');
            return back()->withErrors($validator)->withInput();
        }

        $luaran_wajib['nilai'] = $request->luaran_wajib;
        $luaran_wajib['komentar'] = $request->komentar_luaran_wajib;
        $luaran_tambahan['nilai'] = $request->luaran_tambahan;
        $luaran_tambahan['komentar'] = $request->komentar_luaran_tambahan;
        $kesesuaian['nilai'] = $request->kesesuaian;
        $kesesuaian['komentar'] = $request->komentar_kesesuaian;

        HasilMonev::findOrFail($id)->update([
            'luaran_wajib' => $luaran_wajib,
            'luaran_tambahan' => $luaran_tambahan,
            'kesesuaian' => $kesesuaian,
            'total' => $request->total,
            'komentar' => $request->komentar,
        ]);

        Alert::success('Tersimpan', 'Data monev laporan telah diubah');
        return redirect()->route('reviewer.monev.kemajuan');

    }

}
