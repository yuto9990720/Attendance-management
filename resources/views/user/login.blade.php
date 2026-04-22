@extends('layouts.app')

@section('content')

<div class="login-container">

    <h2>ログイン</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" >
            @if($errors->has('email'))
                <span class="error">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password" >
            @if($errors->has('password'))
                <span class="error">{{ $errors->first('password') }}</span>
            @endif
        </div>

        {{-- ログイン失敗時のエラーメッセージ --}}
        @if($errors->has('failed'))
        <span class="error">{{ $errors->first('failed') }}</span>
        @endif
        
        <button type="submit">ログインする</button>

    </form>

    <a href="{{ route('register') }}">会員登録はこちら</a>

</div>
    
@endsection