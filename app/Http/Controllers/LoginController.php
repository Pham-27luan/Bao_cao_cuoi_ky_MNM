<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (session('isLoggedIn')) {
            if (session('userRole') === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $phone = trim($request->phone);
        $password = (string) $request->password;

        try {
            $account = TaiKhoan::where('phone', $phone)->first();
            if (!$account) {
                return back()->withErrors(['Số điện thoại chưa được đăng ký!'])->withInput();
            }

            $storedPassword = (string) ($account->password ?? '');
            if (!hash_equals($storedPassword, $password)) {
                return back()->withErrors(['Mật khẩu không chính xác!'])->withInput();
            }

            $role = strtolower(trim((string) ($account->role ?? '')));
            if ($role === '') {
                $role = 'khach_hang';
            }
            $fullName = (string) ($account->hoten ?? '');
            $displayName = $fullName !== '' ? $fullName : ($account->email ?: $account->phone);

            session([
                'isAdmin' => $role === 'admin',
                'userRole' => $role,
                'userPhone' => $account->phone,
                'userName' => $displayName,
                'userFullName' => $fullName,
                'userId' => $account->id,
                'isLoggedIn' => true,
            ]);

            if ($role === 'admin') {
                $intended = (string) session('url.intended', '');
                $intendedPath = (string) (parse_url($intended, PHP_URL_PATH) ?? '');
                if ($intendedPath !== '' && str_starts_with($intendedPath, '/admin')) {
                    return redirect()->intended(route('admin.dashboard'));
                }

                session()->forget('url.intended');
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('home'));
        } catch (\Exception $e) {
            Log::error('Login exception: ' . $e->getMessage());
            return back()->withErrors(['Lỗi hệ thống, vui lòng thử lại sau!'])->withInput();
        }
    }

    public function logout(Request $request)
    {
        session()->flush();
        return redirect()->route('login');
    }
}
