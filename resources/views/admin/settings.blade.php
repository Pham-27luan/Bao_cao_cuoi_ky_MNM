<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài đặt hệ thống - Admin</title>
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
                    <h1>Cài đặt hệ thống</h1>
                    <p>Cấu hình thông tin chung của hệ thống</p>
                </div>
                
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Thông tin chung -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Thông tin chung</h2>
                        <form id="generalForm">
                            <div class="form-group">
                                <label class="form-label">Tên công ty</label>
                                <input type="text" class="form-input" value="Công ty vận tải MY BUS">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" class="form-input" value="Q. Ninh Kiều, TP. Cần Thơ">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-input" value="1900 2082">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" value="hotro@gmail.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Website</label>
                                <input type="text" class="form-input" value="https://mybus.vn">
                            </div>
                        </form>
                    </div>
                    
                    <!-- Cấu hình thanh toán -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Cấu hình thanh toán</h2>
                        <form id="paymentForm">
                            <div class="form-group">
                                <label class="form-label">Cổng thanh toán mặc định</label>
                                <select class="form-select">
                                    <option>VNPay</option>
                                    <option>Momo</option>
                                    <option>ZaloPay</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">VNPay - Merchant ID</label>
                                <input type="text" class="form-input" placeholder="Nhập Merchant ID">
                            </div>
                            <div class="form-group">
                                <label class="form-label">VNPay - Secret Key</label>
                                <input type="password" class="form-input" placeholder="Nhập Secret Key">
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="testMode">
                                <label for="testMode">Chế độ test (sandbox)</label>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Cấu hình email -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Cấu hình email</h2>
                        <form id="emailForm">
                            <div class="form-group">
                                <label class="form-label">SMTP Server</label>
                                <input type="text" class="form-input" placeholder="smtp.gmail.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label">SMTP Port</label>
                                <input type="text" class="form-input" placeholder="587">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email gửi</label>
                                <input type="email" class="form-input" placeholder="no-reply@mybus.vn">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mật khẩu ứng dụng</label>
                                <input type="password" class="form-input" placeholder="Nhập mật khẩu">
                            </div>
                        </form>
                    </div>
                    
                    <!-- Cấu hình hệ thống -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Cấu hình hệ thống</h2>
                        <form id="systemForm">
                            <div class="form-group">
                                <label class="form-label">Thời gian giữ chỗ (phút)</label>
                                <input type="number" class="form-input" value="15">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Thời gian hủy vé (giờ trước giờ đi)</label>
                                <input type="number" class="form-input" value="2">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Số vé tối đa/đơn hàng</label>
                                <input type="number" class="form-input" value="10">
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="autoConfirm">
                                <label for="autoConfirm">Tự động xác nhận thanh toán</label>
                            </div>
                            <div class="checkbox-group">
                                <input type="checkbox" id="sendEmail">
                                <label for="sendEmail">Gửi email xác nhận khi đặt vé</label>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Nút lưu -->
                <div class="mt-6 flex gap-4">
                    <button onclick="saveSettings()" class="btn-primary">
                        <i class="fas fa-save"></i> Lưu tất cả cài đặt
                    </button>
                    <button onclick="resetSettings()" class="btn-outline">
                        <i class="fas fa-undo"></i> Khôi phục mặc định
                    </button>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        function saveSettings() {
            alert('Đã lưu cài đặt hệ thống!');
        }
        
        function resetSettings() {
            if(confirm('Khôi phục cài đặt mặc định?')) {
                alert('Đã khôi phục cài đặt mặc định');
            }
        }
    </script>
</body>
</html>
