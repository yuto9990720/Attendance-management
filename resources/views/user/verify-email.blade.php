@extends('layouts.app')

@section('content')
    <div class="verify-email-container">

        <p class="verify-email-text">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        {{-- 認証メールへのリンク --}}
        <a href="http://localhost:8025" target="_blank">
            <button class="verify-btn">認証はこちらから</button>
        </a>

        {{-- 認証メール再送 --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <a href="#" onclick="this.closest('form').submit()" class="resend-link">認証メールを再送する</a>
        </form>

    </div>
@endsection
