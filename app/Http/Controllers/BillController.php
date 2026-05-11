<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use App\Models\Ve;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $phone = trim((string) $request->query('phone', session('userPhone', '')));
        $account = null;
        $bills = collect();
        $summary = [
            'total' => 0,
            'waiting' => 0,
            'done' => 0,
            'amount' => 0,
        ];

        if ($phone !== '') {
            $account = TaiKhoan::where('phone', $phone)->first();

            if ($account) {
                $bills = Ve::query()
                    ->from('ve')
                    ->leftJoin('vitrighe', 've.maghe', '=', 'vitrighe.maghe')
                    ->leftJoin('chuyenxe', 've.machuyen', '=', 'chuyenxe.machuyen')
                    ->leftJoin('tuyenxe', 'chuyenxe.matuyen', '=', 'tuyenxe.matuyen')
                    ->where('ve.mataikhoan', $account->id)
                    ->select([
                        've.mave',
                        've.ngaydat',
                        've.hinhthucthanhtoan',
                        've.tongsotien',
                        've.trangthai',
                        'vitrighe.tenghe',
                        'chuyenxe.ngaydi',
                        'chuyenxe.giodi',
                        'tuyenxe.diemdi',
                        'tuyenxe.diemden',
                    ])
                    ->orderByDesc('ve.ngaydat')
                    ->orderByDesc('ve.mave')
                    ->get()
                    ->map(function ($bill) {
                        $bookingDate = $bill->ngaydat ? Carbon::parse($bill->ngaydat) : null;
                        $travelDate = $bill->ngaydi ? Carbon::parse($bill->ngaydi) : null;
                        $isWaiting = $bill->trangthai === 'cho_don';

                        $bill->status_key = $isWaiting ? 'waiting' : 'done';
                        $bill->status_label = $isWaiting ? 'Cho don' : 'Da di';
                        $bill->date_label = $travelDate ? $travelDate->format('d/m/Y') : 'Chua cap nhat';
                        $bill->time_label = $bill->giodi ?: ($bookingDate ? $bookingDate->format('H:i') : '--:--');
                        $bill->route_label = trim(($bill->diemdi ?? '') . ' - ' . ($bill->diemden ?? ''), ' -');
                        $bill->route_label = $bill->route_label !== '' ? $bill->route_label : 'Chua cap nhat tuyen xe';
                        $bill->money_label = number_format((int) ($bill->tongsotien ?? 0), 0, ',', '.') . ' VND';
                        $bill->seat_label = $bill->tenghe ?: 'Chua cap nhat';

                        return $bill;
                    });

                $summary = [
                    'total' => $bills->count(),
                    'waiting' => $bills->where('status_key', 'waiting')->count(),
                    'done' => $bills->where('status_key', 'done')->count(),
                    'amount' => (int) $bills->sum('tongsotien'),
                ];
            }
        }

        return view('layouts.bill', compact('phone', 'account', 'bills', 'summary'));
    }
}
