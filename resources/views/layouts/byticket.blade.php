<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>MY BUS - Đặt vé</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/byticket.css') }}?v=4">
</head>
<body>
    @include('pages.header')

    <div class="page-wrapper">
        <div class="booking-card">
            <div class="route-info-banner">
                <div class="route-title">
                    <i class="fas fa-bus-alt"></i>
                    <span>{{ $tuyen->tentuyen ?? 'Tuyến xe' }}</span>
                </div>
                <div class="route-details">
                    <span><i class="fas fa-map-marker-alt"></i> Điểm đi: <strong>{{ $tuyen->diemdi ?? '--' }}</strong></span>
                    <span><i class="fas fa-flag-checkered"></i> Điểm đến: <strong>{{ $tuyen->diemden ?? '--' }}</strong></span>
                </div>
            </div>

            <div class="date-selector">
                <span><i class="far fa-calendar-alt"></i> Chọn ngày khởi hành:</span>
                <input type="date" id="travelDate" class="date-input" value="{{ $selectedTrip->ngaydi }}">
            </div>

            <div class="result-section">
                <div>
                    <div class="trip-detail">
                        <div class="trip-time">
                            {{ $selectedTrip->giodi ?? '--:--' }}
                            <i class="fas fa-arrow-right"></i>
                            --:--
                        </div>

                        <div class="route-highlight">
                            <div class="route-info-stack">
                                <strong>📅 Ngày khởi hành: <span id="displayDateHeader">{{ \Carbon\Carbon::parse($selectedTrip->ngaydi)->format('d/m/Y') }}</span></strong>
                                <span>🕒 Thời gian: {{ $tuyen->thoigiandukien ?? '--' }}</span>
                                <span>📍 Quãng đường: {{ $tuyen->khoangcach ?? '--' }} km</span>
                            </div>
                        </div>

                        <div class="seat-area">
                            <h4><i class="fas fa-couch"></i> Sơ đồ ghế (chọn ghế trống)</h4>
                            <div class="seat-grid" id="seatContainer">
                                @foreach($ghes as $ghe)
                                    <div class="seat {{ $ghe->trangthai === 'da_dat' ? 'booked' : 'available' }}" data-seat-id="{{ $ghe->maghe }}">
                                        {{ $ghe->tenghe }}
                                    </div>
                                @endforeach
                            </div>
                            <div class="legend">
                                <span><span class="legend-dot" style="background: #eff6ff;"></span> Trống</span>
                                <span><span class="legend-dot" style="background: #f39c12;"></span> Đang chọn</span>
                                <span><span class="legend-dot" style="background: rgba(220, 53, 69, 0.65);"></span> Đã đặt</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <h3><i class="fas fa-receipt"></i> Thông tin đặt vé</h3>
                    <div class="info-row"><span>Tuyến:</span><span><strong>{{ $tuyen->diemdi ?? '--' }} → {{ $tuyen->diemden ?? '--' }}</strong></span></div>
                    <div class="info-row"><span>Ngày đi:</span><span id="displayDate">{{ \Carbon\Carbon::parse($selectedTrip->ngaydi)->format('d/m/Y') }}</span></div>
                    <div class="info-row"><span>Ghế đã chọn:</span><span id="selectedSeatsLabel">Chưa có ghế</span></div>
                    <div class="info-row"><span>Biển số xe:</span><span>{{ $selectedTrip->xe->biensoxe ?? $tuyen->bienSoXe ?? '--' }}</span></div>
                    <div class="info-row"><span>Đơn giá / vé:</span><span><strong>{{ number_format((int) ($selectedTrip->giave ?? $tuyen->giatien ?? 0), 0, ',', '.') }} VND</strong></span></div>
                    <div class="total-price" id="totalPriceDisplay">0 VND</div>
                    <button class="btn-book-final" id="bookNowBtn"><i class="fas fa-check-circle"></i> ĐẶT VÉ NGAY</button>
                </div>
            </div>
        </div>
    </div>

    @include('pages.footer')

    <script>
        let selectedSeats = [];
        let selectedSeatIds = [];
        let pricePerTicket = {{ (int) ($selectedTrip->giave ?? $tuyen->giatien ?? 0) }};
        const routeId = {{ (int) $tuyen->matuyen }};
        const selectedTripId = {{ (int) $selectedTrip->machuyen }};
        const availableDates = @json($chuyenXes->pluck('ngaydi')->values());

        function initSeatEvents() {
            document.querySelectorAll('.seat:not(.booked)').forEach(seat => {
                seat.style.cursor = 'pointer';
                seat.addEventListener('click', function() {
                    const seatName = this.innerText.trim();
                    const seatId = this.dataset.seatId;

                    if (this.classList.contains('selected')) {
                        this.classList.remove('selected');
                        selectedSeats = selectedSeats.filter(s => s !== seatName);
                        selectedSeatIds = selectedSeatIds.filter(id => id !== seatId);
                    } else {
                        this.classList.add('selected');
                        selectedSeats.push(seatName);
                        selectedSeatIds.push(seatId);
                    }
                    updateSummary();
                });
            });
        }

        function updateSummary() {
            const selectedCount = selectedSeats.length;
            const total = selectedCount * pricePerTicket;

            document.getElementById('selectedSeatsLabel').innerText =
                selectedCount === 0 ? 'Chưa có ghế' : ('Ghế ' + selectedSeats.join(', Ghế '));

            document.getElementById('totalPriceDisplay').innerText = total.toLocaleString('vi-VN') + ' VND';
        }

        const dateInput = document.getElementById('travelDate');
        const today = new Date().toISOString().split('T')[0];
        if (dateInput) {
            dateInput.setAttribute('min', today);
            dateInput.addEventListener('change', function() {
                if (!availableDates.includes(this.value)) {
                    alert('Ngày này chưa có chuyến khởi hành cho tuyến đã chọn.');
                    this.value = '{{ $selectedTrip->ngaydi }}';
                    return;
                }

                window.location.href = "{{ url('/byticket') }}/" + routeId + "?date=" + encodeURIComponent(this.value);
            });
        }

        document.getElementById('bookNowBtn').addEventListener('click', function() {
            if (selectedSeats.length === 0) {
                alert('Vui lòng chọn ghế trước khi đặt vé!');
                return;
            }

            const seats = encodeURIComponent(selectedSeats.join(','));
            const seatIds = encodeURIComponent(selectedSeatIds.join(','));

            window.location.href = "{{ url('/payment') }}/" + selectedTripId + "?seats=" + seats + "&seat_ids=" + seatIds;
        });

        document.addEventListener('DOMContentLoaded', function() {
            initSeatEvents();
            updateSummary();
        });
    </script>
</body>
</html>
