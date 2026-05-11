<?php

namespace App\Http\Controllers;

use App\Models\ChuyenXe;
use App\Models\Ghe;
use App\Models\TuyenXe;
use App\Models\Ve;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request, $matuyen)
    {
        $tuyen = TuyenXe::findOrFail($matuyen);

        $routeStatus = mb_strtolower(trim((string) ($tuyen->trangthai ?? '')), 'UTF-8');
        if ($routeStatus === 'ngừng hoạt động' || $routeStatus === 'ngung hoat dong') {
            return redirect()
                ->route('home')
                ->with('error', 'Tuyến xe này hiện đang ngừng hoạt động nên chưa thể đặt chỗ.');
        }

        $chuyenXes = ChuyenXe::with('xe')
            ->where('matuyen', $tuyen->matuyen)
            ->orderBy('ngaydi')
            ->orderBy('giodi')
            ->get();

        if ($chuyenXes->isEmpty()) {
            return redirect()
                ->route('home')
                ->with('error', 'Tuyen xe nay chua co chuyen khoi hanh de dat ve.');
        }

        $selectedTrip = null;
        $requestedTripId = (int) $request->query('machuyen', 0);
        if ($requestedTripId > 0) {
            $selectedTrip = $chuyenXes->firstWhere('machuyen', $requestedTripId);
        }

        if (!$selectedTrip) {
            $requestedDate = trim((string) $request->query('date', ''));
            if ($requestedDate !== '') {
                $selectedTrip = $chuyenXes->firstWhere('ngaydi', $requestedDate);
            }
        }

        $selectedTrip = $selectedTrip ?: $chuyenXes->first();

        $bookedSeatIds = Ve::where('machuyen', $selectedTrip->machuyen)
            ->pluck('maghe')
            ->map(fn($seatId) => (int) $seatId)
            ->all();

        $ghes = Ghe::where('maxe', $selectedTrip->maxe)->get();
        $ghes->each(function ($ghe) use ($bookedSeatIds) {
            $ghe->trangthai = in_array((int) $ghe->maghe, $bookedSeatIds, true)
                ? 'da_dat'
                : 'trong';
        });

        $ghes = $ghes->sortBy(function ($ghe) {
            preg_match('/(\d+)/', (string) $ghe->tenghe, $matches);
            return (int) ($matches[0] ?? 0);
        });

        return view('layouts.byticket', compact('tuyen', 'ghes', 'chuyenXes', 'selectedTrip'));
    }

    public function byticket(Request $request, $matuyen = null)
    {
        $matuyen = $matuyen ?? $request->query('matuyen');
        if (!$matuyen) {
            return redirect()->route('home');
        }

        return $this->index($request, $matuyen);
    }
}
