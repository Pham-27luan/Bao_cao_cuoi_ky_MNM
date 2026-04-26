<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý xe - Admin</title>
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
                        <h1>Quản lý xe</h1>
                        <p>Danh sách tất cả xe trong hệ thống</p>
                    </div>
                    <button onclick="openCreateBusModal()" class="btn-primary">
                        <i class="fas fa-plus"></i> Thêm xe mới
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
                
                <!-- Filter -->
                <div class="filter-section">
                    <div class="filter-group">
                        <select id="statusFilter" class="filter-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="Đang hoạt động">Đang hoạt động</option>
                            <option value="Bảo trì">Bảo trì</option>
                            <option value="Ngừng hoạt động">Ngừng hoạt động</option>
                        </select>
                        <input type="text" id="searchInput" placeholder="Tìm kiếm biển số hoặc loại xe..." class="filter-search">
                        <button class="filter-btn" onclick="filterBuses()">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <button class="filter-btn" onclick="resetBusFilter()">
                            <i class="fas fa-redo"></i> Làm mới
                        </button>
                    </div>
                </div>
                
                <!-- Bảng danh sách xe -->
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Biển số xe</th>
                                <th>Loại xe</th>
                                <th>Số ghế</th>
                                <th>Nhà xe</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="busTableBody">
                            @forelse($buses as $bus)
                            <tr class="bus-row" data-status="{{ $bus->trangthai }}" data-search="{{ strtolower($bus->biensoxe . ' ' . $bus->loaixe) }}">
                                <td>{{ $bus->maxe }}</td>
                                <td class="font-medium">{{ $bus->biensoxe }}</td>
                                <td>{{ $bus->loaixe }}</td>
                                <td>{{ $bus->soghe }}</td>
                                <td>{{ $bus->nhaxe }}</td>
                                <td>
                                    @if($bus->trangthai === 'Đang hoạt động')
                                        <span class="badge-success"><i class="fas fa-check-circle"></i> {{ $bus->trangthai }}</span>
                                    @elseif($bus->trangthai === 'Bảo trì')
                                        <span class="badge-warning"><i class="fas fa-tools"></i> {{ $bus->trangthai }}</span>
                                    @else
                                        <span class="badge-danger"><i class="fas fa-stop-circle"></i> {{ $bus->trangthai }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="action-group">
                                        <button onclick="editBus({{ $bus->maxe }})" class="action-edit" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteBus({{ $bus->maxe }})" class="action-delete" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center p-4 text-gray-500">
                                    <i class="fas fa-database"></i> Chưa có dữ liệu xe
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Phân trang -->
                <div class="pagination">
                    <p class="pagination-info">Hiển thị <span id="showingCount">{{ count($buses) }}</span> / <span id="totalCount">{{ count($buses) }}</span> xe</p>
                </div>
            </div>
        </main>
    </div>
    
<!-- Modal thêm/sửa xe -->
<div id="busModal" class="modal">
    <div class="modal-content">
        <h2 class="modal-header" id="modalTitle">Thêm xe mới</h2>
        <form id="busForm" method="POST" action="{{ route('admin.buses.store') }}">
            @csrf
            <input type="hidden" id="busId" name="busId">
            <div class="form-group">
                <label class="form-label">Biển số xe <span class="required">*</span></label>
                <input type="text" id="biensoxe" name="biensoxe" class="form-input" placeholder="VD: 29A-12345" required>
            </div>
            <div class="form-group">
                <label class="form-label">Loại xe <span class="required">*</span></label>
                <select id="loaixe" name="loaixe" class="form-select" required>
                    <option value="">Chọn loại xe</option>
                    <option value="Giường nằm">Giường nằm</option>
                    <option value="Limousine">Limousine</option>
                    <option value="Ghế ngồi">Ghế ngồi</option>
                    <option value="Xe khách">Xe khách</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Số ghế <span class="required">*</span></label>
                <input type="number" id="soghe" name="soghe" class="form-input" placeholder="Số lượng ghế" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nhà xe <span class="required">*</span></label>
                <input type="text" id="nhaxe" name="nhaxe" class="form-input" placeholder="Tên nhà xe" required>
            </div>
            <div class="form-group">
                <label class="form-label">Trạng thái <span class="required">*</span></label>
                <select id="trangthai" name="trangthai" class="form-select" required>
                    <option value="Đang hoạt động">Đang hoạt động</option>
                    <option value="Bảo trì">Bảo trì</option>
                    <option value="Ngừng hoạt động">Ngừng hoạt động</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeBusModal()" class="btn-outline flex-1">Hủy</button>
                <button type="submit" class="btn-primary flex-1">Lưu</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal xác nhận xóa xe -->
<div id="deleteBusModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header" style="color: #dc2626;">
            <i class="fas fa-exclamation-triangle"></i> Xác nhận xóa xe
        </div>
        <div class="modal-body">
            <p>Bạn có chắc chắn muốn xóa xe này?</p>
            <p class="text-sm text-gray-500 mt-2">Hành động này không thể hoàn tác!</p>
        </div>
        <div class="modal-footer">
            <button onclick="closeDeleteBusModal()" class="btn-outline">Hủy</button>
            <button id="confirmDeleteBusBtn" class="btn-primary" style="background-color: #dc2626;">Xóa</button>
        </div>
    </div>
</div>
    
    <script src="{{ asset('js/admin.js') }}?v=2"></script>

</body>
</html>
