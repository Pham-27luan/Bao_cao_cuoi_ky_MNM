<?php

namespace App\Http\Controllers;

use App\Models\Ghe;
use App\Models\TuyenXe;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index($matuyen)
    {
        $tuyen = TuyenXe::findOrFail($matuyen);

        $routeStatus = mb_strtolower(trim((string) ($tuyen->trangthai ?? '')), 'UTF-8');
        if ($routeStatus === 'ngừng hoạt động' || $routeStatus === 'ngung hoat dong') {
            return redirect()
                ->route('home')
                ->with('error', 'Tuyến xe này hiện đang ngừng hoạt động nên chưa thể đặt chỗ.');
        }

        $ghes = Ghe::where('maxe', $tuyen->maxe)->get();
        $ghes = $ghes->sortBy(function ($ghe) {
            preg_match('/(\d+)/', (string) $ghe->tenghe, $matches);
            return (int) ($matches[0] ?? 0);
        });

        return view('layouts.byticket', compact('tuyen', 'ghes'));
    }

    public function byticket(Request $request, $matuyen = null)
    {
        $matuyen = $matuyen ?? $request->query('matuyen');
        if (!$matuyen) {
            return redirect()->route('home');
        }

        return $this->index($matuyen);
    }
}
