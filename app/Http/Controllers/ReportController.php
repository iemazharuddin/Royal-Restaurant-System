<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $from = $validated['from'] ?? now()->startOfMonth()->toDateString();
        $to = $validated['to'] ?? now()->toDateString();

        // Orders - include complete from/to dates
        $ordersQuery = Order::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $totalOrders = (clone $ordersQuery)->count();

        $pendingOrders = (clone $ordersQuery)
            ->whereIn('status', [
                'pending',
                'confirmed',
                'preparing',
                'ready',
            ])
            ->count();

        $completedOrders = (clone $ordersQuery)
            ->where('status', 'completed')
            ->count();

        $cancelledOrders = (clone $ordersQuery)
            ->where('status', 'cancelled')
            ->count();

        // Sales from completed orders
        $totalSales = (clone $ordersQuery)
            ->where('status', 'completed')
            ->sum('total');

        // Payments received
        $paymentsQuery = Payment::where('status', 'completed')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $totalPayments = (clone $paymentsQuery)->count();
        $totalRevenue = (clone $paymentsQuery)->sum('amount');

        // Customers
        $totalCustomers = Customer::count();

        $newCustomers = Customer::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->count();

        // Recent orders
        $recentOrders = Order::with('customer')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->latest()
            ->take(10)
            ->get();

        // Daily sales
        $dailySales = (clone $ordersQuery)
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as sale_date, SUM(total) as total_sales')
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get();

        return view('admin.reports.index', compact(
            'from',
            'to',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders',
            'totalSales',
            'totalPayments',
            'totalRevenue',
            'totalCustomers',
            'newCustomers',
            'recentOrders',
            'dailySales'
        ));
    }
}
