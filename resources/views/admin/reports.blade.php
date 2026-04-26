<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo thống kê - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="font-['Inter']">
    <div class="admin-container">
        @include('admin.partials.sidebar')
        
        <main class="admin-main">
            <div class="main-content">
                <div class="page-header">
                    <h1>Báo cáo thống kê</h1>
                    <p>Tổng quan dữ liệu và báo cáo chi tiết</p>
                </div>
                
                <!-- Bộ lọc thời gian -->
                <div class="filter-section">
                    <div class="filter-group">
                        <span class="font-semibold text-gray-700">Thời gian:</span>
                        <select class="filter-select" id="timeRange">
                            <option>Hôm nay</option>
                            <option>7 ngày qua</option>
                            <option>30 ngày qua</option>
                            <option>Tháng này</option>
                            <option>Tháng trước</option>
                            <option>Năm nay</option>
                        </select>
                        <button class="btn-primary" onclick="refreshReport()">
                            <i class="fas fa-chart-line"></i> Xem báo cáo
                        </button>
                        <button class="btn-outline" onclick="exportReport()">
                            <i class="fas fa-file-excel"></i> Xuất Excel
                        </button>
                    </div>
                </div>
                
                <!-- Thống kê nhanh -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Tổng doanh thu</p>
                                <p class="stat-value">185.5M</p>
                                <span class="text-green-600 text-sm">↑ 12.5%</span>
                            </div>
                            <div class="stat-icon bg-blue-100">
                                <i class="fas fa-chart-line text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Số lượng vé</p>
                                <p class="stat-value">1,234</p>
                                <span class="text-green-600 text-sm">↑ 8.3%</span>
                            </div>
                            <div class="stat-icon bg-green-100">
                                <i class="fas fa-ticket-alt text-green-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Người dùng mới</p>
                                <p class="stat-value">89</p>
                                <span class="text-green-600 text-sm">↑ 15.2%</span>
                            </div>
                            <div class="stat-icon bg-purple-100">
                                <i class="fas fa-users text-purple-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Tỷ lệ lấp đầy</p>
                                <p class="stat-value">78%</p>
                                <span class="text-yellow-600 text-sm">↓ 2.1%</span>
                            </div>
                            <div class="stat-icon bg-orange-100">
                                <i class="fas fa-chart-simple text-orange-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Biểu đồ -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-4">Doanh thu theo ngày</h3>
                        <canvas id="revenueChart" height="200"></canvas>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-4">Top tuyến xe bán chạy</h3>
                        <canvas id="routesChart" height="200"></canvas>
                    </div>
                </div>
                
                <!-- Báo cáo chi tiết -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-bold text-gray-800">Báo cáo chi tiết theo tuyến</h3>
                    </div>
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left p-3 font-semibold text-gray-600">Tuyến đường</th>
                                <th class="text-left p-3 font-semibold text-gray-600">Số chuyến</th>
                                <th class="text-left p-3 font-semibold text-gray-600">Số vé</th>
                                <th class="text-left p-3 font-semibold text-gray-600">Doanh thu</th>
                                <th class="text-left p-3 font-semibold text-gray-600">Tỷ lệ lấp đầy</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <td class="p-3 font-medium">Hà Nội - Nam Định</td>
                                <td class="p-3">120</td>
                                <td class="p-3">3,245</td>
                                <td class="p-3 font-semibold">486.7M</td>
                                <td class="p-3"><span class="badge-success">85%</span></td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="p-3 font-medium">Hà Nội - Ninh Bình</td>
                                <td class="p-3">98</td>
                                <td class="p-3">2,156</td>
                                <td class="p-3 font-semibold">258.7M</td>
                                <td class="p-3"><span class="badge-success">78%</span></td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="p-3 font-medium">Nam Định - Thanh Hóa</td>
                                <td class="p-3">85</td>
                                <td class="p-3">1,890</td>
                                <td class="p-3 font-semibold">170.1M</td>
                                <td class="p-3"><span class="badge-warning">72%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Biểu đồ doanh thu
        const ctx1 = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
                datasets: [{
                    label: 'Doanh thu (triệu đồng)',
                    data: [12, 19, 15, 17, 14, 23, 21],
                    borderColor: '#0f766e',
                    backgroundColor: 'rgba(15, 118, 110, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });
        
        // Biểu đồ tuyến xe
        const ctx2 = document.getElementById('routesChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['HN-ND', 'HN-NB', 'ND-TH', 'HN-HP', 'ND-HN'],
                datasets: [{
                    label: 'Số lượng vé',
                    data: [245, 189, 156, 98, 87],
                    backgroundColor: '#14b8a6',
                    borderRadius: 8
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });
        
        function refreshReport() {
            alert('Đang tải báo cáo mới...');
        }
        
        function exportReport() {
            alert('Xuất báo cáo Excel');
        }
    </script>
</body>
</html>
