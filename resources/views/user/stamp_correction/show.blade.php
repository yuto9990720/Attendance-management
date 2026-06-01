@extends('layouts.app')

@section('content')
    <div class="attendance-detail-container">

        <h2>勤怠詳細</h2>

        <table>
            <tr>
                <th>名前</th>
                <td>{{ $stampCorrectionRequest->attendance->user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>
                    {{ Carbon\Carbon::parse($stampCorrectionRequest->attendance->date)->format('Y年') }}
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    {{ Carbon\Carbon::parse($stampCorrectionRequest->attendance->date)->format('n月j日') }}
                </td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>
                    {{ $stampCorrectionRequest->check_in ? Carbon\Carbon::parse($stampCorrectionRequest->check_in)->format('H:i') : '' }}
                    〜
                    {{ $stampCorrectionRequest->check_out ? Carbon\Carbon::parse($stampCorrectionRequest->check_out)->format('H:i') : '' }}
                </td>
            </tr>
            @foreach ($stampCorrectionRequest->stampCorrectionRestTimes as $index => $restTime)
                <tr>
                    <th>休憩{{ $index + 1 }}</th>
                    <td>
                        {{ $restTime->rest_start ? Carbon\Carbon::parse($restTime->rest_start)->format('H:i') : '' }}
                        〜
                        {{ $restTime->rest_end ? Carbon\Carbon::parse($restTime->rest_end)->format('H:i') : '' }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <th>備考</th>
                <td>{{ $stampCorrectionRequest->remarks }}</td>
            </tr>
        </table>

        @if ($stampCorrectionRequest->status === '承認待ち')
            <p class="pending-message">*承認待ちのため修正はできません。</p>
        @endif

    </div>
@endsection
