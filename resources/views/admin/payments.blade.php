<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý thanh toán - Admin</title>
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
                        <h1>Quản lý thanh toán</h1>
                        <p>Theo dõi tất cả giao dịch thanh toán</p>
                    </div>
                    <div class="flex gap-3">
                        <select class="filter-select" id="dateRange">
                            <option>7 ngày qua</option>
                            <option>30 ngày qua</option>
                            <option>Tháng này</option>
                            <option>Tháng trước</option>
                        </select>
                        <button class="btn-primary" onclick="exportReport()">
                            <i class="fas fa-file-excel"></i> Xuất báo cáo
                        </button>
                    </div>
                </div>
                
                <!-- Thống kê doanh thu -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Tổng doanh thu</p>
                                <p class="stat-value">185,500,000đ</p>
                            </div>
                            <div class="stat-icon bg-blue-100">
                                <i class="fas fa-chart-line text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Hoàn thành</p>
                                <p class="stat-value text-green-600">178,200,000đ</p>
                            </div>
                            <div class="stat-icon bg-green-100">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Chờ xử lý</p>
                                <p class="stat-value text-yellow-600">5,300,000đ</p>
                            </div>
                            <div class="stat-icon bg-yellow-100">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Thất bại/Hủy</p>
                                <p class="stat-value text-red-600">2,000,000đ</p>
                            </div>
                            <div class="stat-icon bg-red-100">
                                <i class="fas fa-times-circle text-red-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Filter -->
                <div class="filter-section">
                    <div class="filter-group">
                        <input type="date" class="filter-input" placeholder="Từ ngày">
                        <input type="date" class="filter-input" placeholder="Đến ngày">
                        <select class="filter-select">
                            <option>Tất cả trạng thái</option>
                            <option>Thành công</option>
                            <option>Chờ xử lý</option>
                            <option>Thất bại</option>
                        </select>
                        <input type="text" placeholder="Mã giao dịch / Mã vé..." class="filter-search">
                        <button class="filter-btn">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
                
                <!-- Bảng giao dịch -->
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã GD</th>
                            <th>Mã vé</th>
                            <th>Khách hàng</th>
                            <th>Số tiền</th>
                            <th>Phương thức</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-medium">#PAY-001</td>
                            <td>#V-001234</td>
                            <td>Nguyễn Văn A</td>
                            <td class="font-semibold">150,000đ</td>
                            <td>Chuyển khoản</td>
                            <td>15/04/2026 09:30</td>
                            <td><span class="badge-success">Thành công</span></td>
                            <td class="text-center">
                                <button onclick="viewDetail('PAY-001')" class="action-view">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="font-medium">#PAY-002</td>
                            <td>#V-001235</td>
                            <td>Trần Thị B</td>
                            <td class="font-semibold">120,000đ</td>
                            <td>VNPay</td>
                            <td>15/04/2026 10:15</td>
                            <td><span class="badge-warning">Chờ xử lý</span></td>
                            <td class="text-center">
                                <button onclick="viewDetail('PAY-002')" class="action-view">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="font-medium">#PAY-003</td>
                            <td>#V-001236</td>
                            <td>Lê Văn C</td>
                            <td class="font-semibold">200,000đ</td>
                            <td>Momo</td>
                            <td>14/04/2026 14:20</td>
                            <td><span class="badge-danger">Thất bại</span></td>
                            <td class="text-center">
                                <button onclick="viewDetail('PAY-003')" class="action-view">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="pagination">
                    <p class="pagination-info">Hiển thị 1-3 của 45 giao dịch</p>
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
    
    <script>
        function viewDetail(id) {
            alert('Chi tiết giao dịch: ' + id);
        }
        
        function exportReport() {
            alert('Xuất báo cáo thanh toán');
        }
    </script>
</body>
</html>
