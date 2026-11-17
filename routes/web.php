<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestaurantTableController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FirebaseAuthController;

// Public routes
Route::get('/', fn () => view('welcome'))->name('home');
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/reservation', [ReservationController::class, 'create'])->name('reservation.create');
Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');

// Authentication routes
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
Route::post('/auth/firebase', [FirebaseAuthController::class, 'login'])->middleware('throttle:10,1')->name('firebase.login');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'can:dashboard.view'])->name('dashboard');

// Admin routes
Route::middleware('auth')->prefix('admin')->name('admin.') ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->middleware('can:dashboard.view')->name('dashboard');

        // Roles
        Route::resource('roles', RoleController::class);
        Route::patch(
            'roles/{role}/permission-toggle',
            [RoleController::class, 'togglePermission']
        )
            ->middleware('can:roles.edit')
            ->name('roles.permissions.toggle');

        // Categories
        Route::resource('categories', CategoryController::class)
            ->middleware('module:categories');

        // Menu items
        Route::resource('menu-items', MenuItemController::class)
            ->middleware('module:menu-items');

        // Reservations
        Route::patch(
            'reservations/{reservation}/status',
            [ReservationController::class, 'updateStatus']
        )
            ->middleware('can:reservations.edit')
            ->name('reservations.update-status');
            
        Route::resource('reservations', ReservationController::class)->except(['create', 'store'])->middleware('module:reservations');
        // Contacts
        Route::resource('contacts', ContactController::class)->middleware('module:contacts');
        // Customers
        Route::resource('customers', CustomerController::class)->except(['show'])->middleware('module:customers');
        // Orders
        Route::resource('orders', OrderController::class)->except(['show']) ->middleware('module:orders');
        // Restaurant tables
        Route::resource('tables', RestaurantTableController::class)->except(['show'])->middleware('module:tables');
        // Payments
        Route::resource('payments', PaymentController::class)->except(['show'])->middleware('module:payments');
        // Offers
        Route::resource('offers', OfferController::class)->except(['show'])->middleware('module:offers');
        // Reviews
        Route::resource('reviews', ReviewController::class)->except(['show'])->middleware('module:reviews');
        // Gallery
        Route::resource('gallery', GalleryController::class)->except(['show'])->middleware('module:gallery');
        // Staff
        Route::resource('staff', StaffProfileController::class)->except(['show'])->middleware('module:staff');
        // Notifications
        Route::resource('notifications', NotificationController::class)->except(['show'])->middleware('module:notifications');
        // Reports
        Route::get('reports', [ReportController::class, 'index'])->middleware('can:reports.view')->name('reports.index');
        // Settings
        Route::resource('settings', SettingController::class) ->only(['index', 'store', 'update', 'destroy'])->middleware('module:settings');
    });

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication scaffolding
require __DIR__ . '/auth.php';
