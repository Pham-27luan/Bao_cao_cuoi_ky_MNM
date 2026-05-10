<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate(
            [
                'hoten' => 'required|string|max:255',
                'phone' => 'required|string|regex:/^0[0-9]{9}$/|unique:taikhoan,phone',
                'email' => 'nullable|email|max:255|unique:taikhoan,email',
                'password' => 'required|string|min:6|confirmed',
            ],
            [
                'hoten.required' => 'Vui lòng nhập họ và tên',
                'hoten.max' => 'Họ và tên không được vượt quá 255 ký tự',
                'phone.required' => 'Vui lòng nhập số điện thoại',
                'phone.regex' => 'Số điện thoại không hợp lệ (bắt đầu bằng 0 và có 10 số)',
                'phone.unique' => 'Số điện thoại đã được đăng ký',
                'email.email' => 'Email không hợp lệ',
                'email.unique' => 'Email đã được đăng ký',
                'password.required' => 'Vui lòng nhập mật khẩu',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
                'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            ]
        );

        try {
            $user = TaiKhoan::create([
                'hoten' => trim((string) $request->hoten),
                'phone' => trim((string) $request->phone),
                'email' => $request->email,
                // Luu mat khau dang chuoi (plain text) theo yeu cau hien tai cua du an.
                'password' => (string) $request->password,
                'role' => 'khach_hang',
            ]);

            if ($user) {
                return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
            }

            return back()->withErrors(['Đăng ký thất bại. Vui lòng thử lại!'])->withInput();
        } catch (\Exception $e) {
            Log::error('Register exception: ' . $e->getMessage());
            return back()->withErrors(['Lỗi hệ thống, vui lòng thử lại sau!'])->withInput();
        }
    }
}
