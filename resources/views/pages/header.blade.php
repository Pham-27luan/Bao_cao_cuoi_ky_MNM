<style>
    .main-header,
    .main-header * {
        box-sizing: border-box;
    }

    .main-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        width: 100%;
        background: rgba(255, 255, 255, 0.92);
        border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(16px);
    }

    .container-header {
        width: min(1180px, calc(100% - 32px));
        min-height: 66px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .left-section {
        display: flex;
        align-items: center;
        gap: 28px;
        min-width: 0;
    }

    .logo {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #0f2b3d;
        text-decoration: none;
    }

    .logo-mark {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        color: #fff;
        background: linear-gradient(135deg, #0f766e, #1e5a7a);
        box-shadow: 0 10px 22px rgba(15, 118, 110, 0.22);
    }

    .logo h1 {
        margin: 0;
        color: #0f2b3d;
        font-size: 18px;
        text-align: center;
        line-height: 1.1;
        font-weight: 800;
        letter-spacing: 0;
    }

    .logo p {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 12px;
        line-height: 1.2;
        font-weight: 600;
    }

    .navbar ul {
        display: flex;
        align-items: center;
        list-style: none;
        gap: 6px;
        margin: 0;
        padding: 4px;
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        background: #f8fafc;
    }

    .navbar a {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 36px;
        padding: 0 14px;
        border-radius: 999px;
        color: #334155;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        white-space: nowrap;
        transition: color 0.18s ease, background 0.18s ease, box-shadow 0.18s ease;
    }

    .navbar a:hover,
    .navbar a.is-active {
        color: #0f766e;
        background: #fff;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
    }

    .user-actions,
    .user-menu {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 0 0 auto;
    }

    .user-name {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        max-width: 220px;
        min-height: 38px;
        padding: 0 12px;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-name i {
        color: #0f766e;
        flex: 0 0 auto;
    }

    .user-actions a,
    .logout-btn {
        min-height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 0 14px;
        border-radius: 999px;
        border: 1px solid transparent;
        color: #334155;
        background: transparent;
        text-decoration: none;
        font-family: inherit;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        transition: background 0.18s ease, color 0.18s ease, border-color 0.18s ease, transform 0.18s ease;
    }

    .user-actions .login {
        border-color: #dbe6ef;
        background: #fff;
    }

    .user-actions .register {
        color: #fff;
        background: #0f766e;
        box-shadow: 0 10px 20px rgba(15, 118, 110, 0.2);
    }

    .user-actions .login:hover,
    .logout-btn:hover {
        color: #0f766e;
        background: #f0fdfa;
        border-color: #99f6e4;
    }

    .user-actions .register:hover {
        background: #115e59;
        transform: translateY(-1px);
    }

    .logout-btn {
        border-color: #e2e8f0;
        background: #fff;
    }

    @media (max-width: 920px) {
        .container-header {
            width: min(100% - 20px, 1180px);
            gap: 12px;
        }

        .left-section {
            gap: 12px;
        }

        .logo p,
        .navbar i {
            display: none;
        }

        .navbar ul {
            max-width: 42vw;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .navbar ul::-webkit-scrollbar {
            display: none;
        }
    }

    @media (max-width: 680px) {
        .container-header {
            min-height: 64px;
        }

        .logo-mark {
            width: 38px;
            height: 38px;
            border-radius: 12px;
        }

        .logo h1 {
            font-size: 15px;
        }

        .navbar {
            order: 3;
            width: 100%;
            display: none;
        }

        .user-name {
            max-width: 140px;
            padding: 0 10px;
        }

        .user-actions a,
        .logout-btn {
            padding: 0 11px;
        }
    }

    @media (max-width: 460px) {
        .container-header {
            width: calc(100% - 16px);
        }

        .logo h1 {
            font-size: 14px;
        }

        .user-name {
            display: none;
        }

        .user-actions .login {
            display: none;
        }
    }
</style>

<header class="main-header">
    <div class="container-header">
        <div class="left-section">
            <a class="logo" href="{{ route('home') }}" aria-label="Chú Thiện">
                <span class="logo-mark"><i class="fas fa-bus"></i></span>
                <span>
                    <h1>CHÚ THIỆN</h1>
                    <p>An toàn - Nhanh chóng - Tiện lợi</p>
                </span>
            </a>

            <nav class="navbar" aria-label="Điều hướng chính">
                <ul>
                    <li>
                        <a class="{{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-house"></i>
                            Trang chủ
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#routes">
                            <i class="fas fa-ticket-alt"></i>
                            Đặt vé
                        </a>
                    </li>
                    <li>
                        <a class="{{ request()->routeIs('bill') ? 'is-active' : '' }}" href="{{ route('bill') }}">
                            <i class="fas fa-receipt"></i>
                            Hóa đơn
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="user-actions">
            @if(session('isLoggedIn'))
                <div class="user-menu">
                    <span class="user-name" title="{{ session('userFullName', session('userName', session('userPhone', 'User'))) }}">
                        <i class="fas fa-user-circle"></i>
                        {{ session('userFullName', session('userName', session('userPhone', 'User'))) }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-arrow-right-from-bracket"></i>
                            Đăng xuất
                        </button>
                    </form>
                </div>
            @else
                <a class="login" href="{{ route('login') }}">
                    <i class="fas fa-right-to-bracket"></i>
                    Đăng nhập
                </a>
                <a class="register" href="{{ route('register') }}">
                    <i class="fas fa-user-plus"></i>
                    Đăng ký
                </a>
            @endif
        </div>
    </div>
</header>
