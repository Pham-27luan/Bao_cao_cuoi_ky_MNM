<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khuyến mãi - Admin</title>
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
                        <h1>Khuyến mãi</h1>
                        <p>Quản lý mã giảm giá và chương trình khuyến mãi</p>
                    </div>
                    <button onclick="openCreateModal()" class="btn-primary">
                        <i class="fas fa-plus"></i> Thêm khuyến mãi
                    </button>
                </div>
                
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
                        {{ session('success') }}
                    </div>
                @endif
                
                <!-- Filter -->
                <div class="filter-section">
                    <div class="filter-group">
                        <select class="filter-select">
                            <option>Tất cả trạng thái</option>
                            <option>Đang hoạt động</option>
                            <option>Hết hạn</option>
                            <option>Sắp diễn ra</option>
                        </select>
                        <input type="text" placeholder="Tìm kiếm mã giảm giá..." class="filter-search">
                        <button class="filter-btn">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
                
                <!-- Bảng khuyến mãi -->
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mã giảm giá</th>
                            <th>Giảm giá</th>
                            <th>Số lượng</th>
                            <th>Đã dùng</th>
                            <th>HSD</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td>1</td>
                            <td class="font-bold text-orange-600">MYBUS20</td>
                            <td>20,000đ</td>
                            <td>100</td>
                            <td>45</td>
                            <td>30/12/2026</td>
                            <td><span class="badge-success">Đang hoạt động</span></td>
                            <td class="text-center">
                                <div class="action-group">
                                    <button onclick="editPromo(1)" class="action-edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deletePromo(1)" class="action-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td>2</td>
                            <td class="font-bold text-orange-600">WELCOME10</td>
                            <td>10%</td>
                            <td>500</td>
                            <td>230</td>
                            <td>31/12/2026</td>
                            <td><span class="badge-success">Đang hoạt động</span></td>
                            <td class="text-center">
                                <div class="action-group">
                                    <button onclick="editPromo(2)" class="action-edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deletePromo(2)" class="action-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td>3</td>
                            <td class="font-bold text-orange-600">TET2026</td>
                            <td>50,000đ</td>
                            <td>200</td>
                            <td>200</td>
                            <td>15/02/2026</td>
                            <td><span class="badge-danger">Hết hạn</span></td>
                            <td class="text-center">
                                <div class="action-group">
                                    <button onclick="editPromo(3)" class="action-edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deletePromo(3)" class="action-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="pagination">
                    <p class="pagination-info">Hiển thị 1-3 của 8 khuyến mãi</p>
                    <div class="pagination-buttons">
                        <button class="pagination-btn">Trước</button>
                        <button class="pagination-btn active">1</button>
                        <button class="pagination-btn">2</button>
                        <button class="pagination-btn">3</button>
                        <button class="pagination-btn">Sau</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Modal thêm/sửa khuyến mãi -->
    <div id="promoModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-header" id="modalTitle">Thêm khuyến mãi</h2>
            <form id="promoForm">
                <div class="form-group">
                    <label class="form-label">Mã giảm giá</label>
                    <input type="text" class="form-input" placeholder="Nhập mã giảm giá">
                </div>
                <div class="form-group">
                    <label class="form-label">Loại giảm giá</label>
                    <select class="form-select">
                        <option>Giảm theo số tiền</option>
                        <option>Giảm theo phần trăm</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Giá trị giảm</label>
                    <input type="number" class="form-input" placeholder="Nhập giá trị giảm">
                </div>
                <div class="form-group">
                    <label class="form-label">Số lượng</label>
                    <input type="number" class="form-input" placeholder="Số lượng mã">
                </div>
                <div class="form-group">
                    <label class="form-label">Ngày hết hạn</label>
                    <input type="date" class="form-input">
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal()" class="btn-outline flex-1">Hủy</button>
                    <button type="submit" class="btn-primary flex-1">Lưu</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').innerText = 'Thêm khuyến mãi';
            document.getElementById('promoModal').classList.add('show');
        }
        
        function closeModal() {
            document.getElementById('promoModal').classList.remove('show');
        }
        
        function editPromo(id) {
            document.getElementById('modalTitle').innerText = 'Sửa khuyến mãi';
            document.getElementById('promoModal').classList.add('show');
        }
        
        function deletePromo(id) {
            if(confirm('Xóa khuyến mãi này?')) {
                alert('Đã xóa khuyến mãi!');
            }
        }
    </script>
</body>
</html>
