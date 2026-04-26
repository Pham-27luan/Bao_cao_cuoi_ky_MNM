<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="font-['Inter']">
    <div class="admin-container">
        @include('admin.partials.sidebar')
        
        <main class="admin-main">
            <div class="main-content">
                <div class="header-actions">
                    <div class="page-header" style="margin-bottom: 0;">
                        <h1>Quản lý người dùng</h1>
                        <p>Danh sách tất cả tài khoản trong hệ thống</p>
                    </div>
                    <button onclick="openCreateUserModal()" class="btn-primary">
                        <i class="fas fa-plus"></i> Thêm người dùng
                    </button>
                </div>
                
                @if(session('success'))
                    <div class="alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-error">
                        @foreach($errors->all() as $error)
                            <p><i class="fas fa-exclamation-circle"></i> {{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                
                <!-- Bộ lọc -->
                <div class="filter-section">
                    <div class="filter-group">
                        <input type="text" id="searchInput" placeholder="Tìm kiếm tên hoặc số điện thoại..." class="filter-search">
                        <select id="roleFilter" class="filter-select">
                            <option value="">Tất cả vai trò</option>
                            <option value="admin">Quản trị viên</option>
                            <option value="tai_xe">Tài xế</option>
                            <option value="khach_hang">Khách hàng</option>
                        </select>
                        <button class="filter-btn" onclick="filterUsers()">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <button class="filter-btn" onclick="resetUserFilter()">
                            <i class="fas fa-redo"></i> Làm mới
                        </button>
                    </div>
                </div>
                
                <!-- Bảng danh sách người dùng -->
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Họ tên</th>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="user-row" data-role="{{ $user->role }}" data-name="{{ strtolower($user->hoten ?? '') }}" data-phone="{{ $user->phone }}">
                                <td>{{ $user->id }}</td>
                                <td class="font-medium">{{ $user->hoten ?? 'Chưa cập nhật' }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->email ?? 'Chưa cập nhật' }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge-info"><i class="fas fa-crown"></i> Quản trị viên</span>
                                    @elseif($user->role === 'tai_xe')
                                        <span class="badge-warning"><i class="fas fa-truck"></i> Tài xế</span>
                                    @else
                                        <span class="badge-success"><i class="fas fa-user"></i> Khách hàng</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="action-group">
                                        <button onclick="editUser({{ $user->id }})" class="action-edit" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="showDeleteUserModal({{ $user->id }})" class="action-delete" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center p-4 text-gray-500">
                                    <i class="fas fa-database"></i> Chưa có dữ liệu người dùng
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Phân trang -->
                <div class="pagination">
                    <p class="pagination-info">Hiển thị <span id="showingCount">{{ count($users) }}</span> / <span id="totalCount">{{ count($users) }}</span> người dùng</p>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Modal thêm/sửa người dùng -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-header" id="userModalTitle">Thêm người dùng mới</h2>
            <form id="userForm" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <input type="hidden" id="userId" name="userId">
                <div class="form-group">
                    <label class="form-label">Họ và tên <span class="required">*</span></label>
                    <input type="text" id="hoten" name="hoten" class="form-input" placeholder="Nhập họ và tên" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Số điện thoại <span class="required">*</span></label>
                    <input type="text" id="phone" name="phone" class="form-input" placeholder="Nhập số điện thoại" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Nhập email (không bắt buộc)">
                </div>
                <div class="form-group" id="passwordGroup">
                    <label class="form-label">Mật khẩu <span class="required">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-input" placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('password')"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Vai trò <span class="required">*</span></label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="khach_hang">Khách hàng</option>
                        <option value="tai_xe">Tài xế</option>
                        <option value="admin">Quản trị viên</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeUserModal()" class="btn-outline flex-1">Hủy</button>
                    <button type="submit" class="btn-primary flex-1">Lưu</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal xác nhận xóa -->
    <div id="deleteModal" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header" style="color: #dc2626;">
                <i class="fas fa-exclamation-triangle"></i> Xác nhận xóa
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa người dùng này?</p>
                <p class="text-sm text-gray-500 mt-2">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button onclick="closeDeleteModal()" class="btn-outline">Hủy</button>
                <button id="confirmDeleteBtn" class="btn-primary" style="background-color: #dc2626;">Xóa</button>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/admin.js') }}?v=2"></script>
</body>
</html>
