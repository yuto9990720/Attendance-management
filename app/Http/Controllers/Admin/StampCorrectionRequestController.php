<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StampCorrectionRequest;
use Illuminate\Http\Request;

class StampCorrectionRequestController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'pending');

        $pendingRequests = StampCorrectionRequest::with('user', 'attendance')
            ->where('status', '承認待ち')
            ->get();

        $approvedRequests = StampCorrectionRequest::with('user', 'attendance')
            ->where('status', '承認済み')
            ->get();

        return view('admin.stamp_correction.index', compact('pendingRequests', 'approvedRequests', 'tab'));
    }

    public function show($stampCorrectionRequest)
    {
        $stampCorrectionRequest = StampCorrectionRequest::with(
            'attendance.user',
            'attendance.restTimes'
        )->find($stampCorrectionRequest);

        $attendance = $stampCorrectionRequest->attendance;
        $stampCorrectionRequestId = $stampCorrectionRequest->id;

        return view('admin.stamp_correction.detail', compact('attendance', 'stampCorrectionRequestId'));

    }

    public function approve($stampCorrectionRequest)
    {
        $stampCorrectionRequest = StampCorrectionRequest::with('attendance')->find($stampCorrectionRequest);

        // 勤怠情報を申請内容で更新
        $stampCorrectionRequest->attendance->update([
            'status' => '承認済み',
        ]);

        // 申請ステータスを承認済みに更新
        $stampCorrectionRequest->update([
            'status' => '承認済み',
        ]);

        //承認後、画面遷移なし
       return response()->json([
        'success' => true,
        'message' => '承認済み',
        ]);
    }
}