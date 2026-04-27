<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RestTime;

class AttendanceController extends Controller
{
    public function index()
    {
        // 出勤情報の表示
        $user=Auth::user();
        $today=Carbon::now()->toDateString();

        $attendance=Attendance::where('user_id', $user->id)->where('date', $today)->first();

        $status=$attendance ? $attendance->status : '勤務外';

        return view('user.attendance.index', compact('today','attendance', 'status'));  
      
    }

    public function checkIn(Request $request)
    {
        // 出勤処理
        $user=Auth::user();
        $today=Carbon::now();

        Attendance::create([
            'user_id'=>$user->id,
            'date'=>$today->toDateString(),
            'check_in'=>$today->format('H:i:s'),
            'status'=>'出勤中',
        ]);

        return redirect()->route('attendance.index');
    }

    public function checkOut(Request $request)
    {
        $user=Auth::user();
        $today=Carbon::now();

        $attendance=Attendance::where('user_id', $user->id)->where('date', $today->toDateString())->first();

        $attendance->update([
            'check_out'=>$today->format('H:i:s'),
            'status'=>'退勤済',
        ]);

        return redirect()->route('attendance.index');
    }

    public function restIn(Request $request)
    {
        $user=Auth::user();
        $today=Carbon::now();

        $attendance=Attendance::where('user_id', $user->id)->where('date', $today->toDateString())->first();

        RestTime::create([
            'attendance_id'=>$attendance->id,
            'rest_start'=>$today->format('H:i:s'),
        ]);

        $attendance->update([
            'status'=>'休憩中',
        ]);

        return redirect()->route('attendance.index');


    }

    public function restOut(Request $request)
    {
        $user=Auth::user();
        $today=Carbon::now();

        $attendance=Attendance::where('user_id', $user->id)->where('date', $today->toDateString())->first();

        $restTime=RestTime::where('attendance_id', $attendance->id)->whereNull('rest_end')->first();

        $restTime->update([
            'rest_end'=>$today->format('H:i:s'),
        ]);

        $attendance->update([
            'status'=>'出勤中',
        ]);

        return redirect()->route('attendance.index');
    }

    public function attendanceList(Request $request)
    {
        $user=Auth::user();

        $month=$request->get('month', Carbon::now()->format('Y-m'));

        $startDate=Carbon::parse($month)->startOfMonth();
        $endDate=Carbon::parse($month)->endOfMonth();

        $attendances=Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->keyBy('date');

        $dates=[];
        $currentDate=$startDate->copy();
        while($currentDate->lessThanOrEqualTo($endDate)){
            $dates[]=$currentDate->copy();
            $currentDate->addDay();
        }

        return view('user.attendance.list', compact('attendances', 'dates', 'month'));
    }

    public function attendanceDetail($attendance)
    {
        $attendance=Attendance::with('user','restTimes')->find($attendance);
        return view('user.attendance.detail', compact('attendance'));
    }
}
