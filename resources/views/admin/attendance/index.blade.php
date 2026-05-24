@extends('layouts.admin')

@section('content')
<div class="admin-attendance-container">

    <h2>{{ Carbon\Carbon::parse($date)->isoFormat('YYYY年M月D日') }}の勤怠</h2>

    {{-- 日付切り替え --}}
    <div class="date-nav">
        <a href="{{ route('admin.attendance.index', ['date' => $prevDate]) }}">← 前日</a>
        <span>{{ Carbon\Carbon::parse($date)->isoFormat('YYYY/MM/DD') }}</span>
        <a href="{{ route('admin.attendance.index', ['date' => $nextDate]) }}">翌日 →</a>
    </div>

    {{-- 勤怠一覧テーブル --}}
    <table>
        <thead>
            <tr>
                <th>名前</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->user->name }}</td>
                <td>{{ $attendance->check_in }}</td>
                <td>{{ $attendance->check_out }}</td>
                <td>
                    @php
                        $totalRest = 0;
                        foreach($attendance->restTimes as $restTime) {
                            if($restTime->rest_end) {
                                $totalRest += Carbon\Carbon::parse($restTime->rest_start)
                                    ->diffInMinutes(Carbon\Carbon::parse($restTime->rest_end));
                            }
                        }
                        $restHours = floor($totalRest / 60);
                        $restMinutes = $totalRest % 60;
                    @endphp
                    {{ sprintf('%02d:%02d', $restHours, $restMinutes) }}
                </td>
                <td>
                    @php
                        $totalWork = 0;
                        if($attendance->check_out) {
                            $totalWork = Carbon\Carbon::parse($attendance->check_in)
                                ->diffInMinutes(Carbon\Carbon::parse($attendance->check_out));
                            $totalWork -= $totalRest;
                        }
                        $workHours = floor($totalWork / 60);
                        $workMinutes = $totalWork % 60;
                    @endphp
                    {{ sprintf('%02d:%02d', $workHours, $workMinutes) }}
                </td>
                <td>
                    <a href="{{ route('admin.attendance.detail', ['attendance' => $attendance->id]) }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection