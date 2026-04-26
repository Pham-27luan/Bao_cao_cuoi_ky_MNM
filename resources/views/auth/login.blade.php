<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng Nhập - {{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
    {{-- Header cố định trên cùng --}}
    @include('pages.header')
    
    {{-- Wrapper chiếm toàn bộ không gian --}}
    <div class="main-wrapper">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <h1>🚌 Chào mừng bạn</h1>
                    <p>Đăng nhập để đặt vé xe online</p>
                </div>
                
                <div class="login-body">
                    @if ($errors->any())
                        <div class="error-message">
                            <strong>⚠️ Lỗi!</strong> {{ $errors->first() }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="success-message">
                            <strong>✓ Thành công!</strong> {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                        @csrf
                        
                        <div class="input-group">
                            <label for="phone">Số điện thoại</label>
                            <input 
                                type="text" 
                                id="phone"
                                name="phone"
                                placeholder="Nhập số điện thoại" 
                                value="{{ old('phone') }}"
                                autocomplete="username"
                                required
                            >
                        </div>

                        <div class="input-group">
                            <label for="password">Mật khẩu</label>
                            <div class="password-wrapper">
                                <input 
                                    type="password" 
                                    id="password"
                                    name="password"
                                    placeholder="Nhập mật khẩu" 
                                    autocomplete="current-password"
                                    required
                                >
                                <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                            </div>
                            <div class="forgot-password">
                                @if (\Illuminate\Support\Facades\Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                                @else
                                    <span>Quên mật khẩu? Vui lòng liên hệ admin.</span>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn-login" id="submitBtn">
                            Đăng Nhập
                        </button>

                        <div class="register-link">
                            Chưa có tài khoản? 
                            <a href="{{ route('register') }}">Đăng ký ngay</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Footer cố định dưới cùng --}}
    @include('pages.footer')

    {{-- Script riêng --}}
    <script src="{{ asset('js/login.js') }}"></script>

</body>
</html>
