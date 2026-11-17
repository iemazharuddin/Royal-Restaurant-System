<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\MenuItem;
use App\Models\Reservation;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        // Completed orders
        $completedOrders = Order::where('status', 'completed')->count();

        // Total sales (Order table column: total)
        $totalSales = Order::where('status', 'completed')->sum('total');

        // Payments received
        $paymentsReceived = Payment::where('status', 'completed')->sum('amount');

        $stats = [
            'categories' => Category::count(),
            'menu_items' => MenuItem::count(),
            'reservations' => Reservation::count(),
            'contacts' => Contact::count(),

            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'confirmed_reservations' => Reservation::where('status', 'confirmed')->count(),

            'customers' => Customer::count(),
            'orders' => Order::count(),

            'completed_orders' => $completedOrders,

            'pending_orders' => Order::whereIn('status', [
                'pending',
                'confirmed',
                'preparing'
            ])->count(),

            'total_sales' => $totalSales,
            'payments_received' => $paymentsReceived,
            'revenue' => $paymentsReceived,

            'reviews' => Review::count(),
        ];

        $recentReservations = Reservation::latest()
            ->take(5)
            ->get();

        $popularMenuItems = MenuItem::with('category')
            ->latest()
            ->take(6)
            ->get();

        $recentOrders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'recentReservations',
            'popularMenuItems',
            'recentOrders'
        ));
    }
}
