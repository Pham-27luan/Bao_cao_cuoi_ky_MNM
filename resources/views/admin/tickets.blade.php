<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý vé - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body data-page="admin-tickets" data-ticket-form-has-errors='@json($errors->any())'>
    <div class="admin-container">
        @include('admin.partials.sidebar')

        <main class="admin-main">
            <div class="main-content">
                <div class="header-actions">
                    <div class="page-header">
                        <h1>Quản lý vé</h1>
                        <p>Danh sách tất cả vé đã bán</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                    </div>
                @endif

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Tổng vé đã bán</p>
                                <p class="stat-value">{{ number_format($totalTickets ?? count($tickets)) }}</p>
                            </div>
                            <div class="stat-icon bg-blue-100">
                                <i class="fas fa-ticket-alt text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Doanh thu</p>
                                <p class="stat-value">{{ number_format($totalRevenue ?? 0) }}đ</p>
                            </div>
                            <div class="stat-icon bg-green-100">
                                <i class="fas fa-money-bill-wave text-green-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Đã đi</p>
                                <p class="stat-value">{{ $daThanhToan ?? 0 }}</p>
                            </div>
                            <div class="stat-icon bg-success-100">
                                <i class="fas fa-check-circle text-success-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div>
                                <p class="stat-label">Chờ đón</p>
                                <p class="stat-value">{{ $choThanhToan ?? 0 }}</p>
                            </div>
                            <div class="stat-icon bg-warning-100">
                                <i class="fas fa-clock text-warning-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-section">
                    <div class="filter-group">
                        <input type="date" id="filterDateFrom" class="filter-input" placeholder="Từ ngày">
                        <input type="date" id="filterDateTo" class="filter-input" placeholder="Đến ngày">
                        <select id="filterStatus" class="filter-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="cho_don">Chờ đón</option>
                            <option value="da_di">Đã đi</option>
                        </select>
                        <input type="text" id="searchInput" placeholder="Mã vé / SĐT khách..." class="filter-search">
                        <button class="filter-btn" onclick="filterTickets()">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <button class="filter-btn" onclick="resetTicketFilter()">
                            <i class="fas fa-redo"></i> Làm mới
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Mã vé</th>
                                <th>Khách hàng</th>
                                <th>Số điện thoại</th>
                                <th>Số ghế</th>
                                <th>Ngày đặt</th>
                                <th>Hình thức</th>
                                <th>Giá vé</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr class="ticket-row" data-status="{{ $ticket->trangthai }}" data-search="{{ strtolower($ticket->mave . ' ' . ($ticket->taiKhoan->phone ?? '')) }}">
                                    <td class="font-medium" style="color: #2563eb;">#V{{ str_pad($ticket->mave, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $ticket->ten_khach }}</td>
                                    <td>{{ $ticket->so_dien_thoai }}</td>
                                    <td>{{ $ticket->ghe->tenghe ?? 'N/A' }}</td>
                                    <td>{{ $ticket->ngay_dat_formatted }}</td>
                                    <td>
                                        @if($ticket->hinhthucthanhtoan == 'chuyen_khoan')
                                            <span class="badge-info">Chuyển khoản</span>
                                        @else
                                            <span class="badge-info">Tiền mặt</span>
                                        @endif
                                    </td>
                                    <td class="font-semibold">{{ number_format((int) $ticket->tongsotien, 0, ',', '.') }}đ</td>
                                    <td>
                                        @if($ticket->trangthai == 'da_di')
                                            <span class="badge-success"><i class="fas fa-check-circle"></i> Đã đi</span>
                                        @elseif($ticket->trangthai == 'cho_don')
                                            <span class="badge-warning"><i class="fas fa-clock"></i> Chờ đón</span>
                                        @else
                                            <span class="badge-info"><i class="fas fa-info-circle"></i> {{ $ticket->trangthai }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="action-group">
                                            <button onclick="viewTicket({{ $ticket->mave }})" class="action-view" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button onclick="updateTicketStatus({{ $ticket->mave }})" class="action-edit" title="Cập nhật trạng thái">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteTicket({{ $ticket->mave }})" class="action-delete" title="Xóa vé">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button onclick="printTicket({{ $ticket->mave }})" class="action-print" title="In vé">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center p-4 text-gray-500">
                                        <i class="fas fa-database"></i> Chưa có dữ liệu vé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    <p class="pagination-info">Hiển thị <span id="showingCount">{{ count($tickets) }}</span> / <span id="totalCount">{{ count($tickets) }}</span> vé</p>
                </div>
            </div>
        </main>
    </div>

    <div id="ticketModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <h2 class="modal-header">Chi tiết vé</h2>
            <div class="ticket-detail" id="ticketDetail">
                <div class="text-center p-4">
                    <i class="fas fa-spinner fa-spin"></i> Đang tải...
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="closeTicketModal()" class="btn-outline">Đóng</button>
                <button onclick="printCurrentTicket()" class="btn-primary">In vé</button>
            </div>
        </div>
    </div>

    <div id="statusModal" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <h2 class="modal-header">Cập nhật trạng thái vé</h2>
            <form id="statusForm" method="POST">
                @csrf
                <input type="hidden" id="statusTicketId" name="ticketId">
                <div class="form-group">
                    <label class="form-label">Trạng thái <span class="required">*</span></label>
                    <select id="trangthai" name="trangthai" class="form-select" required>
                        <option value="cho_don">Chờ đón</option>
                        <option value="da_di">Đã đi</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeStatusModal()" class="btn-outline flex-1">Hủy</button>
                    <button type="submit" class="btn-primary flex-1">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteTicketModal" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header" style="color: #dc2626;">
                <i class="fas fa-exclamation-triangle"></i> Xác nhận xóa vé
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa vé này?</p>
                <p class="text-sm text-gray-500 mt-2">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button onclick="closeDeleteTicketModal()" class="btn-outline">Hủy</button>
                <button id="confirmDeleteTicketBtn" class="btn-primary" style="background-color: #dc2626;">Xóa</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin.js') }}?v=2"></script>
</body>
</html>
