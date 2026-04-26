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
                'phone' => 'required|string|regex:/^0[0-9]{9}$/|unique:taikhoan,phone',
                'email' => 'nullable|email|max:255|unique:taikhoan,email',
                'password' => 'required|string|min:6|confirmed',
            ],
            [
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
                'phone' => trim($request->phone),
                'email' => $request->email,
                // Lưu mật khẩu dạng chuỗi (plain text) theo yêu cầu
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

