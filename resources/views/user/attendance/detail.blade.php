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
                    <td>
                        {{ Carbon\Carbon::parse($attendance->date)->format('Y年') }}
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        {{ Carbon\Carbon::parse($attendance->date)->format('n月j日') }}
                    </td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>
                        @if ($attendance->status === '承認待ち')
                            {{ Carbon\Carbon::parse($attendance->check_in)->format('H:i') }} 〜
                            {{ Carbon\Carbon::parse($attendance->check_out)->format('H:i') }}
                        @else
                            <input type="time" name="check_in"
                                value="{{ $attendance->check_in ? Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '' }}">～
                            <input type="time" name="check_out"
                                value="{{ $attendance->check_out ? Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '' }}">
                        @endif
                        {{-- エラーメッセージ --}}
                        @if ($errors->has('check_in'))
                            <p class="error">{{ $errors->first('check_in') }}</p>
                        @endif
                    </td>
                </tr>
                @foreach ($attendance->restTimes as $index => $restTime)
                    <tr>
                        <th>休憩</th>
                        <td>
                            @if ($attendance->status === '承認待ち')
                                {{ Carbon\Carbon::parse($restTime->rest_start)->format('H:i') }} 〜
                                {{ Carbon\Carbon::parse($restTime->rest_end)->format('H:i') }}
                            @else
                                <input type="time" name="rest_start[]"
                                    value="{{ $restTime->rest_start ? Carbon\Carbon::parse($restTime->rest_start)->format('H:i') : '' }}">
                                <input type="time" name="rest_end[]"
                                    value="{{ $restTime->rest_end ? Carbon\Carbon::parse($restTime->rest_end)->format('H:i') : '' }}">
                            @endif
                            {{-- エラーメッセージ --}}
                            @if ($errors->has('rest_start'))
                                <p class="error">{{ $errors->first('rest_start') }}</p>
                            @endif
                            @if ($errors->has('rest_end'))
                                <p class="error">{{ $errors->first('rest_end') }}</p>
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
                        @if ($errors->has('remarks'))
                            <p class="error">{{ $errors->first('remarks') }}</p>
                        @endif
                    </td>
                </tr>
            </table>

            @if ($attendance->status === '承認待ち')
                <p class="pending-message">*承認待ちのため修正はできません。</p>
            @endif

            @if ($attendance->status !== '承認待ち')
                <div class="submit-area">
                    <button type="submit">修正</button>
                </div>
            @endif

        </form>

    </div>
@endsection
