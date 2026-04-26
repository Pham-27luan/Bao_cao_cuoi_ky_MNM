<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Hóa đơn vé xe</title>
    <link rel="stylesheet" href="{{ asset('css/bill.css') }}?v=3">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
</head>
<body>
    @include('pages.header')

    <section class="bill-page">
        <div class="bill-page__shell">
            @if ($phone !== '' && !$account)
                <div class="bill-alert">
                    Không tìm thấy tài khoản nào với số điện thoại {{ $phone }}.
                </div>
            @endif

            <div class="bill-summary-grid">
                <div class="bill-summary-card">
                    <span>Tổng hóa đơn</span>
                    <strong>{{ $summary['total'] }}</strong>
                </div>
                <div class="bill-summary-card">
                    <span>Chờ đón</span>
                    <strong>{{ $summary['waiting'] }}</strong>
                </div>
                <div class="bill-summary-card">
                    <span>Đã đi</span>
                    <strong>{{ $summary['done'] }}</strong>
                </div>
                <div class="bill-summary-card">
                    <span>Tổng chi phí</span>
                    <strong>{{ number_format($summary['amount'], 0, ',', '.') }} VND</strong>
                </div>
            </div>

            <div class="bill-history-card">
                <div class="bill-toolbar">
                    <div>
                        <h2>Danh sách hóa đơn</h2>
                        <p>
                            @if ($account)
                                Đang hiển thị hóa đơn của {{ $account->hoten ?: $account->phone }}.
                            @else
                                Hóa đơn được lấy theo số điện thoại tài khoản đang đăng nhập.
                            @endif
                        </p>
                    </div>

                    <div class="bill-filters" aria-label="Lọc hóa đơn theo trạng thái">
                        <button class="bill-filter-btn is-active" type="button" data-filter="all">Tất cả</button>
                        <button class="bill-filter-btn" type="button" data-filter="waiting">Chờ đón</button>
                        <button class="bill-filter-btn" type="button" data-filter="done">Đã đi</button>
                    </div>
                </div>

                <div class="bill-table-wrapper">
                    <table class="bill-table">
                        <thead>
                            <tr>
                                <th>Mã hóa đơn</th>
                                <th>Tuyến xe</th>
                                <th>Ngày đi</th>
                                <th>Giờ đi</th>
                                <th>Trạng thái</th>
                                <th>Thanh toán</th>
                                <th>Số tiền</th>
                                <th>Số ghế</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bills as $bill)
                                <tr class="bill-row" data-status="{{ $bill->status_key }}">
                                    <td data-label="Mã hóa đơn">
                                        <span class="bill-code">HD-{{ $bill->mave }}</span>
                                        <span class="bill-caption">Hóa đơn đã thanh toán</span>
                                    </td>
                                    <td data-label="Tuyến xe"><span class="bill-route">{{ $bill->route_label }}</span></td>
                                    <td data-label="Ngày đi">{{ $bill->date_label }}</td>
                                    <td data-label="Giờ đi">{{ $bill->time_label }}</td>
                                    <td data-label="Trạng thái">
                                        <span class="status-badge status-badge--{{ $bill->status_key }}">{{ $bill->status_label }}</span>
                                    </td>
                                    <td data-label="Thanh toán">{{ $bill->hinhthucthanhtoan === 'chuyen_khoan' ? 'Chuyển khoản' : 'Tiền mặt' }}</td>
                                    <td data-label="Số tiền"><span class="bill-money">{{ $bill->money_label }}</span></td>
                                    <td data-label="Số ghế">
                                        <div class="seat-tags"><span class="seat-tag">{{ $bill->seat_label }}</span></div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="bill-empty" colspan="8">
                                        @if ($phone === '')
                                            Chưa có số điện thoại tài khoản để tra cứu hóa đơn.
                                        @elseif ($account)
                                            Tài khoản này chưa có hóa đơn nào.
                                        @else
                                            Chưa có dữ liệu hóa đơn phù hợp.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.querySelectorAll('.bill-filter-btn').forEach((button) => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter;

                document.querySelectorAll('.bill-filter-btn').forEach((item) => {
                    item.classList.toggle('is-active', item === button);
                });

                document.querySelectorAll('.bill-row').forEach((row) => {
                    row.hidden = filter !== 'all' && row.dataset.status !== filter;
                });
            });
        });
    </script>

    @include('pages.footer')
</body>
</html>
