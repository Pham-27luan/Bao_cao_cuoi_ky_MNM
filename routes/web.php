<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

// ========== PUBLIC ROUTES ==========
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// ========== ROUTES CẦN ĐĂNG NHẬP (role: admin, tai_xe, khach_hang) ==========
Route::middleware(['role:admin,tai_xe,khach_hang'])->group(function () {
    Route::get('/booking/{matuyen}', [BookingController::class, 'index'])->name('booking');
    Route::get('/byticket/{matuyen?}', [BookingController::class, 'byticket'])->name('byticket');

    Route::get('/payment/{matuyen?}', [PaymentController::class, 'show'])->name('payment');
    Route::post('/payment/{matuyen}/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');

    Route::get('/bill', [BillController::class, 'index'])->name('bill');
});

// ========== ADMIN ROUTES (chỉ admin) ==========
Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users/store', [AdminController::class, 'storeUser'])->name('users.store');
    Route::post('/users/update/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::get('/users/get/{id}', [AdminController::class, 'getUser'])->name('users.get');
    Route::get('/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // Bus management
    Route::get('/buses', [AdminController::class, 'buses'])->name('buses');
    Route::post('/buses/store', [AdminController::class, 'storeBus'])->name('buses.store');
    Route::post('/buses/update/{id}', [AdminController::class, 'updateBus'])->name('buses.update');
    Route::get('/buses/get/{id}', [AdminController::class, 'getBus'])->name('buses.get');
    Route::get('/buses/delete/{id}', [AdminController::class, 'deleteBus'])->name('buses.delete');

    // Route management (tuyến xe)
    Route::get('/routes', [AdminController::class, 'routes'])->name('routes');
    Route::post('/routes/store', [AdminController::class, 'storeRoute'])->name('routes.store');
    Route::post('/routes/update/{id}', [AdminController::class, 'updateRoute'])->name('routes.update');
    Route::get('/routes/get/{id}', [AdminController::class, 'getRoute'])->name('routes.get');
    Route::get('/routes/delete/{id}', [AdminController::class, 'deleteRoute'])->name('routes.delete');

    // Trip management (chuyến xe)
    Route::get('/trips', [AdminController::class, 'trips'])->name('trips');
    Route::post('/trips/store', [AdminController::class, 'storeTrip'])->name('trips.store');
    Route::post('/trips/update/{id}', [AdminController::class, 'updateTrip'])->name('trips.update');
    Route::get('/trips/get/{id}', [AdminController::class, 'getTrip'])->name('trips.get');
    Route::get('/trips/delete/{id}', [AdminController::class, 'deleteTrip'])->name('trips.delete');

    // Ticket management (vé)
    Route::get('/tickets', [AdminController::class, 'tickets'])->name('tickets');
    Route::post('/tickets/update-status/{id}', [AdminController::class, 'updateTicketStatus'])->name('tickets.update-status');
    Route::get('/tickets/delete/{id}', [AdminController::class, 'deleteTicket'])->name('tickets.delete');
    Route::get('/tickets/get/{id}', [AdminController::class, 'getTicket'])->name('tickets.get');
    Route::get('/tickets/export', [AdminController::class, 'exportTickets'])->name('tickets.export');

    // Payment management
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');

    // Promotion management
    Route::get('/promotions', [AdminController::class, 'promotions'])->name('promotions');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});
