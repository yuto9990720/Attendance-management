@extends('layouts.app')

@section('content')
    <div class="attendance-detail-container">

        <h2>勤怠詳細</h2>

        <form method="POST" action="{{ route('stamp-correction-request.store', ['attendance' => $attendance->id]) }}">
            @csrf

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
                        @if ($attendance->status === '承認待ち')
                            {{ $attendance->check_in }} ～ {{ $attendance->check_out }}
                        @else
                            <input type="time" name="check_in" value="{{ $attendance->check_in }}">
                            <input type="time" name="check_out" value="{{ $attendance->check_out }}">
                        @endif
                    </td>
                </tr>
                @foreach ($attendance->restTimes as $index => $restTime)
                    <tr>
                        <th>休憩時間</th>
                        <td>
                            @if ($attendance->status === '承認待ち')
                                {{ $restTime->rest_start }} ～ {{ $restTime->rest_end }}
                            @else
                                <input type="time" name="rest_start[]" value="{{ $restTime->rest_start }}">
                                <input type="time" name="rest_end[]" value="{{ $restTime->rest_end }}">
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <th>備考</th>
                    <td>
                        @if ($attendance->status === '承認待ち')
                            {{ $attendance->remarks }}
                        @else
                            <textarea name="remarks" rows="4" cols="50">{{ $attendance->remarks }}</textarea>
                        @endif
                    </td>
                </tr>
            </table>

            @if ($attendance->status === '承認待ち')
                <p class="error">承認待ちのため修正はできません。</p>
            @endif

            @if ($attendance->status !== '承認待ち')
                <button type="submit">修正</button>
            @endif

        </form>

    </div>
@endsection
