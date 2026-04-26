<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaiKhoan;
use App\Models\Xe;
use App\Models\TuyenXe;
use App\Models\ChuyenXe;
use App\Models\Ve;
use App\Models\Ghe;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    // Dashboard
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
                    ?: 'Khách hàng';

                return [
                    'icon' => 'fas fa-ticket-alt',
                    'icon_bg' => 'bg-blue-100',
                    'icon_color' => 'text-blue-600',
                    'name' => 'Vé mới: #V' . str_pad($ticket->mave, 6, '0', STR_PAD_LEFT)
                        . ' - ' . $customerName,
                    'time' => $ticket->ngaydat ? Carbon::parse($ticket->ngaydat)->diffForHumans() : 'Chưa cập nhật',
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
                    'name' => 'Người dùng mới: ' . ($user->hoten ?: $user->phone),
                    'time' => 'Theo dữ liệu mới nhất',
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
                    'name' => 'Tuyến xe mới: ' . ($route->tentuyen ?: trim($route->diemdi . ' - ' . $route->diemden)),
                    'time' => 'Theo dữ liệu mới nhất',
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


    // ========== QUẢN LÝ NGƯỜI DÙNG ==========


    // Hiển thị danh sách người dùng
    public function users()
    {
        try {
            // Lấy tất cả người dùng, sắp xếp theo ID (asc: tăng dần, desc: giảm dần)
            $users = TaiKhoan::orderBy('id', 'asc')->get();
            return view('admin.users', compact('users'));
        } catch (\Exception $e) {
            Log::error('Admin users error: ' . $e->getMessage());
            $users = [];
            return view('admin.users', compact('users'));
        }
    }

    // Hiển thị form thêm người dùng
    public function createUser()
    {
        return view('admin.users-create');
    }

    // Lưu người dùng mới
    public function storeUser(Request $request)
    {
        $request->validate([
            'hoten' => 'required|string|max:255',
            'phone' => 'required|string|unique:taikhoan,phone',
            'email' => 'nullable|email|unique:taikhoan,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,tai_xe,khach_hang',
        ], [
            'hoten.required' => 'Vui lòng nhập họ tên',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        try {
            TaiKhoan::create([
                'phone' => $request->phone,
                'password' => $request->password,
                'role' => $request->role,
                'email' => $request->email,
                'hoten' => $request->hoten,
            ]);

            return redirect()->route('admin.users')->with('success', 'Thêm người dùng thành công!');
        } catch (\Exception $e) {
            Log::error('Store user error: ' . $e->getMessage());
            return back()->withErrors(['Lỗi: ' . $e->getMessage()])->withInput();
        }
    }

    // Cập nhật người dùng
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

            return redirect()->route('admin.users')->with('success', 'Cập nhật người dùng thành công!');
        } catch (\Exception $e) {
            Log::error('Update user error: ' . $e->getMessage());
            return back()->withErrors(['Lỗi: ' . $e->getMessage()])->withInput();
        }
    }
    // Xóa người dùng
    public function deleteUser($id)
    {
        try {
            $user = TaiKhoan::findOrFail($id);
            $user->delete();

            return redirect()->route('admin.users')->with('success', 'Xóa người dùng thành công!');
        } catch (\Exception $e) {
            Log::error('Delete user error: ' . $e->getMessage());
            return back()->withErrors(['Lỗi: ' . $e->getMessage()]);
        }
    }

    // Lấy thông tin theo ID
    public function getUser($id)
    {
        try {
            $user = TaiKhoan::findOrFail($id);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không tìm thấy người dùng'], 404);
        }
    }


    // ========== QUẢN LÝ XE ==========


    // Hiển thị danh sách xe
    public function buses()
    {
        try {
            $buses = Xe::orderBy('maxe', 'asc')->get();
            return view('admin.buses', compact('buses'));
        } catch (\Exception $e) {
            Log::error('Admin buses error: ' . $e->getMessage());
            $buses = [];
            return view('admin.buses', compact('buses'));
        }
    }

    // Thêm xe mới
    public function storeBus(Request $request)
    {
        $request->validate([
            'biensoxe' => 'required|string',
            'loaixe' => 'required|string',
            'soghe' => 'required|integer|min:1',
            'nhaxe' => 'required|string',
            'trangthai' => 'required|in:Đang hoạt động,Bảo trì,Ngừng hoạt động',
        ], [
            'biensoxe.required' => 'Vui lòng nhập biển số xe',
            'biensoxe.unique' => 'Biển số xe đã tồn tại',
            'loaixe.required' => 'Vui lòng chọn loại xe',
            'soghe.required' => 'Vui lòng nhập số ghế',
            'soghe.min' => 'Số ghế phải lớn hơn 0',
            'nhaxe.required' => 'Vui lòng nhập tên nhà xe',
        ]);

        try {
            $bienSoXe = trim($request->biensoxe);

            if ($this->busPlateExists($bienSoXe)) {
                return back()
                    ->withErrors(['biensoxe' => 'Biển số xe "' . $bienSoXe . '" đã tồn tại'])
                    ->withInput();
            }

            $data = [
                'biensoxe' => $bienSoXe,
                'loaixe' => $request->loaixe,
                'soghe' => $request->soghe,
                'nhaxe' => $request->nhaxe,
                'trangthai' => $request->trangthai,
            ];

            Xe::create($data);

            return redirect()->route('admin.buses')->with('success', 'Thêm xe thành công!');
        } catch (\Exception $e) {
            Log::error('Store bus error: ' . $e->getMessage());
            return back()->withErrors(['Lỗi: ' . $e->getMessage()])->withInput();
        }
    }

    // Cập nhật xe
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
                return back()
                    ->withErrors(['biensoxe' => 'Biển số xe "' . $bienSoXe . '" đã tồn tại'])
                    ->withInput();
            }

            $data = [
                'biensoxe' => $bienSoXe,
                'loaixe' => $request->loaixe,
                'soghe' => $request->soghe,
                'nhaxe' => $request->nhaxe,
                'trangthai' => $request->trangthai,
            ];

            $bus->update($data);

            return redirect()->route('admin.buses')->with('success', 'Cập nhật xe thành công!');
        } catch (\Exception $e) {
            Log::error('Update bus error: ' . $e->getMessage());
            return back()->withErrors(['Lỗi: ' . $e->getMessage()])->withInput();
        }
    }

    // Xóa xe
    public function deleteBus($id)
    {
        try {
            // Xóa tất cả liên quan trước
            ChuyenXe::where('maxe', $id)->delete();
            TuyenXe::where('maxe', $id)->update(['maxe' => null]);

            // Cuối cùng xóa xe
            Xe::destroy($id);

            return redirect()->route('admin.buses')->with('success', 'Xóa xe thành công!');
        } catch (\Exception $e) {
            return back()->withErrors(['Lỗi: ' . $e->getMessage()]);
        }
    }

    // Lấy thông tin xe theo ID (cho modal sửa)
    public function getBus($id)
    {
        try {
            $bus = Xe::findOrFail($id);
            return response()->json($bus);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không tìm thấy xe'], 404);
        }
    }


    // ========== QUẢN LÝ TUYẾN XE ==========


    // Quản lý tuyến - Hiển thị danh sách
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
            $routes = TuyenXe::with('xe')
                ->orderBy('matuyen', 'asc')
                ->get();
            $buses = Xe::where('trangthai', 'Đang hoạt động')->get();
            return view('admin.routes', compact('routes', 'buses'));
        } catch (\Exception $e) {
            Log::error('Admin routes error: ' . $e->getMessage());
            $routes = [];
            $buses = [];
            return view('admin.routes', compact('routes', 'buses'));
        }
    }

    // Thêm tuyến mới
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
        ], [
            'diemdi.required' => 'Vui lòng nhập điểm đi',
            'diemden.required' => 'Vui lòng nhập điểm đến',
            'khoangcach.required' => 'Vui lòng nhập khoảng cách',
            'thoigian.required' => 'Vui lòng nhập thời gian',
            'giatien.required' => 'Vui lòng nhập giá tiền',
            'maxe.exists' => 'Xe không tồn tại',
        ]);

        try {
            TuyenXe::create($this->routeData($request));

            return redirect()->route('admin.routes')->with('success', 'Thêm tuyến thành công!');
        } catch (\Exception $e) {
            Log::error('Store route error: ' . $e->getMessage());
            return back()->withErrors(['Lỗi: ' . $e->getMessage()])->withInput();
        }
    }

    // Cập nhật tuyến
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

            return redirect()->route('admin.routes')->with('success', 'Cập nhật tuyến thành công!');
        } catch (\Exception $e) {
            Log::error('Update route error: ' . $e->getMessage());
            return back()->withErrors(['Lỗi: ' . $e->getMessage()])->withInput();
        }
    }

    // Xóa tuyến
    public function deleteRoute($id)
    {
        try {
            $route = TuyenXe::findOrFail($id);

            // Kiểm tra nếu tuyến đang có chuyến xe thì không cho xóa
            if ($route->chuyenXes && $route->chuyenXes->count() > 0) {
                return back()->withErrors(['Không thể xóa tuyến này vì đang có chuyến xe liên quan!']);
            }

            $route->delete();
            return redirect()->route('admin.routes')->with('success', 'Xóa tuyến thành công!');
        } catch (\Exception $e) {
            Log::error('Delete route error: ' . $e->getMessage());
            return back()->withErrors(['Lỗi: ' . $e->getMessage()]);
        }
    }

    // Lấy thông tin tuyến theo ID
    public function getRoute($id)
    {
        try {
            $route = TuyenXe::with('xe')->findOrFail($id);
            return response()->json($route);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không tìm thấy tuyến'], 404);
        }
    }


    // ========== QUẢN LÝ CHUYẾN XE ==========


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
                ->get();
            $routes = TuyenXe::with('xe')->where('trangthai', 'Đang hoạt động')->get();
            $buses = Xe::with('ghes')->where('trangthai', 'Đang hoạt động')->get();
            return view('admin.trips', compact('trips', 'routes', 'buses'));
        } catch (\Exception $e) {
            Log::error('Admin trips error: ' . $e->getMessage());
            $trips = [];
            $routes = [];
            $buses = [];
            return view('admin.trips', compact('trips', 'routes', 'buses'));
        }
    }

    // Thêm chuyến mới
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
            return redirect()->route('admin.trips')->with('success', 'Thêm chuyến thành công!');
        } catch (\Exception $e) {
            Log::error('Store trip error: ' . $e->getMessage());
            return back()->withErrors(['Lỗi: ' . $e->getMessage()])->withInput();
        }
    }

    // Cập nhật chuyến
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
            return redirect()->route('admin.trips')->with('success', 'Cập nhật chuyến thành công!');
        } catch (\Exception $e) {
            Log::error('Update trip error: ' . $e->getMessage());
            return back()->withErrors(['Lỗi: ' . $e->getMessage()])->withInput();
        }
    }

    // Xóa chuyến
    public function deleteTrip($id)
    {
        try {
            $trip = ChuyenXe::findOrFail($id);
            $trip->delete();
            return redirect()->route('admin.trips')->with('success', 'Xóa chuyến thành công!');
        } catch (\Exception $e) {
            Log::error('Delete trip error: ' . $e->getMessage());
            return back()->withErrors(['Lỗi: ' . $e->getMessage()]);
        }
    }

    // Lấy thông tin chuyến theo ID
    public function getTrip($id)
    {
        try {
            $trip = ChuyenXe::with(['tuyenXe', 'xe.ghes'])->findOrFail($id);
            $trip->ghe_trong = $this->availableSeatsForBus((int) $trip->maxe);
            return response()->json($trip);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không tìm thấy chuyến'], 404);
        }
    }


    // ========== QUẢN LÝ VÉ ==========


    // Hiển thị danh sách vé
    private function tripData(Request $request): array
    {
        $route = TuyenXe::findOrFail($request->matuyen);
        $busId = $route->maxe ?: $request->maxe;

        if (!$busId) {
            throw new \RuntimeException('Tuyến xe chưa được phân công xe.');
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
        $totalSeats = (int) Xe::where('maxe', $busId)->value('soghe');
        $availableSeats = Ghe::where('maxe', $busId)
            ->where(function ($query) {
                $query->where('trangthai', '!=', 'da_dat')
                    ->orWhereNull('trangthai');
            })
            ->count();

        return min($availableSeats, $totalSeats);
    }

    public function tickets()
    {
        try {
            $tickets = Ve::with(['ghe', 'taiKhoan'])
                ->orderBy('mave', 'asc')
                ->get();

            // Thống kê
            $totalTickets = $tickets->count();
            $totalRevenue = $tickets->sum('tongsotien');
            $daThanhToan = $tickets->where('trangthai', 'da_di')->count();
            $choThanhToan = $tickets->where('trangthai', 'cho_don')->count();

            return view('admin.tickets', compact('tickets', 'totalTickets', 'totalRevenue', 'daThanhToan', 'choThanhToan'));
        } catch (\Exception $e) {
            Log::error('Admin tickets error: ' . $e->getMessage());
            $tickets = [];
            $totalTickets = 0;
            $totalRevenue = 0;
            $daThanhToan = 0;
            $choThanhToan = 0;
            return view('admin.tickets', compact('tickets', 'totalTickets', 'totalRevenue', 'daThanhToan', 'choThanhToan'));
        }
    }

    // Cập nhật trạng thái vé
    public function updateTicketStatus(Request $request, $id)
    {
        $request->validate([
            'trangthai' => 'required|in:cho_don,da_di',
        ]);

        try {
            $ticket = Ve::findOrFail($id);
            $ticket->update([
                'trangthai' => $request->trangthai,
            ]);

            return redirect()->route('admin.tickets')->with('success', 'Cập nhật trạng thái vé thành công!');
        } catch (\Exception $e) {
            Log::error('Update ticket status error: ' . $e->getMessage());
            return back()->withErrors(['Lỗi: ' . $e->getMessage()]);
        }
    }

    // Xóa vé
    public function deleteTicket($id)
    {
        try {
            $ticket = Ve::findOrFail($id);
            $ticket->delete();
            return redirect()->route('admin.tickets')->with('success', 'Xóa vé thành công!');
        } catch (\Exception $e) {
            Log::error('Delete ticket error: ' . $e->getMessage());
            return back()->withErrors(['Lỗi: ' . $e->getMessage()]);
        }
    }

    // Lấy thông tin vé theo ID
    public function getTicket($id)
    {
        try {
            $ticket = Ve::with(['ghe', 'taiKhoan'])->findOrFail($id);
            return response()->json($ticket);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không tìm thấy vé'], 404);
        }
    }

    // Xuất báo cáo vé (Excel)
    public function exportTickets()
    {
        // TODO: Implement export to Excel
        return redirect()->back()->with('success', 'Chức năng đang phát triển!');
    }



    // Quản lý thanh toán
    public function payments()
    {
        return view('admin.payments');
    }

    // Khuyến mãi
    public function promotions()
    {
        return view('admin.promotions');
    }

    // Báo cáo thống kê
    public function reports()
    {
        // Lấy dữ liệu thống kê
        $totalRevenue = Ve::sum('tongsotien');
        $totalTickets = Ve::count();
        $totalUsers = TaiKhoan::count();
        $recentTickets = Ve::orderBy('ngaydat', 'asc')->limit(10)->get();

        return view('admin.reports', compact('totalRevenue', 'totalTickets', 'totalUsers', 'recentTickets'));
    }

    // Cài đặt hệ thống
    public function settings()
    {
        return view('admin.settings');
    }
}
