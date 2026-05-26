@extends('layouts.admin')

@section('content')
    <div class="admin-staff-attendance-container">

        <h2>{{ $user->name }}さんの勤怠</h2>

        {{-- 月切り替え --}}
        <div class="month-nav">
            <a href="{{ route('admin.staff.attendance', ['user' => $user->id, 'month' => $prevMonth]) }}">← 前月</a>
            <span>{{ Carbon\Carbon::parse($month)->format('Y/m') }}</span>
            <a href="{{ route('admin.staff.attendance', ['user' => $user->id, 'month' => $nextMonth]) }}">翌月 →</a>
        </div>

        {{-- 勤怠一覧テーブル --}}
        <table>
            <thead>
                <tr>
                    <th>日付</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dates as $date)
                    @php
                        $attendance = $attendances->get($date->toDateString()) ?? null;
                        $totalRest = 0;
                        if ($attendance) {
                            foreach ($attendance->restTimes as $restTime) {
                                if ($restTime->rest_end) {
                                    $totalRest += Carbon\Carbon::parse($restTime->rest_start)->diffInMinutes(
                                        Carbon\Carbon::parse($restTime->rest_end),
                                    );
                                }
                            }
                        }
                        $restHours = floor($totalRest / 60);
                        $restMinutes = $totalRest % 60;

                        $totalWork = 0;
                        if ($attendance && $attendance->check_out) {
                            $totalWork = Carbon\Carbon::parse($attendance->check_in)->diffInMinutes(
                                Carbon\Carbon::parse($attendance->check_out),
                            );
                            $totalWork -= $totalRest;
                        }
                        $workHours = floor($totalWork / 60);
                        $workMinutes = $totalWork % 60;
                    @endphp
                    <tr>
                        <td>{{ $date->locale('ja')->isoFormat('MM/DD(ddd)') }}</td>
                        <td>{{ $attendance && $attendance->check_in ? Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '' }}
                        </td>
                        <td>{{ $attendance && $attendance->check_out ? Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '' }}
                        </td>
                        <td>{{ $attendance ? sprintf('%02d:%02d', $restHours, $restMinutes) : '' }}</td>
                        <td>{{ $attendance ? sprintf('%02d:%02d', $workHours, $workMinutes) : '' }}</td>
                        <td>
                            @if ($attendance)
                                <a href="{{ route('admin.attendance.detail', ['attendance' => $attendance->id]) }}">詳細</a>
                            @else
                                <a href="#">詳細</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- CSV出力 --}}
        <div class="csv-btn">
            <form method="GET" action="{{ route('admin.staff.csv', ['user' => $user->id]) }}">
                <input type="hidden" name="month" value="{{ $month }}">
                <button type="submit">CSV出力</button>
            </form>
        </div>

    </div>
@endsection
