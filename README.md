# Báo Cáo MNM

## Giới thiệu dự án

Đây là dự án web quản lý và đặt vé xe, được xây dựng theo mô hình ứng dụng web với khu vực người dùng và khu vực quản trị riêng. Hệ thống hỗ trợ tìm kiếm tuyến xe, chọn chuyến đi, chọn ghế, thanh toán và xem hóa đơn. Ngoài ra, trang quản trị cho phép quản lý người dùng, xe, tuyến xe, chuyến xe và vé đặt.

## Thành viên thực hiện

| STT | Họ và tên | MSSV | Vai trò |
|---|---|---|---|
| 1 | Phạm Đình Luân | 23810310282 | Nhóm trưởng |
| 2 | Trần Ngọc Thiện | 23810310271 | Thành viên |

## Chức năng dự án

### Chức năng phía người dùng

- Hiển thị danh sách tuyến xe và lọc theo điểm đi, điểm đến, thời gian di chuyển.
- Đặt vé theo từng tuyến/chuyến xe.
- Chọn ghế theo trạng thái trống/đã đặt.
- Thanh toán và lưu thông tin vé.
- Xem hóa đơn sau khi đặt vé.

### Chức năng phía quản trị

- Đăng nhập hệ thống quản trị theo vai trò.
- Quản lý người dùng: thêm, sửa, xóa, xem danh sách người dùng.
- Quản lý xe: thêm, sửa, xóa, cập nhật thông tin xe.
- Quản lý tuyến xe: thêm, sửa, xóa, phân công xe cho tuyến.
- Quản lý chuyến xe: thêm, sửa, xóa, cập nhật ngày đi, giờ đi và giá vé.
- Quản lý vé: xem danh sách vé, cập nhật trạng thái vé, xóa vé.
- Theo dõi thống kê tổng quan như số lượng người dùng, xe, tuyến xe, vé và doanh thu.
- Xem báo cáo trong khu vực admin.

### Chức năng hệ thống

- Phân quyền theo vai trò `admin`, `tai_xe`, `khach_hang`.
- Xử lý dữ liệu bằng migration và model Eloquent.
- Hỗ trợ môi trường phát triển và triển khai qua Docker.

## Bảng mô tả chức năng

| Tên chức năng | Tác dụng của chức năng |
|---|---|
| Đăng ký | Cho phép người dùng tạo tài khoản mới để sử dụng hệ thống đặt vé. |
| Đăng nhập | Xác thực người dùng trước khi đặt vé, thanh toán và xem hóa đơn. |
| Tìm kiếm tuyến xe | Giúp người dùng lọc và tìm tuyến xe phù hợp theo điểm đi, điểm đến và thời gian. |
| Xem danh sách chuyến xe | Hiển thị các chuyến xe thuộc tuyến đã chọn để người dùng lựa chọn. |
| Chọn ghế | Cho phép người dùng chọn ghế trống và tránh các ghế đã được đặt trước. |
| Thanh toán | Ghi nhận thông tin thanh toán và tạo vé cho người dùng. |
| Xem hóa đơn | Hiển thị thông tin vé đã đặt để người dùng theo dõi lại giao dịch. |
| Quản lý người dùng | Cho phép admin thêm, sửa, xóa và xem danh sách người dùng trong hệ thống. |
| Quản lý xe | Cho phép admin quản lý thông tin xe như biển số, loại xe, số ghế và trạng thái hoạt động. |
| Quản lý tuyến xe | Cho phép admin thêm, sửa, xóa và cập nhật thông tin các tuyến xe. |
| Quản lý chuyến xe | Cho phép admin tạo và cập nhật lịch trình, ngày đi, giờ đi và giá vé của từng chuyến. |
| Quản lý vé | Cho phép admin theo dõi danh sách vé, cập nhật trạng thái vé và xóa vé khi cần. |
| Báo cáo và thống kê | Tổng hợp thông tin doanh thu, số lượng vé, người dùng và các chỉ số quan trọng khác. |
| Phân quyền vai trò | Đảm bảo mỗi loại tài khoản chỉ được truy cập các chức năng phù hợp với vai trò của mình. |

## Công cụ và công nghệ sử dụng

### Backend

- PHP `^8.2`
- Laravel `^12.0`
- Laravel Tinker
- Eloquent ORM
- Laravel Middleware cho phân quyền vai trò

### Frontend

- Blade Template Engine
- Vite
- Tailwind CSS 4
- PostCSS
- Autoprefixer
- JavaScript
- Axios

### Cơ sở dữ liệu

- SQLite (mặc định theo cấu hình Laravel)
- MySQL 8.0 (có cấu hình qua Docker Compose)

### Môi trường và vận hành

- Docker
- Docker Compose
- Nginx (có cấu hình trong thư mục `nginx/`)

### Kiểm thử và hỗ trợ phát triển

- PHPUnit
- Laravel Pint
- Laravel Pail
- Laravel Sail
- FakerPHP
- Mockery
- Concurrently

## Cấu trúc thư mục chính

```text
.
|-- app/                # Controller, Model, Middleware
|-- bootstrap/          # Khởi tạo ứng dụng
|-- config/             # Cấu hình hệ thống
|-- database/           # Migration, seeder, factory
|-- public/             # Tài nguyên public
|-- resources/          # View Blade, CSS, JS
|-- routes/             # Định nghĩa route
|-- tests/              # Kiểm thử
|-- docker-compose.yml  # Cấu hình chạy ứng dụng/db bằng Docker
|-- Dockerfile          # Image cho ứng dụng Laravel
```

## Lệnh phát triển có sẵn

```bash
composer setup
```

Chạy môi trường phát triển:

```bash
composer dev
```

Build frontend:

```bash
npm run build
```

Chạy test:

```bash
composer test
```

## Ghi chú

- Dự án hiện đang tổ chức theo hướng hệ thống đặt vé xe và quản lý vận tải.
- Các lệnh Laravel và NPM được thực hiện tại thư mục root của repo này.
