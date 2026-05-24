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
            <nav>
                <a href="{{ route('admin.attendance.index') }}">勤怠一覧</a>
                <a href="{{ route('admin.staff.index') }}">スタッフ一覧</a>
                <a href="{{ route('admin.stamp-correction-requests.index') }}">申請一覧</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit">ログアウト</button>
                </form>
            </nav>
        @endauth
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>
