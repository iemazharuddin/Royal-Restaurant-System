<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // Orders in selected date range
        $ordersQuery = Order::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $totalOrders = (clone $ordersQuery)->count();

        $pendingOrders = (clone $ordersQuery)
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->count();

        $completedOrders = (clone $ordersQuery)
            ->where('status', 'completed')
            ->count();

        $cancelledOrders = (clone $ordersQuery)
            ->where('status', 'cancelled')
            ->count();

        $totalSales = (clone $ordersQuery)
            ->where('status', 'completed')
            ->sum('total');

        // Payments
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
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('sale_date')
            ->get();

        // Monthly sales
        $monthlySales = (clone $ordersQuery)
            ->where('status', 'completed')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as sale_month, SUM(total) as total_sales")
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy('sale_month')
            ->get();

        // Average sales per day (including zero-sales days)
        $daysCount = Carbon::parse($from)->startOfDay()
            ->diffInDays(Carbon::parse($to)->startOfDay()) + 1;

        $averagePerDay = $daysCount > 0
            ? $totalSales / $daysCount
            : 0;

        // Best sales day
        $bestDay = $dailySales->sortByDesc('total_sales')->first();

        $bestDaySales = $bestDay->total_sales ?? 0;
        $bestDayDate = $bestDay->sale_date ?? null;

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
            'dailySales',
            'monthlySales',
            'averagePerDay',
            'bestDaySales',
            'bestDayDate'
        ));
    }
}
