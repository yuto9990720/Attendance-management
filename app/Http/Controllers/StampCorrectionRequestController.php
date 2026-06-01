<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\StampCorrectionRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceRequest;
use App\Models\StampCorrectionRestTime;

class StampCorrectionRequestController extends Controller
{
    public function store(AttendanceRequest $request, $attendance)
{
    $attendance = Attendance::find($attendance);

    $correctionRequest = StampCorrectionRequest::create([
        'user_id'       => Auth::user()->id,
        'attendance_id' => $attendance->id,
        'status'        => '承認待ち',
        'remarks'       => $request->remarks,
        'check_in'      => $request->check_in,
        'check_out'     => $request->check_out,
    ]);

    // 休憩データを保存
    if($request->rest_start) {
        foreach($request->rest_start as $index => $restStart) {
            StampCorrectionRestTime::create([
                'stamp_correction_request_id' => $correctionRequest->id,
                'rest_start'                  => $restStart,
                'rest_end'                    => $request->rest_end[$index] ?? null,
            ]);
        }
    }

    $attendance->update([
        'status' => '承認待ち',
    ]);

    return redirect()->route('stamp-correction-requests.index');
}

    public function index(Request $request)
    {
        $user=Auth::user();

        $tab=$request->input('tab','pending');

        $pendingRequests=StampCorrectionRequest::where('user_id',$user->id)
        ->where('status','承認待ち')
        ->get();

        $approvedRequests=StampCorrectionRequest::where('user_id',$user->id)
        ->where('status','承認済み')
        ->get();

        return view('user.stamp_correction.list', compact('pendingRequests', 'approvedRequests','tab'));


    }

    public function show($stampCorrectionRequest)
{
    $stampCorrectionRequest = StampCorrectionRequest::with(
        'attendance.user',
        'stampCorrectionRestTimes'
    )->find($stampCorrectionRequest);

    return view('user.stamp_correction.show', compact('stampCorrectionRequest'));
}

}
