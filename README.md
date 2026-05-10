# Bao Cao MNM

## Gioi thieu du an

Day la du an web quan ly va dat ve xe, duoc xay dung theo mo hinh ung dung web voi khu vuc nguoi dung va khu vuc quan tri rieng. He thong ho tro tim kiem tuyen xe, chon chuyen di, chon ghe, thanh toan va xem hoa don. Ngoai ra, trang quan tri cho phep quan ly nguoi dung, xe, tuyen xe, chuyen xe va ve dat.

## Thanh vien thuc hien

| STT | Ho va ten | MSSV | Vai tro |
|---|---|---|---|
| 1 | Pham Dinh Luan | 23810310282 | Nhom truong |
| 2 | Tran Ngoc Thien | 23810310271 | Thanh vien |

## Chuc nang du an

### Chuc nang phia nguoi dung

- Hien thi danh sach tuyen xe va loc theo diem di, diem den, thoi gian di chuyen.
- Dat ve theo tung tuyen/chuyen xe.
- Chon ghe theo trang thai trong/da dat.
- Thanh toan va luu thong tin ve.
- Xem hoa don sau khi dat ve.

### Chuc nang phia quan tri

- Dang nhap he thong quan tri theo vai tro.
- Quan ly nguoi dung: them, sua, xoa, xem danh sach nguoi dung.
- Quan ly xe: them, sua, xoa, cap nhat thong tin xe.
- Quan ly tuyen xe: them, sua, xoa, phan cong xe cho tuyen.
- Quan ly chuyen xe: them, sua, xoa, cap nhat ngay di, gio di va gia ve.
- Quan ly ve: xem danh sach ve, cap nhat trang thai ve, xoa ve.
- Theo doi thong ke tong quan nhu so luong nguoi dung, xe, tuyen xe, ve va doanh thu.
- Xem bao cao trong khu vuc admin.

### Chuc nang he thong

- Phan quyen theo vai tro `admin`, `tai_xe`, `khach_hang`.
- Xu ly du lieu bang migration va model Eloquent.
- Ho tro moi truong phat trien va trien khai qua Docker.

## Bang mo ta chuc nang

| Ten chuc nang | Tac dung cua chuc nang |
|---|---|
| Dang ky | Cho phep nguoi dung tao tai khoan moi de su dung he thong dat ve. |
| Dang nhap | Xac thuc nguoi dung truoc khi dat ve, thanh toan va xem hoa don. |
| Tim kiem tuyen xe | Giup nguoi dung loc va tim tuyen xe phu hop theo diem di, diem den va thoi gian. |
| Xem danh sach chuyen xe | Hien thi cac chuyen xe thuoc tuyen da chon de nguoi dung lua chon. |
| Chon ghe | Cho phep nguoi dung chon ghe trong va tranh cac ghe da duoc dat truoc. |
| Thanh toan | Ghi nhan thong tin thanh toan va tao ve cho nguoi dung. |
| Xem hoa don | Hien thi thong tin ve da dat de nguoi dung theo doi lai giao dich. |
| Quan ly nguoi dung | Cho phep admin them, sua, xoa va xem danh sach nguoi dung trong he thong. |
| Quan ly xe | Cho phep admin quan ly thong tin xe nhu bien so, loai xe, so ghe va trang thai hoat dong. |
| Quan ly tuyen xe | Cho phep admin them, sua, xoa va cap nhat thong tin cac tuyen xe. |
| Quan ly chuyen xe | Cho phep admin tao va cap nhat lich trinh, ngay di, gio di va gia ve cua tung chuyen. |
| Quan ly ve | Cho phep admin theo doi danh sach ve, cap nhat trang thai ve va xoa ve khi can. |
| Bao cao va thong ke | Tong hop thong tin doanh thu, so luong ve, nguoi dung va cac chi so quan trong khac. |
| Phan quyen vai tro | Dam bao moi loai tai khoan chi duoc truy cap cac chuc nang phu hop voi vai tro cua minh. |

## Cong cu va cong nghe su dung

### Backend

- PHP `^8.2`
- Laravel `^12.0`
- Laravel Tinker
- Eloquent ORM
- Laravel Middleware cho phan quyen vai tro

### Frontend

- Blade Template Engine
- Vite
- Tailwind CSS 4
- PostCSS
- Autoprefixer
- JavaScript
- Axios

### Co so du lieu

- SQLite (mac dinh theo cau hinh Laravel)
- MySQL 8.0 (co cau hinh qua Docker Compose)

### Moi truong va van hanh

- Docker
- Docker Compose
- Nginx (co cau hinh trong thu muc `nginx/`)

### Kiem thu va ho tro phat trien

- PHPUnit
- Laravel Pint
- Laravel Pail
- Laravel Sail
- FakerPHP
- Mockery
- Concurrently

## Cau truc thu muc chinh

```text
.
|-- app/                # Controller, Model, Middleware
|-- bootstrap/          # Khoi tao ung dung
|-- config/             # Cau hinh he thong
|-- database/           # Migration, seeder, factory
|-- public/             # Tai nguyen public
|-- resources/          # View Blade, CSS, JS
|-- routes/             # Dinh nghia route
|-- tests/              # Kiem thu
|-- docker-compose.yml  # Cau hinh chay ung dung/db bang Docker
|-- Dockerfile          # Image cho ung dung Laravel
```

## Lenh phat trien co san

```bash
composer setup
```

Chay moi truong phat trien:

```bash
composer dev
```

Build frontend:

```bash
npm run build
```

Chay test:

```bash
composer test
```

## Ghi chu

- Du an hien dang to chuc theo huong he thong dat ve xe va quan ly van tai.
- Cac lenh Laravel va NPM duoc thuc hien tai thu muc root cua repo nay.
