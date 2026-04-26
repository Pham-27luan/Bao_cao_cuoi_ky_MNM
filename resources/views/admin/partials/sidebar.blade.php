<aside class="admin-sidebar">
    <div class="sidebar-header">
        <h1>
            <i class="fas fa-bus"></i> CHÚ THIỆN
        </h1>
        <p>An toàn - Nhanh chóng - Tiện lợi</p>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i>
            <span>Bảng điều khiển</span>
        </a>
        <a href="{{ route('admin.users') }}" class="sidebar-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Quản lý người dùng</span>
        </a>
        <a href="{{ route('admin.buses') }}" class="sidebar-item {{ request()->routeIs('admin.buses*') ? 'active' : '' }}">
            <i class="fas fa-bus"></i>
            <span>Quản lý xe</span>
        </a>
        <a href="{{ route('admin.routes') }}" class="sidebar-item {{ request()->routeIs('admin.routes*') ? 'active' : '' }}">
            <i class="fas fa-route"></i>
            <span>Quản lý tuyến</span>
        </a>
        <a href="{{ route('admin.trips') }}" class="sidebar-item {{ request()->routeIs('admin.trips*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i>
            <span>Quản lý chuyến</span>
        </a>
        <a href="{{ route('admin.tickets') }}" class="sidebar-item {{ request()->routeIs('admin.tickets*') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt"></i>
            <span>Quản lý vé</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </button>
        </form>
    </div>
</aside>
