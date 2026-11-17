<?php

use Illuminate\Support\Facades\Route;
use App\Models\MenuItem;

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

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $menuItems = MenuItem::with('category')->where('is_available', true)->latest() ->get();

    return view('welcome', compact('menuItems'));
})->name('home');

Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/reservation', [ReservationController::class, 'create'])->name('reservation.create');
Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');

// Public customer order submission.
Route::post('/orders', [OrderController::class, 'publicStore'])->name('orders.store');
Route::post('/feedback', [ReviewController::class, 'store'])->name('feedback.store');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
Route::post('/auth/firebase', [FirebaseAuthController::class, 'login'])->middleware('throttle:10,1') ->name('firebase.login');


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'can:dashboard.view']) ->name('dashboard');
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index']) ->middleware('can:dashboard.view') ->name('dashboard');
    Route::resource('roles', RoleController::class);
    Route::patch('roles/{role}/permission-toggle', [RoleController::class, 'togglePermission']) ->middleware('can:roles.edit')->name('roles.permissions.toggle');
    Route::resource('categories', CategoryController::class) ->middleware('module:categories');
    Route::resource('menu-items', MenuItemController::class)->middleware('module:menu-items');
    Route::patch('reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->middleware('can:reservations.edit') ->name('reservations.update-status');
    Route::resource('reservations', ReservationController::class)->except(['create', 'store']) ->middleware('module:reservations');
    Route::resource('contacts', ContactController::class)->middleware('module:contacts');
    Route::resource('customers', CustomerController::class)->except(['show'])->middleware('module:customers');
    Route::get('orders/{order}/invoice', [OrderController::class, 'printInvoice'])->middleware('module:orders')->name('orders.invoice');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->middleware('module:orders')->name('orders.update-status');
    Route::get('orders/{order}', [OrderController::class, 'show'])->middleware('module:orders')->name('orders.show');
    Route::resource('orders', OrderController::class) ->except(['show'])->middleware('module:orders');
    Route::resource('tables', RestaurantTableController::class)->except(['show'])->middleware('module:tables');
    Route::resource('payments', PaymentController::class)->except(['show'])->middleware('module:payments');
    Route::resource('offers', OfferController::class)->except(['show'])->middleware('module:offers');
    Route::resource('reviews', ReviewController::class)->except(['show'])->middleware('module:reviews');
    Route::resource('gallery', GalleryController::class)->except(['show']) ->middleware('module:gallery');
    Route::resource('staff', StaffProfileController::class)->except(['show'])->middleware('module:staff');
    Route::resource('notifications', NotificationController::class)->except(['show'])->middleware('module:notifications');
    Route::get('reports', [ReportController::class, 'index'])->middleware('can:reports.view')->name('reports.index');
    Route::resource('settings', SettingController::class)->only(['index', 'store', 'update', 'destroy'])->middleware('module:settings');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update']) ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy']) ->name('profile.destroy');
});

require __DIR__ . '/auth.php';
