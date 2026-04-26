<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!session()->has('isLoggedIn')) {
            return redirect()->route('login')->withErrors(['Vui lòng đăng nhập!']);
        }

        // Nếu không truyền role thì chỉ cần đăng nhập là được phép
        if (count($roles) === 0) {
            return $next($request);
        }

        $userRole = session('userRole', 'khach_hang');
        if (in_array($userRole, $roles, true)) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập trang này!');
    }
}

