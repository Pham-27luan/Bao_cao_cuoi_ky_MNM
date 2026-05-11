<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChuyenXe;
use App\Models\Ghe;
use App\Models\TaiKhoan;
use App\Models\TuyenXe;
use App\Models\Ve;
use App\Models\Xe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = TaiKhoan::count();
        $totalAdmins = TaiKhoan::where('role', 'admin')->count();
        $totalDrivers = TaiKhoan::where('role', 'tai_xe')->count();
        $totalCustomers = TaiKhoan::where('role', 'khach_hang')->count();
        $totalBuses = Xe::count();
        $totalRoutes = TuyenXe::count();
        $totalTickets = Ve::count();
        $todayOrders = Ve::whereDate('ngaydat', Carbon::today())->count();
        $monthlyRevenue = Ve::whereYear('ngaydat', Carbon::now()->year)
            ->whereMonth('ngaydat', Carbon::now()->month)
            ->sum('tongsotien');

        $recentTickets = Ve::with('taiKhoan')
            ->orderByDesc('ngaydat')
            ->orderByDesc('mave')
            ->limit(3)
            ->get()
            ->map(function ($ticket) {
                $customerName = optional($ticket->taiKhoan)->hoten
                    ?: optional($ticket->taiKhoan)->phone
                    ?: 'Khach hang';

                return [
                    'icon' => 'fas fa-ticket-alt',
                    'icon_bg' => 'bg-blue-100',
                    'icon_color' => 'text-blue-600',
                    'name' => 'Ve moi: #V' . str_pad($ticket->mave, 6, '0', STR_PAD_LEFT) . ' - ' . $customerName,
                    'time' => $ticket->ngaydat ? Carbon::parse($ticket->ngaydat)->diffForHumans() : 'Chua cap nhat',
                    'sort_time' => $ticket->ngaydat ? Carbon::parse($ticket->ngaydat)->timestamp : 0,
                ];
            });

        $recentUsers = TaiKhoan::orderByDesc('id')
            ->limit(2)
            ->get()
            ->map(function ($user) {
                return [
                    'icon' => 'fas fa-user-plus',
                    'icon_bg' => 'bg-green-100',
                    'icon_color' => 'text-green-600',
                    'name' => 'Nguoi dung moi: ' . ($user->hoten ?: $user->phone),
                    'time' => 'Theo du lieu moi nhat',
                    'sort_time' => 0,
                ];
            });

        $recentRoutes = TuyenXe::orderByDesc('matuyen')
            ->limit(2)
            ->get()
            ->map(function ($route) {
                return [
                    'icon' => 'fas fa-route',
                    'icon_bg' => 'bg-yellow-100',
                    'icon_color' => 'text-yellow-600',
                    'name' => 'Tuyen xe moi: ' . ($route->tentuyen ?: trim($route->diemdi . ' - ' . $route->diemden)),
                    'time' => 'Theo du lieu moi nhat',
                    'sort_time' => 0,
                ];
            });

        $recentActivities = $recentTickets
            ->concat($recentUsers)
            ->concat($recentRoutes)
            ->sortByDesc('sort_time')
            ->take(5)
            ->values();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalDrivers',
            'totalCustomers',
            'totalBuses',
            'totalRoutes',
            'totalTickets',
            'todayOrders',
            'monthlyRevenue',
            'recentActivities'
        ));
    }

    public function users()
    {
        try {
            $users = TaiKhoan::orderBy('id', 'asc')->get();
            return view('admin.users', compact('users'));
        } catch (\Exception $e) {
            Log::error('Admin users error: ' . $e->getMessage());
            return view('admin.users', ['users' => []]);
        }
    }

    public function createUser()
    {
        return view('admin.users-create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'hoten' => 'required|string|max:255',
            'phone' => 'required|string|unique:taikhoan,phone',
            'email' => 'nullable|email|unique:taikhoan,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,tai_xe,khach_hang',
        ]);

        try {
            TaiKhoan::create([
                'phone' => $request->phone,
                'password' => $request->password,
                'role' => $request->role,
                'email' => $request->email,
                'hoten' => $request->hoten,
            ]);

            return redirect()->route('admin.users')->with('success', 'Them nguoi dung thanh cong!');
        } catch (\Exception $e) {
            Log::error('Store user error: ' . $e->getMessage());
            return back()->withErrors(['Loi: ' . $e->getMessage()])->withInput();
        }
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'hoten' => 'required|string|max:255',
            'phone' => 'required|string|unique:taikhoan,phone,' . $id . ',id',
            'email' => 'nullable|email|unique:taikhoan,email,' . $id . ',id',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,tai_xe,khach_hang',
        ]);

        try {
            $user = TaiKhoan::findOrFail($id);
            $data = [
                'phone' => $request->phone,
                'role' => $request->role,
                'email' => $request->email,
                'hoten' => $request->hoten,
            ];

            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $user->update($data);

            return redirect()->route('admin.users')->with('success', 'Cap nhat nguoi dung thanh cong!');
        } catch (\Exception $e) {
            Log::error('Update user error: ' . $e->getMessage());
            return back()->withErrors(['Loi: ' . $e->getMessage()])->withInput();
        }
    }

    public function deleteUser($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $user = TaiKhoan::findOrFail($id);
                Ve::where('mataikhoan', $user->id)->delete();
                $user->delete();
            });

            return redirect()->route('admin.users')->with('success', 'Xoa nguoi dung thanh cong!');
        } catch (\Exception $e) {
            Log::error('Delete user error: ' . $e->getMessage());
            return redirect()->route('admin.users')->withErrors(['Loi xoa nguoi dung: ' . $e->getMessage()]);
        }
    }

    public function getUser($id)
    {
        try {
            return response()->json(TaiKhoan::findOrFail($id));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Khong tim thay nguoi dung'], 404);
        }
    }

    public function buses()
    {
        try {
            $buses = Xe::orderBy('maxe', 'asc')->get();
            return view('admin.buses', compact('buses'));
        } catch (\Exception $e) {
            Log::error('Admin buses error: ' . $e->getMessage());
            return view('admin.buses', ['buses' => []]);
        }
    }

    public function storeBus(Request $request)
    {
        $request->validate([
            'biensoxe' => 'required|string',
            'loaixe' => 'required|string',
            'soghe' => 'required|integer|min:1',
            'nhaxe' => 'required|string',
            'trangthai' => 'required|in:Đang hoạt động,Bảo trì,Ngừng hoạt động',
        ]);

        try {
            $bienSoXe = trim($request->biensoxe);

            if ($this->busPlateExists($bienSoXe)) {
                return back()->withErrors(['biensoxe' => 'Bien so xe "' . $bienSoXe . '" da ton tai'])->withInput();
            }

            Xe::create([
                'biensoxe' => $bienSoXe,
                'loaixe' => $request->loaixe,
                'soghe' => $request->soghe,
                'nhaxe' => $request->nhaxe,
                'trangthai' => $request->trangthai,
            ]);

            return redirect()->route('admin.buses')->with('success', 'Them xe thanh cong!');
        } catch (\Exception $e) {
            Log::error('Store bus error: ' . $e->getMessage());
            return back()->withErrors(['Loi: ' . $e->getMessage()])->withInput();
        }
    }

    public function updateBus(Request $request, $id)
    {
        $request->validate([
            'biensoxe' => 'required|string',
            'loaixe' => 'required|string',
            'soghe' => 'required|integer|min:1',
            'nhaxe' => 'required|string',
            'trangthai' => 'required|in:Đang hoạt động,Bảo trì,Ngừng hoạt động',
        ]);

        try {
            $bus = Xe::findOrFail($id);
            $bienSoXe = trim($request->biensoxe);

            if ($this->busPlateExists($bienSoXe, $id)) {
                return back()->withErrors(['biensoxe' => 'Bien so xe "' . $bienSoXe . '" da ton tai'])->withInput();
            }

            $bus->update([
                'biensoxe' => $bienSoXe,
                'loaixe' => $request->loaixe,
                'soghe' => $request->soghe,
                'nhaxe' => $request->nhaxe,
                'trangthai' => $request->trangthai,
            ]);

            return redirect()->route('admin.buses')->with('success', 'Cap nhat xe thanh cong!');
        } catch (\Exception $e) {
            Log::error('Update bus error: ' . $e->getMessage());
            return back()->withErrors(['Loi: ' . $e->getMessage()])->withInput();
        }
    }

    public function deleteBus($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $bus = Xe::findOrFail($id);
                $seatIds = Ghe::where('maxe', $id)->pluck('maghe');

                if ($seatIds->isNotEmpty()) {
                    Ve::whereIn('maghe', $seatIds)->delete();
                }

                ChuyenXe::where('maxe', $id)->delete();
                TuyenXe::where('maxe', $id)->update(['maxe' => null]);
                Ghe::where('maxe', $id)->delete();
                $bus->delete();
            });

            return redirect()->route('admin.buses')->with('success', 'Xoa xe thanh cong!');
        } catch (\Exception $e) {
            Log::error('Delete bus error: ' . $e->getMessage());
            return redirect()->route('admin.buses')->withErrors(['Loi xoa xe: ' . $e->getMessage()]);
        }
    }

    public function getBus($id)
    {
        try {
            return response()->json(Xe::findOrFail($id));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Khong tim thay xe'], 404);
        }
    }

    private function busPlateExists(string $bienSoXe, ?int $exceptBusId = null): bool
    {
        $query = Xe::whereRaw('LOWER(TRIM(biensoxe)) = ?', [mb_strtolower(trim($bienSoXe))]);

        if ($exceptBusId) {
            $query->where('maxe', '!=', $exceptBusId);
        }

        return $query->exists();
    }

    public function routes()
    {
        try {
            $routes = TuyenXe::with('xe')->orderBy('matuyen', 'asc')->get();
            $buses = Xe::where('trangthai', 'Đang hoạt động')->get();

            return view('admin.routes', compact('routes', 'buses'));
        } catch (\Exception $e) {
            Log::error('Admin routes error: ' . $e->getMessage());
            return view('admin.routes', ['routes' => [], 'buses' => []]);
        }
    }

    public function storeRoute(Request $request)
    {
        $request->validate([
            'tentuyen' => 'required|string|max:255',
            'diemdi' => 'required|string|max:255',
            'diemden' => 'required|string|max:255',
            'khoangcach' => 'required|numeric|min:0',
            'thoigian' => 'required|string|max:50',
            'giatien' => 'required|numeric|min:0',
            'trangthai' => 'required|in:Đang hoạt động,Ngừng hoạt động',
            'maxe' => 'nullable|exists:xe,maxe',
        ]);

        try {
            TuyenXe::create($this->routeData($request));
            return redirect()->route('admin.routes')->with('success', 'Them tuyen thanh cong!');
        } catch (\Exception $e) {
            Log::error('Store route error: ' . $e->getMessage());
            return back()->withErrors(['Loi: ' . $e->getMessage()])->withInput();
        }
    }

    public function updateRoute(Request $request, $id)
    {
        $request->validate([
            'tentuyen' => 'required|string|max:255',
            'diemdi' => 'required|string|max:255',
            'diemden' => 'required|string|max:255',
            'khoangcach' => 'required|numeric|min:0',
            'thoigian' => 'required|string|max:50',
            'giatien' => 'required|numeric|min:0',
            'trangthai' => 'required|in:Đang hoạt động,Ngừng hoạt động',
            'maxe' => 'nullable|exists:xe,maxe',
        ]);

        try {
            $route = TuyenXe::findOrFail($id);
            $route->update($this->routeData($request));

            return redirect()->route('admin.routes')->with('success', 'Cap nhat tuyen thanh cong!');
        } catch (\Exception $e) {
            Log::error('Update route error: ' . $e->getMessage());
            return back()->withErrors(['Loi: ' . $e->getMessage()])->withInput();
        }
    }

    public function deleteRoute($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $route = TuyenXe::findOrFail($id);
                ChuyenXe::where('matuyen', $route->matuyen)->delete();
                $route->delete();
            });

            return redirect()->route('admin.routes')->with('success', 'Xoa tuyen thanh cong!');
        } catch (\Exception $e) {
            Log::error('Delete route error: ' . $e->getMessage());
            return redirect()->route('admin.routes')->withErrors(['Loi xoa tuyen: ' . $e->getMessage()]);
        }
    }

    public function getRoute($id)
    {
        try {
            return response()->json(TuyenXe::with('xe')->findOrFail($id));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Khong tim thay tuyen'], 404);
        }
    }

    private function routeData(Request $request): array
    {
        return [
            'tentuyen' => trim($request->tentuyen),
            'diemdi' => trim($request->diemdi),
            'diemden' => trim($request->diemden),
            'thoigiandukien' => trim($request->thoigian),
            'khoangcach' => $request->khoangcach,
            'giatien' => $request->giatien,
            'maxe' => $request->maxe ?: null,
            'trangthai' => $request->trangthai,
        ];
    }

    public function trips()
    {
        try {
            $trips = ChuyenXe::with(['tuyenXe.xe.ghes', 'xe.ghes'])
                ->orderBy('machuyen', 'asc')
                ->get()
                ->map(function ($trip) {
                    $trip->ghe_trong = $this->availableSeatsForTrip($trip);
                    return $trip;
                });
            $routes = TuyenXe::with('xe')->where('trangthai', 'Đang hoạt động')->get();
            $buses = Xe::with('ghes')->where('trangthai', 'Đang hoạt động')->get();

            return view('admin.trips', compact('trips', 'routes', 'buses'));
        } catch (\Exception $e) {
            Log::error('Admin trips error: ' . $e->getMessage());
            return view('admin.trips', ['trips' => [], 'routes' => [], 'buses' => []]);
        }
    }

    public function storeTrip(Request $request)
    {
        $request->validate([
            'matuyen' => 'required|exists:tuyenxe,matuyen',
            'maxe' => 'nullable|exists:xe,maxe',
            'ngaydi' => 'required|date',
            'giodi' => 'required',
            'giave' => 'nullable|numeric|min:0',
        ]);

        try {
            ChuyenXe::create($this->tripData($request));
            return redirect()->route('admin.trips')->with('success', 'Them chuyen thanh cong!');
        } catch (\Exception $e) {
            Log::error('Store trip error: ' . $e->getMessage());
            return back()->withErrors(['Loi: ' . $e->getMessage()])->withInput();
        }
    }

    public function updateTrip(Request $request, $id)
    {
        $request->validate([
            'matuyen' => 'required|exists:tuyenxe,matuyen',
            'maxe' => 'nullable|exists:xe,maxe',
            'ngaydi' => 'required|date',
            'giodi' => 'required',
            'giave' => 'nullable|numeric|min:0',
        ]);

        try {
            $trip = ChuyenXe::findOrFail($id);
            $trip->update($this->tripData($request));
            return redirect()->route('admin.trips')->with('success', 'Cap nhat chuyen thanh cong!');
        } catch (\Exception $e) {
            Log::error('Update trip error: ' . $e->getMessage());
            return back()->withErrors(['Loi: ' . $e->getMessage()])->withInput();
        }
    }

    public function deleteTrip($id)
    {
        try {
            DB::transaction(function () use ($id) {
                ChuyenXe::findOrFail($id)->delete();
            });

            return redirect()->route('admin.trips')->with('success', 'Xoa chuyen thanh cong!');
        } catch (\Exception $e) {
            Log::error('Delete trip error: ' . $e->getMessage());
            return redirect()->route('admin.trips')->withErrors(['Loi xoa chuyen: ' . $e->getMessage()]);
        }
    }

    public function getTrip($id)
    {
        try {
            $trip = ChuyenXe::with(['tuyenXe', 'xe.ghes'])->findOrFail($id);
            $trip->ghe_trong = $this->availableSeatsForTrip($trip);
            return response()->json($trip);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Khong tim thay chuyen'], 404);
        }
    }

    private function tripData(Request $request): array
    {
        $route = TuyenXe::findOrFail($request->matuyen);
        $busId = $route->maxe ?: $request->maxe;

        if (!$busId) {
            throw new \RuntimeException('Tuyen xe chua duoc phan cong xe.');
        }

        return [
            'matuyen' => $route->matuyen,
            'maxe' => $busId,
            'ngaydi' => $request->ngaydi,
            'giodi' => $request->giodi,
            'giave' => $this->normalizeTicketPrice($route->giatien ?: $request->giave),
            'ghe_trong' => $this->availableSeatsForBus((int) $busId),
        ];
    }

    private function normalizeTicketPrice($price): int
    {
        $price = (float) $price;

        if ($price > 0 && $price < 1000) {
            return (int) ($price * 1000);
        }

        return (int) $price;
    }

    private function availableSeatsForBus(int $busId): int
    {
        return (int) Xe::where('maxe', $busId)->value('soghe');
    }

    private function availableSeatsForTrip(ChuyenXe $trip): int
    {
        $totalSeats = $this->availableSeatsForBus((int) $trip->maxe);
        $bookedSeats = Ve::where('machuyen', $trip->machuyen)->count();

        return max(0, $totalSeats - $bookedSeats);
    }

    public function tickets()
    {
        try {
            $tickets = Ve::with(['ghe', 'taiKhoan', 'chuyenXe.tuyenXe'])
                ->orderBy('mave', 'asc')
                ->get();

            $totalTickets = $tickets->count();
            $totalRevenue = $tickets->sum('tongsotien');
            $daThanhToan = $tickets->where('trangthai', 'da_di')->count();
            $choThanhToan = $tickets->where('trangthai', 'cho_don')->count();

            return view('admin.tickets', compact('tickets', 'totalTickets', 'totalRevenue', 'daThanhToan', 'choThanhToan'));
        } catch (\Exception $e) {
            Log::error('Admin tickets error: ' . $e->getMessage());
            return view('admin.tickets', [
                'tickets' => [],
                'totalTickets' => 0,
                'totalRevenue' => 0,
                'daThanhToan' => 0,
                'choThanhToan' => 0,
            ]);
        }
    }

    public function updateTicketStatus(Request $request, $id)
    {
        $request->validate([
            'trangthai' => 'required|in:cho_don,da_di',
        ]);

        try {
            $ticket = Ve::findOrFail($id);
            $ticket->update(['trangthai' => $request->trangthai]);

            return redirect()->route('admin.tickets')->with('success', 'Cap nhat trang thai ve thanh cong!');
        } catch (\Exception $e) {
            Log::error('Update ticket status error: ' . $e->getMessage());
            return back()->withErrors(['Loi: ' . $e->getMessage()]);
        }
    }

    public function deleteTicket($id)
    {
        try {
            DB::transaction(function () use ($id) {
                Ve::findOrFail($id)->delete();
            });

            return redirect()->route('admin.tickets')->with('success', 'Xoa ve thanh cong!');
        } catch (\Exception $e) {
            Log::error('Delete ticket error: ' . $e->getMessage());
            return redirect()->route('admin.tickets')->withErrors(['Loi xoa ve: ' . $e->getMessage()]);
        }
    }

    public function getTicket($id)
    {
        try {
            $ticket = Ve::with(['ghe', 'taiKhoan', 'chuyenXe.tuyenXe'])->findOrFail($id);
            return response()->json($ticket);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Khong tim thay ve'], 404);
        }
    }

    public function exportTickets()
    {
        return redirect()->back()->with('success', 'Chuc nang dang phat trien!');
    }

    public function payments()
    {
        return view('admin.payments');
    }

    public function promotions()
    {
        return view('admin.promotions');
    }

    public function reports()
    {
        $totalRevenue = Ve::sum('tongsotien');
        $totalTickets = Ve::count();
        $totalUsers = TaiKhoan::count();
        $recentTickets = Ve::orderBy('ngaydat', 'asc')->limit(10)->get();

        return view('admin.reports', compact('totalRevenue', 'totalTickets', 'totalUsers', 'recentTickets'));
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
