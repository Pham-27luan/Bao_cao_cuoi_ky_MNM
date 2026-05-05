<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('taikhoan')->upsert([
            [
                'id' => 1,
                'phone' => '0900000001',
                'password' => 'admin123',
                'role' => 'admin',
                'email' => 'admin@mybus.local',
                'hoten' => 'Quan tri vien',
            ],
            [
                'id' => 2,
                'phone' => '0900000002',
                'password' => '123456',
                'role' => 'khach_hang',
                'email' => 'khachhang@mybus.local',
                'hoten' => 'Nguyen Van A',
            ],
        ], ['id'], ['phone', 'password', 'role', 'email', 'hoten']);

        DB::table('xe')->upsert([
            [
                'maxe' => 1,
                'biensoxe' => '29B-12345',
                'loaixe' => 'Ghe ngoi',
                'soghe' => 16,
                'nhaxe' => 'MY BUS',
                'trangthai' => 'Dang hoat dong',
            ],
            [
                'maxe' => 2,
                'biensoxe' => '30F-67890',
                'loaixe' => 'Limousine',
                'soghe' => 9,
                'nhaxe' => 'MY BUS',
                'trangthai' => 'Dang hoat dong',
            ],
        ], ['maxe'], ['biensoxe', 'loaixe', 'soghe', 'nhaxe', 'trangthai']);

        DB::table('tuyenxe')->upsert([
            [
                'matuyen' => 1,
                'tentuyen' => 'Ha Noi - Ninh Binh',
                'diemdi' => 'Ha Noi',
                'diemden' => 'Ninh Binh',
                'thoigiandukien' => '2 gio 30 phut',
                'khoangcach' => 95,
                'giatien' => 150000,
                'trangthai' => 'Dang hoat dong',
                'maxe' => 1,
            ],
            [
                'matuyen' => 2,
                'tentuyen' => 'Ha Noi - Hai Phong',
                'diemdi' => 'Ha Noi',
                'diemden' => 'Hai Phong',
                'thoigiandukien' => '2 gio',
                'khoangcach' => 120,
                'giatien' => 180000,
                'trangthai' => 'Dang hoat dong',
                'maxe' => 2,
            ],
        ], ['matuyen'], ['tentuyen', 'diemdi', 'diemden', 'thoigiandukien', 'khoangcach', 'giatien', 'trangthai', 'maxe']);

        $seats = [];
        for ($i = 1; $i <= 16; $i++) {
            $seats[] = [
                'maghe' => $i,
                'tenghe' => 'A' . $i,
                'trangthai' => $i <= 2 ? 'da_dat' : 'trong',
                'maxe' => 1,
            ];
        }

        for ($i = 1; $i <= 9; $i++) {
            $seats[] = [
                'maghe' => 100 + $i,
                'tenghe' => 'B' . $i,
                'trangthai' => 'trong',
                'maxe' => 2,
            ];
        }

        DB::table('vitrighe')->upsert($seats, ['maghe'], ['tenghe', 'trangthai', 'maxe']);

        DB::table('chuyenxe')->upsert([
            [
                'machuyen' => 1,
                'matuyen' => 1,
                'maxe' => 1,
                'ngaydi' => now()->toDateString(),
                'giodi' => '08:00',
                'giave' => 150000,
                'ghe_trong' => 14,
            ],
            [
                'machuyen' => 2,
                'matuyen' => 2,
                'maxe' => 2,
                'ngaydi' => now()->addDay()->toDateString(),
                'giodi' => '09:30',
                'giave' => 180000,
                'ghe_trong' => 9,
            ],
        ], ['machuyen'], ['matuyen', 'maxe', 'ngaydi', 'giodi', 'giave', 'ghe_trong']);

        DB::table('ve')->upsert([
            [
                'mave' => 1,
                'maghe' => 1,
                'mataikhoan' => 2,
                'ngaydat' => now()->subDay()->format('Y-m-d H:i:s'),
                'hinhthucthanhtoan' => 'tien_mat',
                'tongsotien' => 150000,
                'trangthai' => 'cho_don',
            ],
            [
                'mave' => 2,
                'maghe' => 2,
                'mataikhoan' => 2,
                'ngaydat' => now()->format('Y-m-d H:i:s'),
                'hinhthucthanhtoan' => 'chuyen_khoan',
                'tongsotien' => 150000,
                'trangthai' => 'da_di',
            ],
        ], ['mave'], ['maghe', 'mataikhoan', 'ngaydat', 'hinhthucthanhtoan', 'tongsotien', 'trangthai']);
    }
}
