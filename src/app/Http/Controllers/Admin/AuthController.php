<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminLoginRequest;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // ログイン試行
        if (Auth::attempt($credentials)) {

            // role が 1（管理者）かチェック
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.index');
            }

            // 一般ユーザーならログアウトさせる
            Auth::logout();
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login.form');
    }
}
