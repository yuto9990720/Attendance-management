@extends('layouts.admin')

@section('content')
<div class="admin-attendance-detail-container">

    <h2>勤怠詳細</h2>

    <form method="POST" action="{{ route('admin.attendance.update', ['attendance' => $attendance->id]) }}">
        @csrf
        @method('PUT')

        <table>
            <tr>
                <th>名前</th>
                <td>{{ $attendance->user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>{{ Carbon\Carbon::parse($attendance->date)->isoFormat('YYYY年M月D日') }}</td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>
                    <input type="time" name="check_in" value="{{ $attendance->check_in }}">
                    〜
                    <input type="time" name="check_out" value="{{ $attendance->check_out }}">
                </td>
            </tr>
            @foreach($attendance->restTimes as $index => $restTime)
            <tr>
                <th>休憩{{ $index + 1 }}</th>
                <td>
                    <input type="hidden" name="rest_time_id[]" value="{{ $restTime->id }}">
                    <input type="time" name="rest_start[]" value="{{ $restTime->rest_start }}">
                    〜
                    <input type="time" name="rest_end[]" value="{{ $restTime->rest_end }}">
                </td>
            </tr>
            @endforeach
            <tr>
                <th>備考</th>
                <td>
                    <textarea name="remarks">{{ $attendance->remarks }}</textarea>
                </td>
            </tr>
        </table>

        <button type="submit">修正</button>

    </form>

</div>
@endsection