<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <title>MY BUS - Hành trình tiếp theo của bạn</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v=4">
</head>
<body>

@include('pages.header')

@if(session('success'))
    <div class="booking-success" id="bookingSuccess">
        <div class="booking-success__icon"><i class="fas fa-check"></i></div>
        <div>
            <strong>Đặt vé thành công!</strong>
            <p>{{ session('success') }}</p>
        </div>
        <button type="button" aria-label="Đóng thông báo" onclick="document.getElementById('bookingSuccess').remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="booking-success booking-success--error" id="bookingError">
        <div class="booking-success__icon"><i class="fas fa-ban"></i></div>
        <div>
            <strong>Chưa thể đặt vé</strong>
            <p>{{ session('error') }}</p>
        </div>
        <button type="button" aria-label="Đóng thông báo" onclick="document.getElementById('bookingError').remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

<div class="main-wrapper">
    <div class="hero">
        <div class="hero-content">
            <h1><i class="fas fa-bus-alt"></i> HÀNH TRÌNH TIẾP THEO CỦA BẠN</h1>
            <p>Đặt vé trực tuyến nhanh chóng, an toàn và tiện lợi nhất cùng MY BUS.</p>

            <form method="GET" action="{{ url('/') }}" class="search-banner">
                <select name="from">
                    <option value="">Chọn điểm đi</option>
                    @foreach($filterOptions->pluck('diemdi')->filter()->unique()->values() as $diemdi)
                        <option value="{{ $diemdi }}" {{ request('from') == $diemdi ? 'selected' : '' }}>
                            {{ $diemdi }}
                        </option>
                    @endforeach
                </select>

                <select name="to">
                    <option value="">Chọn điểm đến</option>
                    @foreach($filterOptions->pluck('diemden')->filter()->unique()->values() as $diemden)
                        <option value="{{ $diemden }}" {{ request('to') == $diemden ? 'selected' : '' }}>
                            {{ $diemden }}
                        </option>
                    @endforeach
                </select>

                <select name="duration">
                    <option value="">Thời gian dự kiến</option>
                    @foreach($filterOptions->pluck('thoigiandukien')->filter()->unique()->values() as $thoigiandukien)
                        <option value="{{ $thoigiandukien }}" {{ request('duration') == $thoigiandukien ? 'selected' : '' }}>
                            {{ $thoigiandukien }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"><i class="fas fa-search"></i> Tìm chuyến</button>
            </form>

            <div class="stats-row">
                <div class="stat-card"><div class="stat-number">4.9/5</div><div class="stat-label">Đánh giá từ khách</div></div>
                <div class="stat-card"><div class="stat-number">120+</div><div class="stat-label">Chuyến xe mỗi ngày</div></div>
                <div class="stat-card"><div class="stat-number">98%</div><div class="stat-label">Đúng giờ</div></div>
            </div>
        </div>
    </div>

    <div class="main-container" id="routes">
        <div class="page-title">
            <i class="fas fa-map-marked-alt"></i>
            DANH SÁCH TUYẾN XE TRỰC TUYẾN
            <i class="fas fa-ticket-alt"></i>
        </div>

        <div class="routes-grid">
            @forelse($tuyenXes as $tuyen)
                @php
                    $routeStatus = mb_strtolower(trim((string) ($tuyen->trangthai ?? '')), 'UTF-8');
                    $isInactive = in_array($routeStatus, ['ngừng hoạt động', 'ngung hoat dong'], true);
                @endphp
                <div class="route-card {{ $isInactive ? 'route-card--inactive' : '' }}">
                    <div class="route-header">
                        <div class="route-header__title">
                            <i class="fas fa-route"></i>
                            <span>{{ $tuyen->tentuyen }}</span>
                        </div>
                        <span class="route-status {{ $isInactive ? 'route-status--inactive' : 'route-status--active' }}">
                            {{ $isInactive ? 'Ngừng hoạt động' : 'Đang hoạt động' }}
                        </span>
                    </div>
                    <div class="route-body">
                        <div class="route-points">
                            <span class="from-to">{{ $tuyen->diemdi }}</span>
                            <span class="arrow"><i class="fas fa-long-arrow-alt-right"></i></span>
                            <span class="from-to">{{ $tuyen->diemden }}</span>
                        </div>

                        <div class="route-info">
                            <div class="info-item">
                                <div class="info-label"><i class="far fa-hourglass"></i> THỜI GIAN DỰ KIẾN</div>
                                <div class="info-value">{{ $tuyen->thoigiandukien ?: 'Chưa cập nhật' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><i class="fas fa-road"></i> KHOẢNG CÁCH</div>
                                <div class="info-value">{{ $tuyen->khoangcach ?? '--' }} KM</div>
                            </div>
                        </div>

                        <div class="price-section">
                            <div class="price-label">GIÁ VÉ TỪ</div>
                            <div class="price-value">
                                {{ number_format($tuyen->giatien ?? 0, 0, ',', '.') }}
                                <span class="price-unit">VND</span>
                            </div>
                        </div>

                        @if($isInactive)
                            <button class="btn-book btn-book--disabled" type="button" disabled aria-disabled="true">
                                NGỪNG HOẠT ĐỘNG
                            </button>
                        @else
                            <button class="btn-book" onclick="bookRoute({{ $tuyen->matuyen }})">
                                ĐẶT NGAY
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="route-card" style="grid-column: 1 / -1; text-align: center;">
                    <div class="route-header">
                        <i class="fas fa-exclamation-triangle"></i> Không tìm thấy tuyến xe
                    </div>
                    <div class="route-body">
                        <p>Không có tuyến xe phù hợp với lựa chọn hiện tại.</p>
                        <p>Vui lòng chọn lại điểm đi, điểm đến hoặc thời gian dự kiến khác.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <script>
            function bookRoute(routeId) {
                window.location.href = "{{ url('/byticket') }}/" + routeId;
            }
        </script>

        <div class="promo-block">
            <div class="testimonial-text">
                <i class="fas fa-quote-left" style="color:#f39c12; margin-right: 6px;"></i>
                "MY BUS thật sự chuyên nghiệp, ghế ngồi thoải mái, đúng giờ. Rất hài lòng!"
                <div style="margin-top: 8px; font-weight: 600;">- Chị Lan Anh, Hà Nam</div>
            </div>
            <div class="promo-badge"><i class="fas fa-gift"></i> MÃ GIẢM 20K: MYBUS20</div>
            <div class="promo-badge" style="background:#b64926;"><i class="fas fa-shield-alt"></i> ĐẶT VÉ LINH HOẠT</div>
        </div>
    </div>
</div>

@include('pages.footer')

</body>
</html>
