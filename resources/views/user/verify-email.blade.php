@extends('layouts.app')

@section('content')
<div class="verify-email-container">

    <p>登録していただいたメールアドレスに認証メールを送付しました。<br>
    メール認証を完了してください。</p>

    {{-- 認証メールへのリンク --}}
    <a href="http://localhost:8025" target="_blank">
        <button>認証はこちらから</button>
    </a>

    {{-- 認証メール再送 --}}
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">認証メールを再送する</button>
    </form>

</div>
@endsection