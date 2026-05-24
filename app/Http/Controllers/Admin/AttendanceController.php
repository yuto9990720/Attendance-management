<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\RestTime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());

        $attendances = Attendance::with('user','restTimes')
            ->where('date', $date)
            ->get();

        $prevDate = Carbon::parse($date)->subDay()->toDateString();
        $nextDate = Carbon::parse($date)->addDay()->toDateString();

        return view('admin.attendance.index', compact('date', 'attendances', 'prevDate', 'nextDate'));
    }

    public function detail($attendance)
    {
        $attendance = Attendance::with('user', 'restTimes')->find($attendance);

        return view('admin.attendance.detail', compact('attendance'));
    }

    public function update(Request $request, $attendance)
    {
        $attendance = Attendance::find($attendance);

        // 勤怠情報を更新
        $attendance->update([
            'check_in'  => $request->check_in,
            'check_out' => $request->check_out,
            'remarks'   => $request->remarks,
        ]);

        // 休憩情報を更新
        if($request->rest_time_id) {
            foreach($request->rest_time_id as $index => $restTimeId) {
                RestTime::find($restTimeId)->update([
                    'rest_start' => $request->rest_start[$index],
                    'rest_end'   => $request->rest_end[$index],
                ]);
            }
        }

        return redirect()->route('admin.attendance.index');
    }

}