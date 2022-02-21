<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Penjadwalan akses';
        $schedules = Schedule::all();
        // dd($schedules);
        return view('admin.schedules', [
            'title' => $title,
            'schedules' => $schedules,
        ]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validator = Validator::make($request->all(), [
            'started_at' => 'required',
            'finished_at' => 'required',
        ]);

        if ($validator->fails()) {
            Alert::toast('Update jadwal gagal', 'error');
            return back()->withError($validator);
        }

        Schedule::findOrFail($schedule->id)->update([
            'started_at' => $request->started_at,
            'finished_at' => $request->finished_at,
            'jadwal_id' => $schedule->jadwal_id,
            'user_id' => $schedule->user_id,
        ]);
        Alert::success('Berhasil', 'Jadwal akses ' . $schedule->jadwal->nama_jadwal . ' berhasil dibuat');
        return back();
    }

}
