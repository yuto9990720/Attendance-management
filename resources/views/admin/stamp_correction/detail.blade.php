@extends('layouts.admin')

@section('content')
    <div class="admin-stamp-correction-detail-container">

        <h2>勤怠詳細</h2>

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
                    {{ $attendance->check_in ? Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '' }}
                    〜
                    {{ $attendance->check_out ? Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '' }}
                </td>
            </tr>
            @foreach ($attendance->restTimes as $index => $restTime)
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
                <td>{{ $attendance->remarks }}</td>
            </tr>
        </table>

        <div class="submit-area">
            <div id="approve-area">
                @if ($attendance->status === '承認済み')
                    <button type="button" disabled id="approve-btn">承認済み</button>
                @else
                    <button type="button" id="approve-btn"
                        data-url="{{ route('admin.stamp-correction-request.approve', ['stampCorrectionRequest' => $stampCorrectionRequestId]) }}">
                        承認
                    </button>
                @endif
            </div>
        </div>

    </div>

    <script>
        const btn = document.getElementById('approve-btn');
        if (btn && btn.dataset.url) {
            btn.addEventListener('click', function() {
                fetch(this.dataset.url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            btn.textContent = '承認済み';
                            btn.disabled = true;
                        }
                    });
            });
        }
    </script>
@endsection
