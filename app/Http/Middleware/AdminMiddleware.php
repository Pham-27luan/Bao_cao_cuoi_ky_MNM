<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra session admin
        if (!session()->has('isAdmin') || session('isAdmin') !== true) {
            return redirect('/login')->withErrors(['Bạn cần đăng nhập với tài khoản admin để truy cập!']);
        }
        
        return $next($request);
    }
}