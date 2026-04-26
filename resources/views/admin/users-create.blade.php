<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm người dùng - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-container">
        @include('admin.partials.sidebar')
        
        <main class="admin-main">
            <div class="main-content">
                <div style="margin-bottom: 2rem;">
                    <a href="{{ route('admin.users') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <div class="page-header" style="margin-bottom: 0;">
                        <h1>Thêm người dùng mới</h1>
                        <p>Tạo tài khoản mới cho hệ thống</p>
                    </div>
                </div>
                
                @if($errors->any())
                    <div class="error-box">
                        @foreach($errors->all() as $error)
                            <p class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                
                <div class="form-container">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-label">Họ và tên <span class="required">*</span></label>
                            <input type="text" name="hoten" value="{{ old('hoten') }}" required class="form-input" placeholder="Nhập họ và tên">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Số điện thoại <span class="required">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required class="form-input" placeholder="Nhập số điện thoại">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="Nhập email (không bắt buộc)">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Mật khẩu <span class="required">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" name="password" required class="form-input" id="password" placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)">
                                <i class="fas fa-eye toggle-password" onclick="togglePassword('password')"></i>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Vai trò <span class="required">*</span></label>
                            <select name="role" required class="form-select">
                                <option value="khach_hang" {{ old('role') == 'khach_hang' ? 'selected' : '' }}>Khách hàng</option>
                                <option value="tai_xe" {{ old('role') == 'tai_xe' ? 'selected' : '' }}>Tài xế</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                            </select>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Tạo tài khoản
                            </button>
                            <a href="{{ route('admin.users') }}" class="btn-outline">
                                <i class="fas fa-times"></i> Hủy bỏ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/admin.js') }}?v=2"></script>
    
</body>
</html>
