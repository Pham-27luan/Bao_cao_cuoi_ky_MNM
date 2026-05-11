<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toan ve xe</title>
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}?v=3">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    @include('pages.header')

    <section class="payment-page">
        <div class="payment-shell">
            @if ($errors->any())
                <div class="payment-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="payment-intro">
                <span class="payment-kicker">Thanh toan ve xe</span>
                <h1>Hoan tat thong tin thanh toan cho chuyen di cua ban</h1>
                <p>
                    Don ve duoc gan truc tiep voi chuyen xe da chon, vi vay ghe trong va ghe da dat se duoc tinh dung theo tung chuyen.
                </p>
            </div>

            <div class="payment-hero">
                <div class="payment-card">
                    <div class="section-head">
                        <h2>Thong tin nguoi thanh toan</h2>
                        <p>Ban co the kiem tra lai thong tin truoc khi xac nhan don ve.</p>
                    </div>

                    <form>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="fullName">Ho ten</label>
                                <input
                                    type="text"
                                    id="fullName"
                                    name="fullName"
                                    placeholder="Nhap ho ten cua ban"
                                    value="{{ session('userFullName', session('userPhone', '')) }}"
                                >
                            </div>

                            <div class="form-group">
                                <label for="phoneNumber">So dien thoai</label>
                                <input
                                    type="tel"
                                    id="phoneNumber"
                                    name="phoneNumber"
                                    placeholder="Nhap so dien thoai"
                                    value="{{ session('userPhone', '') }}"
                                >
                            </div>

                            <div class="form-group">
                                <label for="seatNumber">Ghe ngoi</label>
                                <input type="text" id="seatNumber" name="seatNumber" value="" readonly>
                            </div>

                            <div class="form-group">
                                <label for="ticketCode">Ma ve</label>
                                <input type="text" id="ticketCode" name="ticketCode" value="" readonly>
                            </div>

                            <div class="form-group">
                                <label for="travelDate">Ngay di</label>
                                <input type="text" id="travelDate" name="travelDate" value="" readonly>
                            </div>

                            <div class="form-group">
                                <label for="destination">Diem den</label>
                                <input type="text" id="destination" name="destination" value="" readonly>
                            </div>

                            <div class="form-group">
                                <label for="price">Gia tien</label>
                                <input type="text" id="price" name="price" value="" readonly>
                            </div>

                            <div class="form-group">
                                <label for="busPlate">Bien so xe</label>
                                <input type="text" id="busPlate" name="busPlate" value="" readonly>
                            </div>
                        </div>
                    </form>
                </div>

                <aside class="ticket-summary">
                    <div class="summary-top">
                        <small>Thong tin don ve</small>
                        <h3 id="tripTitle">Chuyen xe</h3>
                        <div class="summary-route" id="tripMeta">
                            Khoi hanh theo lich da chon
                        </div>
                    </div>

                    <ul class="summary-list">
                        <li>
                            <span>Ma ve</span>
                            <strong id="summaryTicketCode"></strong>
                        </li>
                        <li>
                            <span>Ghe ngoi</span>
                            <strong id="summarySeatNumber"></strong>
                        </li>
                        <li>
                            <span>Ngay di</span>
                            <strong id="summaryTravelDate"></strong>
                        </li>
                        <li>
                            <span>Diem den</span>
                            <strong id="summaryDestination"></strong>
                        </li>
                        <li>
                            <span>Gia ve</span>
                            <strong id="summaryPrice"></strong>
                        </li>
                    </ul>

                    <div class="payment-methods">
                        <h3>Hinh thuc thanh toan</h3>

                        <div class="method-grid">
                            <div class="method-option">
                                <input type="radio" id="cash" name="payment_method" value="tien_mat" form="paymentConfirmForm" checked>
                                <label for="cash">
                                    <span class="method-title">Tien mat</span>
                                    <span class="method-desc">Thanh toan truc tiep tai quay hoac khi nhan ve.</span>
                                </label>
                            </div>

                            <div class="method-option">
                                <input type="radio" id="banking" name="payment_method" value="chuyen_khoan" form="paymentConfirmForm">
                                <label for="banking">
                                    <span class="method-title">Chuyen khoan</span>
                                    <span class="method-desc">Thanh toan qua ngan hang de xac nhan nhanh hon.</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="summary-total">
                        <span>Tong thanh toan</span>
                        <strong id="totalAmount"></strong>
                    </div>

                    <form id="paymentConfirmForm" method="POST" action="{{ route('payment.confirm', $payment['machuyen'] ?? $chuyenXe->machuyen) }}">
                        @csrf
                        <input type="hidden" name="seat_ids" value="{{ implode(',', $payment['seatIds'] ?? []) }}">
                        <button type="submit" class="pay-button">Xac nhan thanh toan</button>
                    </form>
                    <p class="secure-note">Thong tin chuyen va ghe duoc doi chieu theo machuyen truoc khi luu ve.</p>
                </aside>
            </div>
        </div>
    </section>

    <script>
        const data = @json($payment ?? session('payment'));
        const fromPlace = data?.from || '';
        const toPlace = data?.to || '';
        const travelDate = data?.date || '';
        const departureTime = data?.departureTime || '';
        const seatNumber = data?.seats || '';
        const ticketCode = data?.ticketCode || '';
        const totalPrice = data?.total || '';
        const busPlate = data?.busPlate || '';

        document.title = `Thanh toan ve xe ${fromPlace} - ${toPlace}`;
        document.getElementById('seatNumber').value = seatNumber;
        document.getElementById('ticketCode').value = ticketCode;
        document.getElementById('travelDate').value = departureTime ? `${travelDate} ${departureTime}` : travelDate;
        document.getElementById('destination').value = toPlace;
        document.getElementById('price').value = totalPrice;
        document.getElementById('busPlate').value = busPlate;
        document.getElementById('tripTitle').textContent = `Chuyen xe ${fromPlace} - ${toPlace}`;
        document.getElementById('tripMeta').textContent = `Tuyen: ${fromPlace} -> ${toPlace} | Ngay di ${travelDate}${departureTime ? ` - ${departureTime}` : ''}`;
        document.getElementById('summaryTicketCode').textContent = ticketCode;
        document.getElementById('summarySeatNumber').textContent = seatNumber;
        document.getElementById('summaryTravelDate').textContent = departureTime ? `${travelDate} ${departureTime}` : travelDate;
        document.getElementById('summaryDestination').textContent = toPlace;
        document.getElementById('summaryPrice').textContent = totalPrice;
        document.getElementById('totalAmount').textContent = totalPrice;
    </script>

    @include('pages.footer')
</body>
</html>
