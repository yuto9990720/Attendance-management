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
            <!-- ロゴやサイト名などをここに配置 -->
        </div>
        <nav>
            <!-- ナビゲーションメニューをここに配置 -->
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>
