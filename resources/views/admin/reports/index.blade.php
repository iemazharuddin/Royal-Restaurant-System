@extends('layouts.admin')

@section('title', 'Reports & Analytics')
@section('page-heading', 'Reports & Analytics')

@section('content')

@php
    // ---------- Chart data prepared from the existing $dailySales ----------
    $fromC = \Carbon\Carbon::parse($from)->startOfDay();
    $toC   = \Carbon\Carbon::parse($to)->startOfDay();
    if ($fromC->gt($toC)) { [$fromC, $toC] = [$toC, $fromC]; }
    $rangeDays = (int) abs($fromC->diffInDays($toC)) + 1;

    $byDay = collect($dailySales)->mapWithKeys(fn($d) => [
        \Carbon\Carbon::parse($d->sale_date)->format('Y-m-d') => (float) $d->total_sales,
    ]);

    // Daily series (zero-filled so the line is continuous)
    $dailyLabels = []; $dailyValues = [];
    foreach (\Carbon\CarbonPeriod::create($fromC, $toC) as $dt) {
        $dailyLabels[] = $dt->format('d M');
        $dailyValues[] = (float) ($byDay[$dt->format('Y-m-d')] ?? 0);
    }

    // Monthly series
    $monthly = [];
    $cursor = $fromC->copy()->startOfMonth();
    while ($cursor->lte($toC)) { $monthly[$cursor->format('Y-m')] = 0; $cursor->addMonthNoOverflow(); }
    foreach ($byDay as $date => $v) {
        $k = substr($date, 0, 7);
        $monthly[$k] = ($monthly[$k] ?? 0) + $v;
    }
    $monthlyLabels = []; $monthlyValues = [];
    foreach ($monthly as $k => $v) {
        $monthlyLabels[] = \Carbon\Carbon::parse($k . '-01')->format('M Y');
        $monthlyValues[] = round($v, 2);
    }

    $useMonthly   = $rangeDays > 62;
    $sumSales     = (float) $byDay->sum();
    $avgPerDay    = $rangeDays ? $sumSales / $rangeDays : 0;
    $bestDayKey   = $byDay->count() ? $byDay->sortDesc()->keys()->first() : null;
    $bestDayValue = $bestDayKey ? $byDay[$bestDayKey] : 0;

    // Breakdown list (monthly for long ranges, daily for short)
    $breakdown = [];
    if ($useMonthly) {
        foreach (array_reverse($monthly, true) as $k => $v) {
            $breakdown[] = ['label' => \Carbon\Carbon::parse($k . '-01')->format('F Y'), 'value' => (float) $v];
        }
    } else {
        foreach ($dailySales as $d) {
            $breakdown[] = ['label' => \Carbon\Carbon::parse($d->sale_date)->format('d M Y'), 'value' => (float) $d->total_sales];
        }
    }
    $maxBreak = max(1, collect($breakdown)->max('value') ?? 1);
@endphp

<div class="space-y-5 reports-page">

    {{-- HEADER --}}
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <p class="gold text-[10px] font-bold uppercase tracking-[0.2em]">Business Intelligence</p>
            <h1 class="mt-1 text-2xl font-extrabold">Reports & Analytics</h1>
            <p class="muted mt-1 text-xs">Review sales, payments, orders and customer activity.</p>
        </div>

        <button type="button" onclick="window.print()"
            class="admin-add-btn rounded-lg px-3 py-2 text-xs font-semibold">
            <i class="fas fa-print mr-1"></i> Print Report
        </button>
    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('admin.reports.index') }}" id="reportFilter"
        class="surface rounded-xl p-4">

        <div class="mb-3 flex items-center gap-2">
            <i class="fas fa-calendar-alt gold text-xs"></i>
            <h2 class="text-sm font-bold">Filter Reports</h2>
        </div>

        {{-- Quick ranges --}}
        <div class="mb-3 flex flex-wrap gap-2">
            <button type="button" data-range="today"  class="range-chip">Today</button>
            <button type="button" data-range="7"      class="range-chip">Last 7 Days</button>
            <button type="button" data-range="month"  class="range-chip">This Month</button>
            <button type="button" data-range="6m"     class="range-chip">Last 6 Months</button>
            <button type="button" data-range="12m"    class="range-chip">Last 12 Months</button>
        </div>

        <div class="filter-grid">

            <div class="min-w-0">
                <label for="from" class="mb-1.5 block text-xs font-semibold">From Date</label>
                <input id="from" type="date" name="from"
                    value="{{ $from }}" max="{{ $to }}"
                    class="admin-form-control date-input w-full">
            </div>

            <div class="min-w-0">
                <label for="to" class="mb-1.5 block text-xs font-semibold">To Date</label>
                <input id="to" type="date" name="to"
                    value="{{ $to }}" min="{{ $from }}"
                    class="admin-form-control date-input w-full">
            </div>

            <button type="submit"
                class="admin-add-btn rounded-lg px-3 py-2 text-xs font-semibold">
                <i class="fas fa-filter mr-1"></i> Apply Filter
            </button>

            <a href="{{ route('admin.reports.index') }}"
                class="surface flex items-center justify-center gap-2 rounded-lg border px-3 py-2 text-xs font-semibold">
                <i class="fas fa-redo"></i> Reset
            </a>

        </div>
    </form>

    {{-- MAIN CARDS --}}
    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">

        @foreach([
            ['Total Orders', number_format((int)$totalOrders), 'fa-shopping-bag'],
            ['Completed Orders', number_format((int)$completedOrders), 'fa-check-circle'],
            ['Total Sales', 'Rs. '.number_format((float)$totalSales,2), 'fa-chart-line'],
            ['Payments Received', 'Rs. '.number_format((float)$totalRevenue,2), 'fa-wallet'],
        ] as [$label, $value, $icon])

            <div class="surface rounded-xl p-4">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="muted text-xs">{{ $label }}</p>
                        <h3 class="mt-2 break-words text-lg font-extrabold">{{ $value }}</h3>
                    </div>

                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-sm text-[#172338]"
                        style="background:linear-gradient(145deg,#ffe2a0,#d49a36)">
                        <i class="fas {{ $icon }}"></i>
                    </span>
                </div>
            </div>

        @endforeach

    </div>

    {{-- SECONDARY CARDS --}}
    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">

        @foreach([
            ['Pending Orders', $pendingOrders, 'fa-clock'],
            ['Cancelled Orders', $cancelledOrders, 'fa-times-circle'],
            ['Total Customers', $totalCustomers, 'fa-users'],
            ['New Customers', $newCustomers, 'fa-user-plus'],
        ] as [$label, $value, $icon])

            <div class="surface rounded-xl p-4">
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <p class="muted text-xs">{{ $label }}</p>
                        <h3 class="mt-2 text-lg font-extrabold">{{ number_format((int)$value) }}</h3>
                    </div>

                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-500/10 text-sm text-amber-500">
                        <i class="fas {{ $icon }}"></i>
                    </span>
                </div>
            </div>

        @endforeach

    </div>

    {{-- GRAPHS --}}
    <div class="grid gap-4 xl:grid-cols-3">

        {{-- SALES TREND --}}
        <section class="surface rounded-xl p-4 xl:col-span-2">

            <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-sm font-extrabold">Sales Trend</h2>
                    <p class="muted mt-1 text-[11px]">
                        {{ $fromC->format('d M Y') }} – {{ $toC->format('d M Y') }} · completed orders
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <div class="seg" id="groupSeg">
                        <button type="button" data-group="daily"   class="{{ $useMonthly ? '' : 'on' }}">Daily</button>
                        <button type="button" data-group="monthly" class="{{ $useMonthly ? 'on' : '' }}">Monthly</button>
                    </div>
                    <div class="seg" id="typeSeg">
                        <button type="button" data-type="line" class="on"><i class="fas fa-chart-area"></i></button>
                        <button type="button" data-type="bar"><i class="fas fa-chart-bar"></i></button>
                    </div>
                </div>
            </div>

            {{-- mini stats --}}
            <div class="mini-grid mb-4">
                <div class="mini-stat">
                    <p class="muted">Total</p>
                    <strong>Rs. {{ number_format($sumSales, 0) }}</strong>
                </div>
                <div class="mini-stat">
                    <p class="muted">Avg / Day</p>
                    <strong>Rs. {{ number_format($avgPerDay, 0) }}</strong>
                </div>
                <div class="mini-stat">
                    <p class="muted">Best Day</p>
                    <strong>
                        @if($bestDayKey)
                            Rs. {{ number_format($bestDayValue, 0) }}
                            <span class="muted block text-[9px] font-medium">{{ \Carbon\Carbon::parse($bestDayKey)->format('d M') }}</span>
                        @else — @endif
                    </strong>
                </div>
            </div>

            <div class="chart-box">
                <canvas id="salesChart"></canvas>
            </div>

            @if($sumSales <= 0)
                <p class="muted mt-3 text-center text-[11px]">No completed sales in this date range.</p>
            @endif
        </section>

        {{-- ORDER STATUS --}}
        <section class="surface rounded-xl p-4">
            <div class="mb-4">
                <h2 class="text-sm font-extrabold">Order Status</h2>
                <p class="muted mt-1 text-[11px]">Share of orders in selected range</p>
            </div>

            <div class="chart-box chart-box--donut">
                <canvas id="statusChart"></canvas>
            </div>

            <div class="mt-4 space-y-2 text-xs">
                <div class="flex items-center justify-between">
                    <span><i class="legend-dot" style="background:#16a34a"></i> Completed</span>
                    <strong>{{ number_format((int)$completedOrders) }}</strong>
                </div>
                <div class="flex items-center justify-between">
                    <span><i class="legend-dot" style="background:#f59e0b"></i> Pending</span>
                    <strong>{{ number_format((int)$pendingOrders) }}</strong>
                </div>
                <div class="flex items-center justify-between">
                    <span><i class="legend-dot" style="background:#ef4444"></i> Cancelled</span>
                    <strong>{{ number_format((int)$cancelledOrders) }}</strong>
                </div>
            </div>
        </section>

    </div>

    {{-- BREAKDOWN + RECENT ORDERS --}}
    <div class="grid gap-4 xl:grid-cols-2">

        {{-- SALES BREAKDOWN --}}
        <section class="surface rounded-xl p-4">

            <div class="mb-4 flex items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm font-extrabold">{{ $useMonthly ? 'Monthly' : 'Daily' }} Sales Breakdown</h2>
                    <p class="muted mt-1 text-[11px]">Completed orders sales performance</p>
                </div>

                <span class="rounded-lg bg-amber-500/10 px-2.5 py-1.5 text-[10px] font-bold gold">
                    <i class="fas fa-chart-bar mr-1"></i> Sales
                </span>
            </div>

            <div class="max-h-[420px] space-y-4 overflow-y-auto pr-1">

                @forelse($breakdown as $row)
                    @php $pct = min(100, ($row['value'] / $maxBreak) * 100); @endphp

                    <div>
                        <div class="mb-1.5 flex justify-between gap-2 text-xs">
                            <span class="muted">{{ $row['label'] }}</span>
                            <strong>Rs. {{ number_format($row['value'], 2) }}</strong>
                        </div>

                        <div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                            <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-yellow-600"
                                style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="muted py-8 text-center text-xs">No completed sales in this date range.</p>
                @endforelse

            </div>
        </section>

        {{-- RECENT ORDERS --}}
        <section class="surface overflow-hidden rounded-xl">

            <div class="border-b border-gray-200 p-4 dark:border-gray-700">
                <h2 class="text-sm font-extrabold">Recent Orders</h2>
                <p class="muted mt-1 text-[11px]">Orders within selected date range</p>
            </div>

            <div class="overflow-x-auto">
                <table class="admin-table w-full min-w-[480px] text-left text-xs">

                    <thead>
                        <tr>
                            <th class="px-3 py-3">Order</th>
                            <th class="px-3 py-3">Customer</th>
                            <th class="px-3 py-3">Total</th>
                            <th class="px-3 py-3">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td class="px-3 py-3 font-semibold">{{ $order->order_number }}</td>
                                <td class="px-3 py-3">{{ $order->customer?->name ?? 'Guest' }}</td>
                                <td class="px-3 py-3 font-semibold">Rs. {{ number_format((float)$order->total,2) }}</td>
                                <td class="px-3 py-3">
                                    <span class="rounded-full bg-amber-500/10 px-2 py-1 text-[10px] font-bold capitalize gold">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="muted px-3 py-8 text-center">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-3 dark:border-gray-700">
                <p class="muted text-[10px]">
                    <i class="fas fa-credit-card mr-1"></i>
                    {{ $totalPayments }} payment(s) received between {{ $from }} and {{ $to }}.
                </p>
            </div>

        </section>

    </div>

    {{-- FOOTER --}}
    <div class="surface rounded-xl p-4">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-sm font-bold">Report Summary</h3>
                <p class="muted mt-1 text-[11px]">Restaurant performance for selected period.</p>
            </div>

            <span class="gold text-[10px] font-bold">
                <i class="fas fa-shield-alt mr-1"></i> Royal Restaurant Analytics
            </span>
        </div>
    </div>

</div>

{{-- STYLING --}}
<style>
.reports-page { min-width: 0; max-width: 100%; }
.reports-page .surface { border-radius: 12px; }

.reports-page .admin-add-btn {
    font-size: 12px !important;
    padding: 9px 13px !important;
    border-radius: 8px;
}

/* Quick range chips */
.reports-page .range-chip {
    font-size: 11px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 999px;
    border: 1px solid rgba(212,154,54,.35);
    background: transparent;
    cursor: pointer;
    transition: all .15s ease;
}
.reports-page .range-chip:hover { background: rgba(212,154,54,.12); }
.reports-page .range-chip.is-active {
    background: linear-gradient(145deg,#ffe2a0,#d49a36);
    color: #172338;
    border-color: transparent;
}

/* Filter layout */
.reports-page .filter-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    align-items: end;
}
.reports-page .filter-grid > button,
.reports-page .filter-grid > a { min-height: 38px; }
@media (min-width: 1024px) {
    .reports-page .filter-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
}

/* Date inputs: white, readable, never overflow */
.reports-page input.date-input {
    display: block;
    width: 100%;
    min-width: 0;
    max-width: 100%;
    min-height: 40px;
    padding: 8px 10px;
    font-size: 12px;
    box-sizing: border-box;
    background-color: #ffffff !important;
    color: #172338 !important;
    border: 1px solid #e3d9c6 !important;
    border-radius: 8px;
    color-scheme: light;
    -webkit-appearance: none;
    appearance: none;
}
.reports-page input.date-input::-webkit-calendar-picker-indicator { cursor: pointer; opacity: .8; }

.dark .reports-page input.date-input,
html[data-theme="dark"] .reports-page input.date-input,
body.dark .reports-page input.date-input {
    background-color: #1f2a3d !important;
    color: #f5f5f5 !important;
    border-color: #3a475e !important;
    color-scheme: dark;
}

/* Segmented toggles */
.reports-page .seg {
    display: inline-flex;
    padding: 3px;
    gap: 2px;
    border-radius: 10px;
    background: rgba(212,154,54,.12);
}
.reports-page .seg button {
    font-size: 11px;
    font-weight: 700;
    padding: 5px 11px;
    border-radius: 8px;
    cursor: pointer;
    background: transparent;
    transition: all .15s ease;
}
.reports-page .seg button.on {
    background: linear-gradient(145deg,#ffe2a0,#d49a36);
    color: #172338;
    box-shadow: 0 2px 6px rgba(212,154,54,.35);
}

/* Mini stats */
.reports-page .mini-stat {
    padding: 10px 12px;
    border-radius: 10px;
    background: rgba(212,154,54,.08);
    min-width: 0;
}
.reports-page .mini-stat p { font-size: 10px; }
.reports-page .mini-stat strong { display: block; margin-top: 2px; font-size: 13px; word-break: break-word; }

/* Charts */
.reports-page .chart-box { position: relative; height: 280px; width: 100%; }
.reports-page .chart-box--donut { height: 210px; }
.reports-page .legend-dot {
    display: inline-block; width: 9px; height: 9px; border-radius: 50%; margin-right: 6px;
}

.reports-page .admin-table th,
.reports-page .admin-table td { padding: 9px 12px; font-size: 11px; }

@media (max-width: 640px) {
    .reports-page { gap: 14px; }
    .reports-page .surface { border-radius: 10px; }
    .reports-page .date-input { font-size: 16px; }
    .reports-page .chart-box { height: 240px; }
}

/* ---------- Mobile responsiveness fixes ---------- */
.reports-page .grid > *,
.reports-page section,
.reports-page .surface { min-width: 0; }

.reports-page .mini-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 8px;
}

.reports-page .chart-box { overflow: hidden; }
.reports-page .chart-box canvas { max-width: 100% !important; }
.reports-page .chart-box--donut { max-width: 240px; margin-inline: auto; }

@media (max-width: 639px) {
    /* single column that can shrink: nothing can push the page wider than the screen */
    .reports-page .grid { grid-template-columns: minmax(0, 1fr); }

    .reports-page .mini-grid { gap: 6px; }
    .reports-page .mini-stat { padding: 8px 8px; }
    .reports-page .mini-stat p { font-size: 9px; }
    .reports-page .mini-stat strong { font-size: 11px; }

    .reports-page .seg button { padding: 5px 9px; }
    .reports-page .range-chip { padding: 5px 10px; }
    .reports-page .chart-box { height: 230px; }
    .reports-page .chart-box--donut { height: 190px; }
}

/* ---------- Custom calendar (flatpickr) - same look as the category dropdown ---------- */
.reports-page input.date-input.flatpickr-input,
.reports-page input.date-input[readonly] {
    cursor: pointer;
    padding-right: 34px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23d49a36' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2'/%3E%3Cpath d='M16 2v4M8 2v4M3 10h18'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 14px;
}
.reports-page input.date-input:focus { outline: none; border-color: #d49a36 !important; box-shadow: 0 0 0 3px rgba(212,154,54,.18); }

.flatpickr-calendar {
    font-family: inherit;
    border-radius: 16px;
    border: 1px solid #efe6d3;
    box-shadow: 0 14px 34px rgba(23,35,56,.18);
    background: #fff;
    padding-bottom: 4px;
    z-index: 99999 !important;
}
.flatpickr-calendar:before, .flatpickr-calendar:after { display: none; }
.flatpickr-months { padding: 8px 6px 0; }
.flatpickr-months .flatpickr-month,
.flatpickr-current-month .flatpickr-monthDropdown-months,
.flatpickr-current-month input.cur-year { color: #172338; fill: #172338; font-weight: 800; font-size: 14px; }
.flatpickr-months .flatpickr-prev-month svg,
.flatpickr-months .flatpickr-next-month svg { fill: #172338; width: 12px; height: 12px; }
.flatpickr-months .flatpickr-prev-month:hover svg,
.flatpickr-months .flatpickr-next-month:hover svg { fill: #d49a36; }
span.flatpickr-weekday { color: #b9812a; font-weight: 700; font-size: 11px; }
.flatpickr-day { border-radius: 10px; color: #172338; font-weight: 500; }
.flatpickr-day:hover { background: rgba(212,154,54,.16); border-color: transparent; }
.flatpickr-day.today { border-color: #d49a36; }
.flatpickr-day.today:hover { background: rgba(212,154,54,.16); color: #172338; }
.flatpickr-day.selected,
.flatpickr-day.selected:hover {
    background: linear-gradient(145deg,#ffe2a0,#d49a36);
    border-color: transparent;
    color: #172338;
    font-weight: 800;
    box-shadow: 0 3px 8px rgba(212,154,54,.4);
}
.flatpickr-day.flatpickr-disabled,
.flatpickr-day.flatpickr-disabled:hover,
.flatpickr-day.prevMonthDay, .flatpickr-day.nextMonthDay { color: rgba(23,35,56,.28); }
.flatpickr-day.flatpickr-disabled { background: transparent; cursor: not-allowed; }

.flatpickr-calendar .fp-footer {
    display: flex; justify-content: space-between;
    padding: 8px 16px 10px; margin-top: 4px;
    border-top: 1px solid #f1e9d8;
}
.flatpickr-calendar .fp-footer button {
    font-size: 12px; font-weight: 700; color: #b9812a;
    background: transparent; border: 0; cursor: pointer; padding: 4px 2px;
}
.flatpickr-calendar .fp-footer button:hover { color: #d49a36; text-decoration: underline; }

/* dark admin theme */
.dark .flatpickr-calendar,
html[data-theme="dark"] .flatpickr-calendar,
body.dark .flatpickr-calendar { background: #1f2a3d; border-color: #3a475e; }
.dark .flatpickr-months .flatpickr-month,
.dark .flatpickr-current-month .flatpickr-monthDropdown-months,
.dark .flatpickr-current-month input.cur-year,
.dark .flatpickr-day,
html[data-theme="dark"] .flatpickr-day,
body.dark .flatpickr-day { color: #f5f5f5; fill: #f5f5f5; }
.dark .flatpickr-months .flatpickr-prev-month svg,
.dark .flatpickr-months .flatpickr-next-month svg { fill: #f5f5f5; }
.dark .flatpickr-day.flatpickr-disabled,
.dark .flatpickr-day.prevMonthDay,
.dark .flatpickr-day.nextMonthDay { color: rgba(245,245,245,.3); }
.dark .flatpickr-day.selected { color: #172338; }
.dark .flatpickr-calendar .fp-footer { border-top-color: #3a475e; }
.dark .flatpickr-current-month .flatpickr-monthDropdown-months { background: #1f2a3d; }

@media (max-width: 639px) {
    .flatpickr-calendar { max-width: calc(100vw - 20px); }
}

@media print {
    aside, nav, header, button, form, .seg { display: none !important; }
    body { background: white !important; color: black !important; }
    .surface { box-shadow: none !important; border: 1px solid #ddd !important; break-inside: avoid; }
    main { width: 100% !important; margin: 0 !important; padding: 10px !important; }
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
(function () {
    /* ---------------- Date filter ---------------- */
    const from = document.getElementById('from');
    const to   = document.getElementById('to');
    const form = document.getElementById('reportFilter');

    function fmt(d) {
        const p = n => String(n).padStart(2, '0');
        return d.getFullYear() + '-' + p(d.getMonth() + 1) + '-' + p(d.getDate());
    }

    function rangeFor(key) {
        const now = new Date();
        let start = new Date(now);
        if (key === '7')     start.setDate(now.getDate() - 6);
        if (key === 'month') start = new Date(now.getFullYear(), now.getMonth(), 1);
        if (key === '6m')    start = new Date(now.getFullYear(), now.getMonth() - 5, 1);
        if (key === '12m')   start = new Date(now.getFullYear(), now.getMonth() - 11, 1);
        return { from: fmt(start), to: fmt(now) };
    }

    // highlight the active quick-range chip (before the pickers touch the inputs)
    document.querySelectorAll('[data-range]').forEach(function (btn) {
        const r = rangeFor(btn.dataset.range);
        if (from.value === r.from && to.value === r.to) btn.classList.add('is-active');
    });

    let fpFrom = null, fpTo = null;

    function addFooter(fp) {
        const f = document.createElement('div');
        f.className = 'fp-footer';
        f.innerHTML = '<button type="button" data-a="clear">Clear</button><button type="button" data-a="today">Today</button>';
        fp.calendarContainer.appendChild(f);
        f.addEventListener('click', function (e) {
            const b = e.target.closest('button'); if (!b) return;
            if (b.dataset.a === 'clear') fp.clear(); else fp.setDate(new Date(), true);
            fp.close();
        });
    }

    const baseOpts = {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd M Y',
        altInputClass: 'admin-form-control date-input w-full',
        disableMobile: true,      // use the styled calendar on phones too
        allowInput: false,
        animate: true,
        onReady: function (sel, str, fp) { addFooter(fp); }
    };

    if (typeof flatpickr !== 'undefined') {
        fpFrom = flatpickr(from, Object.assign({}, baseOpts, {
            defaultDate: from.value || null,
            maxDate: to.value || null,
            onChange: function (sel, str) { if (fpTo) fpTo.set('minDate', str || null); }
        }));
        fpTo = flatpickr(to, Object.assign({}, baseOpts, {
            defaultDate: to.value || null,
            minDate: from.value || null,
            onChange: function (sel, str) { if (fpFrom) fpFrom.set('maxDate', str || null); }
        }));
    } else {
        // fallback: native date inputs
        const link = function () {
            if (to.value)   from.max = to.value;
            if (from.value) to.min   = from.value;
        };
        from.addEventListener('change', link);
        to.addEventListener('change', link);
        link();
    }

    document.querySelectorAll('[data-range]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const r = rangeFor(btn.dataset.range);
            if (fpFrom && fpTo) {
                fpFrom.set('maxDate', null); fpTo.set('minDate', null);
                fpFrom.setDate(r.from, false);
                fpTo.setDate(r.to, false);
            } else {
                from.max = ''; to.min = '';
                from.value = r.from; to.value = r.to;
            }
            form.submit();
        });
    });

    /* ---------------- Charts ---------------- */
    if (typeof Chart === 'undefined') return;

    const DATA = {
        daily:   { labels: @json($dailyLabels),   values: @json($dailyValues) },
        monthly: { labels: @json($monthlyLabels), values: @json($monthlyValues) }
    };
    const STATUS = {
        completed: {{ (int) $completedOrders }},
        pending:   {{ (int) $pendingOrders }},
        cancelled: {{ (int) $cancelledOrders }}
    };

    let group = @json($useMonthly ? 'monthly' : 'daily');
    let type  = 'line';
    let salesChart = null, statusChart = null;

    function isDark() {
        return document.documentElement.classList.contains('dark') ||
               document.body.classList.contains('dark') ||
               document.documentElement.getAttribute('data-theme') === 'dark';
    }
    function theme() {
        return isDark()
            ? { text: '#aab4c5', grid: 'rgba(255,255,255,.07)', tipBg: '#0f1726', tipText: '#fff', ring: '#1f2a3d' }
            : { text: '#6b7280', grid: 'rgba(23,35,56,.07)',   tipBg: '#172338', tipText: '#fff', ring: '#ffffff' };
    }
    function money(v) { return 'Rs. ' + Number(v).toLocaleString(undefined, { maximumFractionDigits: 2 }); }
    function short(v) { return v >= 1000000 ? (v/1000000).toFixed(1) + 'M' : v >= 1000 ? (v/1000).toFixed(v >= 10000 ? 0 : 1) + 'k' : v; }

    function buildSales() {
        const canvas = document.getElementById('salesChart');
        const ctx = canvas.getContext('2d');
        const t = theme();
        const set = DATA[group];

        const grad = ctx.createLinearGradient(0, 0, 0, canvas.parentElement.clientHeight || 280);
        if (type === 'line') {
            grad.addColorStop(0, 'rgba(212,154,54,.45)');
            grad.addColorStop(1, 'rgba(212,154,54,0)');
        } else {
            grad.addColorStop(0, '#ffe2a0');
            grad.addColorStop(1, '#d49a36');
        }

        if (salesChart) salesChart.destroy();
        salesChart = new Chart(ctx, {
            type: type,
            data: {
                labels: set.labels,
                datasets: [{
                    label: 'Sales',
                    data: set.values,
                    borderColor: '#d49a36',
                    backgroundColor: grad,
                    borderWidth: type === 'line' ? 2.5 : 0,
                    fill: type === 'line',
                    tension: 0.4,
                    pointRadius: type === 'line' && set.values.length <= 31 ? 3 : 0,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#d49a36',
                    pointBorderColor: t.ring,
                    pointBorderWidth: 2,
                    borderRadius: 8,
                    maxBarThickness: 38
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                animation: { duration: 700, easing: 'easeOutQuart' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: t.tipBg, titleColor: t.tipText, bodyColor: t.tipText,
                        padding: 10, cornerRadius: 10, displayColors: false,
                        callbacks: { label: c => money(c.parsed.y) }
                    }
                },
                scales: {
                    x: { grid: { display: false }, border: { display: false },
                         ticks: { color: t.text, font: { size: 10 }, maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } },
                    y: { beginAtZero: true, border: { display: false }, grid: { color: t.grid },
                         ticks: { color: t.text, font: { size: 10 }, maxTicksLimit: 5, callback: v => short(v) } }
                }
            }
        });
    }

    function buildStatus() {
        const t = theme();
        const total = STATUS.completed + STATUS.pending + STATUS.cancelled;
        const empty = total === 0;

        const centerText = {
            id: 'centerText',
            afterDraw(chart) {
                const { ctx, chartArea: a } = chart;
                const x = (a.left + a.right) / 2, y = (a.top + a.bottom) / 2;
                ctx.save();
                ctx.textAlign = 'center';
                ctx.fillStyle = isDark() ? '#fff' : '#172338';
                ctx.font = '800 24px sans-serif';
                ctx.fillText(total, x, y + 4);
                ctx.fillStyle = t.text;
                ctx.font = '600 10px sans-serif';
                ctx.fillText('ORDERS', x, y + 20);
                ctx.restore();
            }
        };

        if (statusChart) statusChart.destroy();
        statusChart = new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: empty ? ['No orders'] : ['Completed', 'Pending', 'Cancelled'],
                datasets: [{
                    data: empty ? [1] : [STATUS.completed, STATUS.pending, STATUS.cancelled],
                    backgroundColor: empty ? ['rgba(150,150,150,.25)'] : ['#16a34a', '#f59e0b', '#ef4444'],
                    borderColor: t.ring, borderWidth: 3, borderRadius: 6, hoverOffset: 6
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: !empty, backgroundColor: t.tipBg, titleColor: t.tipText, bodyColor: t.tipText,
                               padding: 10, cornerRadius: 10 }
                }
            },
            plugins: [centerText]
        });
    }

    function setActive(seg, attr, val) {
        seg.querySelectorAll('button').forEach(b => b.classList.toggle('on', b.dataset[attr] === val));
    }

    document.getElementById('groupSeg').addEventListener('click', function (e) {
        const b = e.target.closest('button'); if (!b) return;
        group = b.dataset.group; setActive(this, 'group', group); buildSales();
    });
    document.getElementById('typeSeg').addEventListener('click', function (e) {
        const b = e.target.closest('button'); if (!b) return;
        type = b.dataset.type; setActive(this, 'type', type); buildSales();
    });

    buildSales();
    buildStatus();

    // Re-draw when the admin theme (dark/light) is toggled
    new MutationObserver(function () { buildSales(); buildStatus(); })
        .observe(document.documentElement, { attributes: true, attributeFilter: ['class', 'data-theme'] });
    new MutationObserver(function () { buildSales(); buildStatus(); })
        .observe(document.body, { attributes: true, attributeFilter: ['class'] });
})();
</script>

@endsection