<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DonorController as AdminDonorController;
use App\Http\Controllers\Admin\BloodStockController;
use App\Http\Controllers\Admin\BloodRequestController as AdminBloodRequestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\BloodRequestController as UserBloodRequestController;
use App\Http\Controllers\User\DonorController as UserDonorController;
use App\Http\Controllers\User\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// AI Chat (public)
Route::post('/ai/chat', [AIController::class, 'chat'])->name('ai.chat');
Route::post('/ai/partial-request', [AIController::class, 'createPartialRequest'])->name('ai.partial-request');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (no authentication required)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

    // Authenticated admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Donors Management
        Route::resource('donors', AdminDonorController::class);
        Route::post('/donors/{donor}/toggle-availability', [AdminDonorController::class, 'toggleAvailability'])->name('donors.toggle-availability');
        Route::get('/donors/locations', [AdminDonorController::class, 'locations'])->name('donors.locations');

        // Blood Stock Management
        Route::get('/blood-stock', [BloodStockController::class, 'index'])->name('blood-stock.index');
        Route::put('/blood-stock/{bloodStock}', [BloodStockController::class, 'update'])->name('blood-stock.update');
        Route::post('/blood-stock/{bloodStock}/add', [BloodStockController::class, 'addUnits'])->name('blood-stock.add');
        Route::post('/blood-stock/{bloodStock}/remove', [BloodStockController::class, 'removeUnits'])->name('blood-stock.remove');

        // Blood Requests Management
        Route::get('/requests', [AdminBloodRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/{bloodRequest}', [AdminBloodRequestController::class, 'show'])->name('requests.show');
        Route::post('/requests/{bloodRequest}/approve', [AdminBloodRequestController::class, 'approve'])->name('requests.approve');
        Route::post('/requests/{bloodRequest}/reject', [AdminBloodRequestController::class, 'reject'])->name('requests.reject');

        // Users Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::put('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'exportRequests'])->name('reports.export');

        // AI Assistant
        Route::get('/ai-assistant', [AIController::class, 'showAdminChatUI'])->name('ai-chat');
    });
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::prefix('user')->name('user.')->group(function () {
    // Guest routes (no authentication required)
    Route::middleware('guest:user')->group(function () {
        Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserAuthController::class, 'login']);
        Route::get('/register', [UserAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [UserAuthController::class, 'register']);
    });

    // Authenticated user routes
    Route::middleware('auth:user')->group(function () {
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        // Blood Requests
        Route::get('/request-blood', [UserBloodRequestController::class, 'create'])->name('request-blood');
        Route::post('/request-blood', [UserBloodRequestController::class, 'store']);
        Route::get('/my-requests', [UserBloodRequestController::class, 'index'])->name('requests.index');
        Route::get('/my-requests/{bloodRequest}', [UserBloodRequestController::class, 'show'])->name('requests.show');

        // Donors
        Route::get('/donors', [UserDonorController::class, 'index'])->name('donors');
        Route::get('/find-nearby', [UserDonorController::class, 'findNearby'])->name('find-nearby');
        Route::post('/get-nearby-donors', [UserDonorController::class, 'getNearby'])->name('get-nearby-donors');
        Route::get('/donors/{donor}/contact', [UserDonorController::class, 'contact'])->name('contact-donor');
        Route::get('/track-stock', [UserDonorController::class, 'trackStock'])->name('track-stock');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::get('/notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');

        // AI Chat
        Route::get('/ai-assistant', [AIController::class, 'showChatUI'])->name('ai-chat');
    });
});
