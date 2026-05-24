<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        // 一般ユーザーのみ取得
        $users = User::where('role', 'user')->get();

        return view('admin.staff.index', compact('users'));
    }

    public function attendance(Request $request, $user)
    {
        $user = User::find($user);

        // 表示する月を取得（パラメータがなければ今月）
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        // そのユーザーのその月の勤怠情報を取得
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('restTimes')
            ->get()
            ->keyBy('date');

        // その月の全日付を生成
        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lessThanOrEqualTo($endDate)) {
            $dates[] = $currentDate->copy();
            $currentDate->addDay();
        }

        $prevMonth = Carbon::parse($month)->subMonth()->format('Y-m');
        $nextMonth = Carbon::parse($month)->addMonth()->format('Y-m');

        return view('admin.staff.attendance', compact('user', 'month', 'dates', 'attendances', 'prevMonth', 'nextMonth'));
    }

    public function csv(Request $request, $user)
    {
        $user = User::find($user);
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('restTimes')
            ->get();

        // CSVのヘッダー
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $user->name . '_' . $month . '.csv"',
        ];

        // CSVの中身を生成
        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');

            // BOM追加（Excelで文字化けしないため）
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // カラムヘッダー
            fputcsv($file, ['日付', '出勤', '退勤', '休憩', '合計']);

            foreach($attendances as $attendance) {
                // 休憩合計時間の計算
                $totalRest = 0;
                foreach($attendance->restTimes as $restTime) {
                    if($restTime->rest_end) {
                        $totalRest += Carbon::parse($restTime->rest_start)
                            ->diffInMinutes(Carbon::parse($restTime->rest_end));
                    }
                }
                $restHours   = floor($totalRest / 60);
                $restMinutes = $totalRest % 60;

                // 勤務合計時間の計算
                $totalWork = 0;
                if($attendance->check_out) {
                    $totalWork = Carbon::parse($attendance->check_in)
                        ->diffInMinutes(Carbon::parse($attendance->check_out));
                    $totalWork -= $totalRest;
                }
                $workHours   = floor($totalWork / 60);
                $workMinutes = $totalWork % 60;

                fputcsv($file, [
                    Carbon::parse($attendance->date)->isoFormat('YYYY/MM/DD'),
                    $attendance->check_in,
                    $attendance->check_out,
                    sprintf('%02d:%02d', $restHours, $restMinutes),
                    sprintf('%02d:%02d', $workHours, $workMinutes),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}