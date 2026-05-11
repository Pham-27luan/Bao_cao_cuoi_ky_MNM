<?php

namespace App\Http\Controllers;

use App\Models\ChuyenXe;
use App\Models\Ghe;
use App\Models\Ve;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function show(Request $request, $machuyen = null)
    {
        $machuyen = $machuyen ?? $request->query('machuyen');
        if (!$machuyen) {
            return redirect()->route('home');
        }

        $chuyenXe = ChuyenXe::with(['tuyenXe', 'xe'])->findOrFail($machuyen);
        $tuyen = $chuyenXe->tuyenXe;

        if (!$tuyen) {
            return redirect()->route('home')->with('error', 'Khong tim thay thong tin tuyen cho chuyen xe nay.');
        }

        $seatsRaw = (string) $request->query('seats', '');
        $seats = array_values(array_filter(array_map('trim', explode(',', $seatsRaw))));
        $seatLabel = count($seats) ? implode(', ', $seats) : '';

        $seatIdsRaw = (string) $request->query('seat_ids', '');
        $seatIds = array_values(array_filter(array_map('intval', explode(',', $seatIdsRaw))));

        if (empty($seatIds) && !empty($seats)) {
            $seatIds = Ghe::where('maxe', $chuyenXe->maxe)
                ->whereIn('tenghe', $seats)
                ->pluck('maghe')
                ->map(fn($id) => (int) $id)
                ->all();
        }

        $travelDate = (string) ($chuyenXe->ngaydi ?? '');
        $travelDateLabel = $travelDate ? date('d/m/Y', strtotime($travelDate)) : '';

        $count = max(1, count($seats));
        $unitPrice = (int) ($chuyenXe->giave ?? $tuyen->giatien ?? 0);
        $total = $unitPrice * $count;
        $ticketCode = ((int) Ve::max('mave')) + 1;

        $payment = [
            'from' => $tuyen->diemdi ?? '',
            'to' => $tuyen->diemden ?? '',
            'date' => $travelDateLabel,
            'rawDate' => $travelDate,
            'departureTime' => (string) ($chuyenXe->giodi ?? ''),
            'seats' => $seatLabel,
            'seatIds' => $seatIds,
            'machuyen' => (int) $chuyenXe->machuyen,
            'matuyen' => (int) $tuyen->matuyen,
            'ticketCode' => $ticketCode,
            'unitPrice' => number_format($unitPrice, 0, ',', '.') . ' VND',
            'total' => number_format($total, 0, ',', '.') . ' VND',
            'busPlate' => $chuyenXe->xe->biensoxe ?? ($tuyen->bienSoXe ?? ''),
        ];

        session(['payment' => $payment]);

        return view('layouts.payment', compact('tuyen', 'chuyenXe', 'payment'));
    }

    public function confirm(Request $request, $machuyen)
    {
        $validated = $request->validate([
            'seat_ids' => 'required|string',
            'payment_method' => 'required|in:tien_mat,chuyen_khoan',
        ]);

        $accountId = session('userId');
        if (!$accountId) {
            return redirect()->route('login')->withErrors(['Vui long dang nhap truoc khi thanh toan.']);
        }

        $chuyenXe = ChuyenXe::with('tuyenXe')->findOrFail($machuyen);
        $seatIds = array_values(array_unique(array_filter(array_map('intval', explode(',', $validated['seat_ids'])))));

        if (empty($seatIds)) {
            return back()->withErrors(['Vui long chon ghe truoc khi thanh toan.']);
        }

        try {
            DB::beginTransaction();

            $ghes = Ghe::where('maxe', $chuyenXe->maxe)
                ->whereIn('maghe', $seatIds)
                ->get();

            if ($ghes->count() !== count($seatIds)) {
                throw new \RuntimeException('Ghe khong hop le cho chuyen xe nay.');
            }

            $bookedSeatIds = Ve::where('machuyen', $chuyenXe->machuyen)
                ->whereIn('maghe', $seatIds)
                ->pluck('maghe')
                ->all();

            if (!empty($bookedSeatIds)) {
                throw new \RuntimeException('Mot so ghe da duoc dat. Vui long chon ghe khac.');
            }

            $ngayDat = now()->format('Y-m-d H:i:s');
            $ticketPrice = (int) ($chuyenXe->giave ?? optional($chuyenXe->tuyenXe)->giatien ?? 0);

            foreach ($ghes as $ghe) {
                $this->createTicket(
                    $chuyenXe,
                    $ghe,
                    (int) $accountId,
                    $ngayDat,
                    $ticketPrice,
                    $validated['payment_method']
                );
            }

            DB::commit();
            session()->forget('payment');

            return redirect()
                ->route('home')
                ->with('success', 'Ve cua ban da duoc luu. Ban co the xem lai trong muc Hoa don.');
        } catch (\RuntimeException $e) {
            DB::rollBack();
            return back()->withErrors([$e->getMessage()]);
        } catch (QueryException $e) {
            DB::rollBack();

            if (str_contains(mb_strtolower($e->getMessage()), 'unique')) {
                return back()->withErrors(['Ghe ban chon vua duoc nguoi khac dat. Vui long chon ghe khac.']);
            }

            Log::error('Payment query error: ' . $e->getMessage());
            return back()->withErrors(['Co loi xay ra khi luu ve, vui long thu lai sau!']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment error: ' . $e->getMessage());
            return back()->withErrors(['Co loi xay ra, vui long thu lai sau!']);
        }
    }

    private function createTicket(
        ChuyenXe $chuyenXe,
        Ghe $ghe,
        int $accountId,
        string $ngayDat,
        int $total,
        string $paymentMethod
    ): void {
        Ve::create([
            'machuyen' => $chuyenXe->machuyen,
            'maghe' => $ghe->maghe,
            'mataikhoan' => $accountId,
            'ngaydat' => $ngayDat,
            'hinhthucthanhtoan' => $paymentMethod,
            'tongsotien' => $total,
            'trangthai' => 'cho_don',
        ]);
    }
}
