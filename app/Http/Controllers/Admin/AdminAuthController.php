<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('admin.attendance.index');
            }
            Auth::logout();
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login');
    }
}