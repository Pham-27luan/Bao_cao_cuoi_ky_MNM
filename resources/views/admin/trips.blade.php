<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý chuyến - Admin</title>
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
                        <h1>Quản lý chuyến</h1>
                        <p>Danh sách tất cả chuyến xe</p>
                    </div>
                    <button onclick="openCreateTripModal()" class="btn-primary">
                        <i class="fas fa-plus"></i> Thêm chuyến mới
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
                
                <!-- Bộ lọc -->
                <div class="filter-section">
                    <div class="filter-group">
                        <input type="date" id="filterDate" class="filter-input" placeholder="Ngày đi">
                        <select id="filterRoute" class="filter-select">
                            <option value="">Tất cả tuyến</option>
                            @foreach($routes as $route)
                                <option value="{{ $route->matuyen }}">{{ $route->tentuyen }}</option>
                            @endforeach
                        </select>
                        <input type="text" id="searchInput" placeholder="Tìm kiếm chuyến..." class="filter-search">
                        <button class="filter-btn" onclick="filterTrips()">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <button class="filter-btn" onclick="resetTripFilter()">
                            <i class="fas fa-redo"></i> Làm mới
                        </button>
                    </div>
                </div>
                
                <!-- Bảng danh sách chuyến -->
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tuyến đường</th>
                                <th>Ngày đi</th>
                                <th>Giờ đi</th>
                                <th>Xe</th>
                                <th>Ghế trống</th>
                                <th>Giá vé</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trips as $trip)
                                @php
                                    $displayBus = $trip->tuyenXe && $trip->tuyenXe->xe ? $trip->tuyenXe->xe : $trip->xe;
                                    $displayPrice = $trip->tuyenXe && $trip->tuyenXe->giatien
                                        ? ((float) $trip->tuyenXe->giatien < 1000 ? (float) $trip->tuyenXe->giatien * 1000 : (float) $trip->tuyenXe->giatien)
                                        : $trip->giave;
                                @endphp
                                <tr class="trip-row" data-route="{{ $trip->matuyen }}" data-ngay="{{ $trip->ngaydi }}" data-search="{{ strtolower($trip->ten_tuyen . ' ' . ($displayBus->biensoxe ?? '')) }}">
                                    <td>{{ $trip->machuyen }}</td>
                                    <td class="font-medium">{{ $trip->ten_tuyen }}</td>
                                    <td>{{ $trip->ngay_di_formatted }}</td>
                                    <td>{{ $trip->giodi }}</td>
                                    <td>{{ $displayBus->biensoxe ?? 'Chưa cập nhật' }}</td>
                                    <td>
                                        @php
                                            $tongGhe = $displayBus ? $displayBus->soghe : 0;
                                            $gheTrong = $displayBus
                                                ? $displayBus->ghes->filter(fn($ghe) => $ghe->trangthai !== 'da_dat')->count()
                                                : 0;
                                            $gheTrong = min((int) $gheTrong, (int) $tongGhe);
                                            $percent = $tongGhe > 0 ? ($gheTrong / $tongGhe) * 100 : 0;
                                        @endphp
                                        <span class="{{ $gheTrong > 10 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                            {{ $gheTrong }}/{{ $tongGhe }}
                                        </span>
                                    </td>
                                    <td class="font-semibold">{{ number_format($displayPrice, 0, ',', '.') }}đ</td>
                                    <td class="text-center">
                                        <div class="action-group">
                                            <button onclick="editTrip({{ $trip->machuyen }})" class="action-edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteTrip({{ $trip->machuyen }})" class="action-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center p-4 text-gray-500">
                                        <i class="fas fa-database"></i> Chưa có dữ liệu chuyến xe
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Phân trang -->
                <div class="pagination">
                    <p class="pagination-info">Hiển thị <span id="showingCount">{{ count($trips) }}</span> / <span id="totalCount">{{ count($trips) }}</span> chuyến</p>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Modal thêm/sửa chuyến -->
    <div id="tripModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-header" id="tripModalTitle">Thêm chuyến mới</h2>
            <form id="tripForm" method="POST" action="{{ route('admin.trips.store') }}">
                @csrf
                <input type="hidden" id="tripId" name="tripId">
                
                <div class="form-group">
                    <label class="form-label">Chọn tuyến <span class="required">*</span></label>
                    <select id="matuyen" name="matuyen" class="form-select" required>
                        <option value="">-- Chọn tuyến --</option>
                        @foreach($routes as $route)
                            <option
                                value="{{ $route->matuyen }}"
                                data-maxe="{{ $route->maxe }}"
                                data-giave="{{ $route->giatien > 0 && $route->giatien < 1000 ? $route->giatien * 1000 : $route->giatien }}"
                                {{ old('matuyen') == $route->matuyen ? 'selected' : '' }}
                            >
                                {{ $route->tentuyen }} - {{ $route->diemdi }} đi {{ $route->diemden }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Xe theo tuyến <span class="required">*</span></label>
                    <select id="maxe" name="maxe" class="form-select" required>
                        <option value="">-- Tự lấy theo tuyến --</option>
                        @foreach($buses as $bus)
                            <option
                                value="{{ $bus->maxe }}"
                                data-soghe="{{ $bus->soghe }}"
                                data-ghe-trong="{{ min($bus->ghes->filter(fn($ghe) => $ghe->trangthai !== 'da_dat')->count(), $bus->soghe) }}"
                                {{ old('maxe') == $bus->maxe ? 'selected' : '' }}
                            >
                                {{ $bus->biensoxe }} - {{ $bus->loaixe }} ({{ $bus->soghe }} ghế)
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Ngày đi <span class="required">*</span></label>
                    <input type="date" id="ngaydi" name="ngaydi" class="form-input" value="{{ old('ngaydi') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Giờ đi <span class="required">*</span></label>
                    <input type="time" id="giodi" name="giodi" class="form-input" value="{{ old('giodi') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Giá vé theo tuyến (VNĐ) <span class="required">*</span></label>
                    <input type="number" id="giave" name="giave" class="form-input" placeholder="Tự lấy theo tuyến" value="{{ old('giave') }}" readonly required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Số ghế trống <span class="required">*</span></label>
                    <input type="number" id="ghe_trong" name="ghe_trong" class="form-input" placeholder="Tự cập nhật theo xe" value="{{ old('ghe_trong') }}" min="0" readonly required>
                </div>
                
                <div class="modal-footer">
                    <button type="button" onclick="closeTripModal()" class="btn-outline flex-1">Hủy</button>
                    <button type="submit" class="btn-primary flex-1">Lưu</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal xác nhận xóa -->
    <div id="deleteTripModal" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header" style="color: #dc2626;">
                <i class="fas fa-exclamation-triangle"></i> Xác nhận xóa chuyến
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa chuyến này?</p>
                <p class="text-sm text-gray-500 mt-2">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button onclick="closeDeleteTripModal()" class="btn-outline">Hủy</button>
                <button id="confirmDeleteTripBtn" class="btn-primary" style="background-color: #dc2626;">Xóa</button>
            </div>
        </div>
    </div>
    
    <script>
        // Khai báo biến để kiểm tra lỗi validation
        window.tripFormHasErrors = @json($errors->any());
    </script>
    
    <script src="{{ asset('js/admin.js') }}?v=2"></script>
</body>
</html>
