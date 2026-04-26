<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tuyến - Admin</title>
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
                        <h1>Quản lý tuyến</h1>
                        <p>Danh sách tất cả tuyến xe</p>
                    </div>
                    <button onclick="openCreateRouteModal()" class="btn-primary">
                        <i class="fas fa-plus"></i> Thêm tuyến mới
                    </button>
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

                <div class="filter-section">
                    <div class="filter-group">
                        <input type="text" id="searchInput" placeholder="Tìm kiếm điểm đi, điểm đến..." class="filter-search">
                        <button class="filter-btn" onclick="filterRoutes()">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <button class="filter-btn" onclick="resetRouteFilter()">
                            <i class="fas fa-redo"></i> Làm mới
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên tuyến</th>
                                <th>Điểm đi</th>
                                <th>Điểm đến</th>
                                <th>Khoảng cách</th>
                                <th>Thời gian</th>
                                <th>Giá vé</th>
                                <th>Biển số xe</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($routes as $route)
                                <tr class="route-row" data-search="{{ strtolower($route->tentuyen . ' ' . $route->diemdi . ' ' . $route->diemden) }}">
                                    <td>{{ $route->matuyen }}</td>
                                    <td class="font-medium">{{ $route->tentuyen }}</td>
                                    <td class="font-medium">{{ $route->diemdi }}</td>
                                    <td>{{ $route->diemden }}</td>
                                    <td>{{ number_format($route->khoangcach) }} km</td>
                                    <td class="font-medium">{{ $route->thoigiandukien ?? $route->thoigian ?? 'Chưa cập nhật' }}</td>
                                    <td class="font-semibold text-green-600">{{ number_format($route->giatien) }}đ</td>
                                    <td>
                                        @if($route->maxe)
                                            <span class="badge-info">{{ $route->xe->biensoxe ?? 'Chưa cập nhật' }}</span>
                                        @else
                                            <span class="badge-warning">Chưa phân công</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($route->trangthai === 'Đang hoạt động')
                                            <span class="badge-success">Đang hoạt động</span>
                                        @else
                                            <span class="badge-danger">Ngừng hoạt động</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="action-group">
                                            <button onclick="editRoute({{ $route->matuyen }})" class="action-edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteRoute({{ $route->matuyen }})" class="action-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center p-4 text-gray-500">
                                        <i class="fas fa-database"></i> Chưa có dữ liệu tuyến xe
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    <p class="pagination-info">Hiển thị <span id="showingCount">{{ count($routes) }}</span> / <span id="totalCount">{{ count($routes) }}</span> tuyến</p>
                </div>
            </div>
        </main>
    </div>

    <div id="routeModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-header" id="routeModalTitle">Thêm tuyến mới</h2>
            <form id="routeForm" method="POST" action="{{ route('admin.routes.store') }}">
                @csrf
                <input type="hidden" id="routeId" name="routeId">

                <div class="form-group">
                    <label class="form-label">Tên tuyến <span class="required">*</span></label>
                    <input type="text" id="tentuyen" name="tentuyen" class="form-input" placeholder="VD: Nam Định - Hà Nội" value="{{ old('tentuyen') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Chọn xe</label>
                    <select id="maxe" name="maxe" class="form-select">
                        <option value="">-- Chọn xe --</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->maxe }}" {{ (string) old('maxe') === (string) $bus->maxe ? 'selected' : '' }}>
                                {{ $bus->biensoxe }} - {{ $bus->loaixe }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Điểm đi <span class="required">*</span></label>
                    <input type="text" id="diemdi" name="diemdi" class="form-input" placeholder="VD: Hà Nội" value="{{ old('diemdi') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Điểm đến <span class="required">*</span></label>
                    <input type="text" id="diemden" name="diemden" class="form-input" placeholder="VD: Nam Định" value="{{ old('diemden') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Khoảng cách (km) <span class="required">*</span></label>
                    <input type="number" id="khoangcach" name="khoangcach" class="form-input" placeholder="VD: 110" value="{{ old('khoangcach') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Thời gian <span class="required">*</span></label>
                    <input type="text" id="thoigian" name="thoigian" class="form-input" placeholder="VD: 2.5h" value="{{ old('thoigian') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Giá vé (VNĐ) <span class="required">*</span></label>
                    <input type="number" id="giatien" name="giatien" class="form-input" placeholder="VD: 150000" value="{{ old('giatien') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Trạng thái <span class="required">*</span></label>
                    <select id="trangthai" name="trangthai" class="form-select" required>
                        <option value="Đang hoạt động" {{ old('trangthai', 'Đang hoạt động') === 'Đang hoạt động' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="Ngừng hoạt động" {{ old('trangthai') === 'Ngừng hoạt động' ? 'selected' : '' }}>Ngừng hoạt động</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeRouteModal()" class="btn-outline flex-1">Hủy</button>
                    <button type="submit" class="btn-primary flex-1">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteRouteModal" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header" style="color: #dc2626;">
                <i class="fas fa-exclamation-triangle"></i> Xác nhận xóa tuyến
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa tuyến này?</p>
                <p class="text-sm text-gray-500 mt-2">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button onclick="closeDeleteRouteModal()" class="btn-outline">Hủy</button>
                <button id="confirmDeleteRouteBtn" class="btn-primary" style="background-color: #dc2626;">Xóa</button>
            </div>
        </div>
    </div>

    <script>
        window.routeFormHasErrors = @json($errors->any());
    </script>
    
    <script src="{{ asset('js/admin.js') }}?v=2"></script>
</body>
</html>
