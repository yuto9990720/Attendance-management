@extends('layouts.admin')

@section('content')
<div class="admin-stamp-correction-container">

    <h2>申請一覧</h2>

    {{-- タブ --}}
    <div class="tabs">
        <a href="{{ route('admin.stamp-correction-requests.index', ['tab' => 'pending']) }}">承認待ち</a>
        <a href="{{ route('admin.stamp-correction-requests.index', ['tab' => 'approved']) }}">承認済み</a>
    </div>

    {{-- 承認待ち一覧 --}}
    @if(!isset($tab) || $tab === 'pending')
    <table>
        <thead>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingRequests as $request)
            <tr>
                <td>{{ $request->status }}</td>
                <td>{{ $request->user->name }}</td>
                <td>{{ Carbon\Carbon::parse($request->attendance->date)->isoFormat('YYYY/MM/DD') }}</td>
                <td>{{ $request->remarks }}</td>
                <td>{{ Carbon\Carbon::parse($request->created_at)->isoFormat('YYYY/MM/DD') }}</td>
                <td>
                    <a href="{{ route('admin.stamp-correction-request.show', ['stampCorrectionRequest' => $request->id]) }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- 承認済み一覧 --}}
    @if($tab === 'approved')
    <table>
        <thead>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($approvedRequests as $request)
            <tr>
                <td>{{ $request->status }}</td>
                <td>{{ $request->user->name }}</td>
                <td>{{ Carbon\Carbon::parse($request->attendance->date)->isoFormat('YYYY/MM/DD') }}</td>
                <td>{{ $request->remarks }}</td>
                <td>{{ Carbon\Carbon::parse($request->created_at)->isoFormat('YYYY/MM/DD') }}</td>
                <td>
                    <a href="{{ route('admin.stamp-correction-request.show', ['stampCorrectionRequest' => $request->id]) }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

</div>
@endsection