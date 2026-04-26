<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-content">
            <!-- Cột 1: Kết nối với chúng tôi -->
            <div class="footer-col">
                <h3>Kết nối với chúng tôi</h3>
                <div class="contact-info">
                    <p class="phone">📞 1900 2082</p>
                    <p>📍 Địa chỉ: Q. Ninh Kiều, TP. Cần Thơ</p>
                    <p>✉️ Email: hotro@gmail.com</p>
                </div>
            </div>

            <!-- Cột 2: Hướng dẫn -->
            <div class="footer-col">
                <h3>Hướng dẫn</h3>
                <ul class="footer-links">
                    <li><a href="#">Hướng dẫn đặt vé trên Web</a></li>
                    <li><a href="#">Hướng dẫn đặt vé trên App</a></li>
                    <li><a href="#">Hồi Đáp</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                </ul>
            </div>

            <!-- Cột 3: Đi đến trang -->
            <div class="footer-col">
                <h3>Đi đến trang</h3>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}">Trang Chủ</a></li>
                    <li><a href="#">Lịch Trình</a></li>
                    <li><a href="{{ route('register') }}">Đăng ký</a></li>
                    <li><a href="{{ route('login') }}">Đăng Nhập</a></li>
                </ul>
            </div>

            <!-- Cột 4: Khác -->
            <div class="footer-col">
                <h3>Khác</h3>
                <ul class="footer-links">
                    <li><a href="#">Trở thành nhà cung cấp</a></li>
                    <li><a href="#">Cộng tác với chúng tôi</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">Liên Kết Hữu Dụng</a></li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom">
            <p>© 2026 Công ty vận tải. Tất cả các quyền được bảo lưu.</p>
        </div>
    </div>
</footer>

<style>
    .main-footer,
    .main-footer * {
        box-sizing: border-box;
    }

    .main-footer {
        background-color: #0F172A;
        color: #e2e8f0;
        padding: 40px 0 20px;
        width: 100%;
        position: relative;
        left: 0;
        right: 0;
        margin-top: 0;
        /* Không fixed, nằm cuối trang */
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .footer-content {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 30px;
        margin-bottom: 40px;
    }

    .footer-col {
        flex: 1;
        min-width: 180px;
    }

    .footer-col h3 {
        color: #ffffff;
        font-size: 16px;
        margin-bottom: 20px;
        font-weight: 600;
        position: relative;
        padding-bottom: 12px;
    }

    .footer-col h3::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 40px;
        height: 2px;
        background: linear-gradient(90deg, #3b82f6, #0ea5e9);
    }

    .contact-info p {
        margin: 10px 0;
        line-height: 1.5;
        font-size: 13px;
        color: #cbd5e1;
    }

    .footer-links {
        list-style: none;
        padding: 0;
    }

    .footer-links li {
        margin-bottom: 8px;
    }

    .footer-links a {
        color: #cbd5e1;
        text-decoration: none;
        font-size: 13px;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .footer-links a:hover {
        color: #3b82f6;
        transform: translateX(5px);
    }
    
    .contact-info .phone {
        font-size: 20px;
        font-weight: 600;
    }
    
    .footer-bottom {
        text-align: center;
        padding-top: 20px;
        margin-top: 20px;
        border-top: 1px solid #1e293b;
    }

    .footer-bottom p {
        font-size: 12px;
        color: #94a3b8;
    }

    /* Responsive cho footer */
    @media (max-width: 768px) {
        .main-footer {
            padding: 30px 0 15px;
        }
        
        .footer-content {
            gap: 20px;
        }
        
        .footer-col {
            min-width: 150px;
        }
        
        .footer-col h3 {
            font-size: 14px;
            margin-bottom: 15px;
            padding-bottom: 8px;
        }
        
        .contact-info p {
            font-size: 11px;
            margin: 8px 0;
        }
        
        .footer-links a {
            font-size: 11px;
        }
        
        .contact-info .phone {
            font-size: 16px;
        }
        
        .footer-bottom p {
            font-size: 10px;
        }
    }

    @media (max-width: 480px) {
        .footer-content {
            flex-direction: column;
            gap: 25px;
        }
        
        .footer-col {
            min-width: 100%;
            text-align: center;
        }
        
        .footer-col h3::after {
            left: 50%;
            transform: translateX(-50%);
        }
        
        .contact-info p {
            text-align: center;
        }
        
        .footer-links li {
            text-align: center;
        }
        
        .footer-links a:hover {
            transform: translateX(0) scale(1.05);
        }
    }
</style>
