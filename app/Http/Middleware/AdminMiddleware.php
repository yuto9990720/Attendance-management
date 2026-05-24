<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // ログインしていない場合はログイン画面へ
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 管理者でない場合は打刻画面へ
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('attendance.index');
        }

        return $next($request);
    }
}