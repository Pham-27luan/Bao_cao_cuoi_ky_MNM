<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MY BUS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="font-['Inter']">
    <div class="admin-container">
        @include('admin.partials.sidebar')

        <main class="admin-main">
            <div class="main-content">
                <div class="page-header">
                    <h1>Dashboard</h1>
                    <p>Chào mừng bạn đến với trang quản trị</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Tổng người dùng</p>
                                <p class="stat-value">{{ number_format($totalUsers ?? 0) }}</p>
                            </div>
                            <div class="stat-icon bg-blue-100">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Tổng tuyến xe</p>
                                <p class="stat-value">{{ number_format($totalRoutes ?? 0) }}</p>
                            </div>
                            <div class="stat-icon bg-green-100">
                                <i class="fas fa-route text-green-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Đơn hàng hôm nay</p>
                                <p class="stat-value">{{ number_format($todayOrders ?? 0) }}</p>
                            </div>
                            <div class="stat-icon bg-orange-100">
                                <i class="fas fa-ticket-alt text-orange-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Doanh thu tháng</p>
                                <p class="stat-value">{{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}đ</p>
                            </div>
                            <div class="stat-icon bg-purple-100">
                                <i class="fas fa-chart-line text-purple-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="recent-activity">
                    <h2 class="activity-title">Hoạt động gần đây</h2>
                    <div class="activity-list">
                        @forelse($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="activity-icon {{ $activity['icon_bg'] }}">
                                    <i class="{{ $activity['icon'] }} {{ $activity['icon_color'] }}"></i>
                                </div>
                                <div class="activity-text">
                                    <p class="activity-name">{{ $activity['name'] }}</p>
                                    <p class="activity-time">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="activity-item">
                                <div class="activity-icon bg-blue-100">
                                    <i class="fas fa-database text-blue-600"></i>
                                </div>
                                <div class="activity-text">
                                    <p class="activity-name">Chưa có dữ liệu hoạt động</p>
                                    <p class="activity-time">Hệ thống sẽ cập nhật khi có dữ liệu mới</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
