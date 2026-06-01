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
            'stampCorrectionRestTimes'
        )->find($stampCorrectionRequest);

        return view('admin.stamp_correction.detail', compact('stampCorrectionRequest'));

    }

    public function approve($stampCorrectionRequest)
    {
        $stampCorrectionRequest = StampCorrectionRequest::with(
            'attendance',
            'stampCorrectionRestTimes'
        )->find($stampCorrectionRequest);

        // 勤怠情報を申請内容で更新
        $stampCorrectionRequest->attendance->update([
            'check_in'  => $stampCorrectionRequest->check_in,
            'check_out' => $stampCorrectionRequest->check_out,
            'remarks'   => $stampCorrectionRequest->remarks,
            'status'    => '承認済み',
        ]);

        // 休憩情報を更新
        foreach($stampCorrectionRequest->stampCorrectionRestTimes as $index => $restTime) {
            $existingRestTime = $stampCorrectionRequest->attendance->restTimes[$index] ?? null;
            if($existingRestTime) {
                $existingRestTime->update([
                    'rest_start' => $restTime->rest_start,
                    'rest_end'   => $restTime->rest_end,
                ]);
            }
        }

        // 申請ステータスを承認済みに更新
        $stampCorrectionRequest->update([
            'status' => '承認済み',
        ]);

        return response()->json(['success' => true]);
        }
}