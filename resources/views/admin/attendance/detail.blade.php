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
                    <td>
                        {{ Carbon\Carbon::parse($attendance->date)->format('Y年') }}
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        {{ Carbon\Carbon::parse($attendance->date)->format('n月j日') }}
                    </td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>
                        <input type="time" name="check_in"
                            value="{{ $attendance->check_in ? Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '' }}">
                        〜
                        <input type="time" name="check_out"
                            value="{{ $attendance->check_out ? Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '' }}">
                        {{-- エラーメッセージ --}}
                        @if ($errors->has('check_in'))
                            <p class="error">{{ $errors->first('check_in') }}</p>
                        @endif

                    </td>
                </tr>
                @foreach ($attendance->restTimes as $index => $restTime)
                    <tr>
                        <th>休憩{{ $index + 1 }}</th>
                        <td>
                            <input type="hidden" name="rest_time_id[]" value="{{ $restTime->id }}">
                            <input type="time" name="rest_start[]"
                                value="{{ $restTime->rest_start ? Carbon\Carbon::parse($restTime->rest_start)->format('H:i') : '' }}">
                            〜
                            <input type="time" name="rest_end[]"
                                value="{{ $restTime->rest_end ? Carbon\Carbon::parse($restTime->rest_end)->format('H:i') : '' }}">
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
                        <textarea name="remarks">{{ $attendance->remarks }}</textarea>

                        {{-- エラーメッセージ --}}
                        @if ($errors->has('remarks'))
                            <p class="error">{{ $errors->first('remarks') }}</p>
                        @endif
                    </td>
                </tr>
            </table>

            <div class="submit-area">
                <button type="submit">修正</button>
            </div>

        </form>

    </div>
@endsection
