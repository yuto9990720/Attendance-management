@extends('layouts.app')

@section('content')
    <div class="attendance-container">

        <div class="status">
            <span>{{ $status }}</span>
        </div>

        <div class="date">
            <p>{{ Carbon\Carbon::parse($today)->isoFormat('YYYY年M月D日(ddd)') }}</p>
        </div>

        <div class="time">
            <p id="clock"></p>
        </div>

        <script>
            function updateClock() {
                const now = new Date();

                const hours = String(now.getHours()).padStart(2, '0');

                const minutes = String(now.getMinutes()).padStart(2, '0');

                document.getElementById('clock').textContent = hours + ':' + minutes;
            }
            setInterval(updateClock, 1000);

            updateClock();
        </script>

        <div class="buttons">
            @if ($status === '勤務外')
                <form method="POST" action="{{ route('attendance.check-in') }}">
                    @csrf
                    <button type="submit">出勤</button>
                </form>
            @elseif($status === '出勤中')
                <form method="POST" action="{{ route('attendance.check-out') }}">
                    @csrf
                    <button type="submit">退勤</button>
                </form>
                <form method="POST" action="{{ route('attendance.rest-in') }}">
                    @csrf
                    <button type="submit">休憩入</button>
                </form>
            @elseif($status === '休憩中')
                <form method="POST" action="{{ route('attendance.rest-out') }}">
                    @csrf
                    <button type="submit">休憩戻</button>
                </form>
            @elseif($status === '退勤済')
                <p>お疲れ様でした！</p>
            @endif
        </div>
    </div>
@endsection
