<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <div class="header-logo">
            <img src="{{ asset('images/logo.png') }}" alt="ロゴ">
        </div>
        @auth
        @if(Auth::user()->email_verified_at)
        <nav>
            <a href="{{ route('attendance.index') }}">勤怠</a>
            <a href="{{ route('attendance.list') }}">勤怠一覧</a>
            <a href="{{ route('stamp-correction-requests.index') }}">申請</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        </nav>
        @endif
        @endauth
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>