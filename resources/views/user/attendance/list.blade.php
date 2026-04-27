@extends('layouts.app')

@section('content')
    <div class="attendance-list-container">

        <h2>勤怠一覧</h2>

        {{-- 月切り替え --}}
        <div class="month-nav">
            <a
                href="{{ route('attendance.list', ['month' => Carbon\Carbon::parse($month)->subMonth()->format('Y-m')]) }}">←前月</a>

            <span>{{ Carbon\Carbon::parse($month)->format('Y-m') }}</span>

            <a
                href="{{ route('attendance.list', ['month' => Carbon\Carbon::parse($month)->addMonth()->format('Y-m')]) }}">次月→</a>
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
                    @endphp
                    <tr>
                        <td>{{ $date->isoFormat('MM/DD(ddd)') }}</td>
                        <td>{{ $attendance ? $attendance->check_in : '-' }}</td>
                        <td>{{ $attendance ? $attendance->check_out : '-' }}</td>
                        <td>
                            {{-- 休憩時間の合計を表示 --}}
                        </td>
                        <td>
                            {{-- 勤務時間から休憩時間を引いた合計を表示 --}}
                        </td>
                        <td>
                            @if ($attendance)
                                <a href="{{ route('attendance.detail', ['attendance' => $attendance->id]) }}">詳細</a>
                            @else
                                <a href="#">詳細</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
