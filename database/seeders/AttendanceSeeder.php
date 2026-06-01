<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\RestTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();

        foreach ($users as $user) {
            // 過去3ヶ月分の勤怠データを作成
            for ($i = 90; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);

                // 土日はスキップ
                if ($date->isWeekend()) {
                    continue;
                }

                $attendance = Attendance::create([
                    'user_id'   => $user->id,
                    'date'      => $date->toDateString(),
                    'check_in'  => '09:00:00',
                    'check_out' => '18:00:00',
                    'status'    => '退勤済',
                    'remarks'   => null,
                ]);

                // 休憩データを作成
                RestTime::create([
                    'attendance_id' => $attendance->id,
                    'rest_start'    => '12:00:00',
                    'rest_end'      => '13:00:00',
                ]);
            }
        }
    }
}