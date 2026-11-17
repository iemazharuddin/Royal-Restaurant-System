@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-heading', 'Admin Overview')

@section('content')

@php
    // ---- Reservation numbers: counted directly from the database (case-insensitive status) ----
    $byStatus = \App\Models\Reservation::pluck('status')
        ->map(fn ($s) => strtolower(trim((string) $s)))
        ->countBy();

    $totalReservations     = (int) $byStatus->sum();
    $pendingReservations   = (int) (($byStatus['pending'] ?? 0) + ($byStatus[''] ?? 0)); // empty status = pending
    $confirmedReservations = (int) ($byStatus['confirmed'] ?? 0);
    $completedReservations = (int) ($byStatus['completed'] ?? 0);
    $cancelledReservations = (int) (($byStatus['cancelled'] ?? 0) + ($byStatus['canceled'] ?? 0));

    // ---- Other stats (from controller) ----
    $totalCategories = $stats['categories'] ?? 0;
    $totalMenuItems  = $stats['menu_items'] ?? 0;
    $totalContacts   = $stats['contacts'] ?? 0;
    $totalCustomers  = $stats['customers'] ?? 0;
    $totalOrders     = $stats['orders'] ?? 0;
    $totalRevenue    = $stats['revenue'] ?? 0;
    $totalReviews    = $stats['reviews'] ?? 0;

    $completionRate = $totalReservations > 0
        ? round(($completedReservations / $totalReservations) * 100)
        : 0;

    $pendingPercent   = $totalReservations > 0 ? ($pendingReservations / $totalReservations) * 100 : 0;
    $confirmedPercent = $totalReservations > 0 ? ($confirmedReservations / $totalReservations) * 100 : 0;
    $completedPercent = $totalReservations > 0 ? ($completedReservations / $totalReservations) * 100 : 0;
    $cancelledPercent = $totalReservations > 0 ? ($cancelledReservations / $totalReservations) * 100 : 0;

    // ---- Last 6 months: sales (completed orders) + order count, for the graph ----
    $months = [];
    for ($i = 5; $i >= 0; $i--) {
        $m = now()->copy()->startOfMonth()->subMonthsNoOverflow($i);
        $months[$m->format('Y-m')] = ['label' => $m->format('M Y'), 'sales' => 0.0, 'orders' => 0];
    }
    try {
        $orderRows = \App\Models\Order::where('created_at', '>=', now()->copy()->startOfMonth()->subMonthsNoOverflow(5))
            ->get(['total', 'status', 'created_at']);
    } catch (\Throwable $e) {
        $orderRows = collect();
    }
    foreach ($orderRows as $o) {
        $k = optional($o->created_at)->format('Y-m');
        if (!$k || !isset($months[$k])) continue;
        $months[$k]['orders']++;
        if (strtolower((string) $o->status) === 'completed') {
            $months[$k]['sales'] += (float) $o->total;
        }
    }
    $chartLabels = array_column($months, 'label');
    $chartSales  = array_map(fn ($m) => round($m['sales'], 2), array_values($months));
    $chartOrders = array_column($months, 'orders');

    $keys       = array_keys($months);
    $thisMonth  = $months[end($keys)]['sales'];
    $lastMonth  = $months[$keys[count($keys) - 2]]['sales'];
    $growth     = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : null;
    $sixMonthSales = array_sum(array_column($months, 'sales'));
    $sixMonthOrders = array_sum(array_column($months, 'orders'));

    $mainCards = [
        ['Total Reservations', number_format((int) $totalReservations), 'fa-calendar-check'],
        ['Orders',             number_format((int) $totalOrders),       'fa-receipt'],
        ['Customers',          number_format((int) $totalCustomers),    'fa-users'],
        ['Revenue (Rs.)',      number_format((float) $totalRevenue, 2), 'fa-coins'],
    ];
    $subCards = [
        ['Menu Items',       $totalMenuItems,  'fa-utensils'],
        ['Categories',       $totalCategories, 'fa-layer-group'],
        ['Contact Messages', $totalContacts,   'fa-envelope'],
        ['Reviews',          $totalReviews,    'fa-star'],
    ];
@endphp

<div class="space-y-5 dash-page">
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <p class="gold text-[10px] font-bold uppercase tracking-[0.2em]">Dashboard</p>
            <h1 class="mt-1 text-2xl font-extrabold">Welcome to Royal Restaurant</h1>
            <p class="muted mt-1 text-xs">Manage your restaurant, reservations and menu from one place.</p>
        </div>

        <a href="{{ route('admin.reports.index') }}" class="admin-add-btn rounded-lg px-3 py-2 text-center text-xs font-semibold">
            <i class="fas fa-chart-line mr-1"></i> Full Reports </a>
    </div>

    <div class="grid grid-cols-2 gap-2.5 sm:gap-3 xl:grid-cols-4">
        @foreach($mainCards as [$label, $value, $icon])
            <div class="surface rounded-xl p-3 sm:p-4">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="muted text-[11px] sm:text-xs">{{ $label }}</p>
                        <h3 class="mt-2 truncate text-base font-extrabold sm:text-lg">{{ $value }}</h3>
                    </div>

                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-sm text-[#172338]" style="background:linear-gradient(145deg,#ffe2a0,#d49a36)">
                        <i class="fas {{ $icon }}"></i>
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-2 gap-2.5 sm:gap-3 xl:grid-cols-4">
        @foreach($subCards as [$label, $value, $icon])
            <div class="surface rounded-xl p-3 sm:p-4">
                <div class="flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="muted text-[11px] sm:text-xs">{{ $label }}</p>
                        <h3 class="mt-2 text-base font-extrabold sm:text-lg">{{ number_format((int) $value) }}</h3>
                    </div>

                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-500/10 text-sm text-amber-500">
                        <i class="fas {{ $icon }}"></i>
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- GRAPHS --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <section class="surface rounded-xl p-4 lg:col-span-2">
            <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-sm font-extrabold">Sales Overview</h2>
                    <p class="muted mt-1 text-[11px]">Last 6 months</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <div class="seg" id="metricSeg">
                        <button type="button" data-metric="sales" class="on">Sales</button>
                        <button type="button" data-metric="orders">Orders</button>
                    </div>
                    <div class="seg" id="typeSeg">
                        <button type="button" data-type="line" class="on"><i class="fas fa-chart-area"></i></button>
                        <button type="button" data-type="bar"><i class="fas fa-chart-bar"></i></button>
                    </div>
                </div>
            </div>

            <div class="mini-grid mb-4">
                <div class="mini-stat">
                    <p class="muted">6 Month Sales</p>
                    <strong>Rs. {{ number_format($sixMonthSales, 0) }}</strong>
                </div>
                <div class="mini-stat">
                    <p class="muted">6 Month Orders</p>
                    <strong>{{ number_format($sixMonthOrders) }}</strong>
                </div>
                <div class="mini-stat">
                    <p class="muted">vs Last Month</p>
                    <strong class="{{ $growth === null ? '' : ($growth >= 0 ? 'text-emerald-500' : 'text-red-500') }}">
                        @if($growth === null) — @else {{ $growth >= 0 ? '+' : '' }}{{ number_format($growth, 0) }}% @endif
                    </strong>
                </div>
            </div>

            <div class="chart-box">
                <canvas id="salesChart"></canvas>
            </div>
        </section>

        {{-- RESERVATION STATUS --}}
        <section class="surface rounded-xl p-4">
            <div class="mb-4">
                <h2 class="text-sm font-extrabold">Reservation Status</h2>
                <p class="muted mt-1 text-[11px]">Share of all reservations</p>
            </div>

            <div class="chart-box chart-box--donut">
                <canvas id="reservationChart"></canvas>
            </div>

            <div class="mt-4 space-y-2 text-xs">
                @foreach([
                    ['Pending',   $pendingReservations,   '#f59e0b'],
                    ['Confirmed', $confirmedReservations, '#0d9488'],
                    ['Completed', $completedReservations, '#16a34a'],
                    ['Cancelled', $cancelledReservations, '#ef4444'],
                ] as [$l, $v, $c])
                    <div class="flex items-center justify-between">
                        <span><i class="legend-dot" style="background:{{ $c }}"></i> {{ $l }}</span>
                        <strong>{{ number_format($v) }}</strong>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    {{-- RESERVATION OVERVIEW + COMPLETION --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <section class="surface rounded-xl p-4 lg:col-span-2">
            <div class="mb-4">
                <h2 class="text-sm font-extrabold">Reservation Overview</h2>
                <p class="muted mt-1 text-[11px]">Current reservation statistics</p>
            </div>

            <div class="space-y-4">
                @foreach([
                    ['Pending',   $pendingReservations,   $pendingPercent,   '#f59e0b'],
                    ['Confirmed', $confirmedReservations, $confirmedPercent, '#0d9488'],
                    ['Completed', $completedReservations, $completedPercent, '#16a34a'],
                    ['Cancelled', $cancelledReservations, $cancelledPercent, '#ef4444'],
                ] as $row)
                    <div>
                        <div class="mb-1.5 flex justify-between text-xs">
                            <span class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full" style="background:{{ $row[3] }}"></span>
                                {{ $row[0] }}
                            </span>
                            <strong>{{ $row[1] }}</strong>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                            <div class="h-full rounded-full" style="width:{{ $row[2] }}%;background:{{ $row[3] }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="surface rounded-xl p-4">
            <h2 class="text-sm font-extrabold">Completion Rate</h2>
            <p class="muted mt-1 text-[11px]">Completed reservations</p>

            <div class="flex items-center justify-center py-5">
                <div class="ring-track flex h-28 w-28 items-center justify-center rounded-full"
                     style="background-image:conic-gradient(#d49a36 {{ $completionRate }}%, transparent 0);">
                    <div class="ring-inner flex h-[88px] w-[88px] items-center justify-center rounded-full">
                        <span class="text-2xl font-extrabold">{{ $completionRate }}%</span>
                    </div>
                </div>
            </div>

            <p class="muted text-center text-[11px]">
                {{ $completedReservations }} of {{ $totalReservations }} reservations completed
            </p>
        </section>
    </div>

    {{-- RECENT RESERVATIONS --}}
    <section class="surface overflow-hidden rounded-xl">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 p-4 dark:border-gray-700">
            <div>
                <h2 class="text-sm font-extrabold">Recent Reservations</h2>
                <p class="muted mt-1 text-[11px]">Latest customer bookings</p>
            </div>

            <a href="{{ route('admin.reservations.index') }}" class="gold text-xs font-bold"> View All <i class="fas fa-arrow-right ml-1"></i></a>
        </div>

        <div class="overflow-x-auto">
            <table class="admin-table w-full min-w-[480px] text-left text-xs">
                <thead>
                    <tr>
                        <th class="px-3 py-3">Customer</th>
                        <th class="px-3 py-3">Date</th>
                        <th class="px-3 py-3">Guests</th>
                        <th class="px-3 py-3">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentReservations as $reservation)
                        @php
                            $status = strtolower($reservation->status ?? 'pending');
                            $statusClass = match($status) {
                                'confirmed' => 'bg-teal-500/10 text-teal-600',
                                'completed' => 'bg-green-500/10 text-green-600',
                                'cancelled', 'canceled' => 'bg-red-500/10 text-red-500',
                                default => 'bg-amber-500/10 gold',
                            };
                        @endphp
                        <tr>
                            <td class="px-3 py-3 font-semibold">
                                {{ $reservation->name ?? $reservation->customer_name ?? 'Customer' }}
                            </td>
                            <td class="muted px-3 py-3">
                                {{ $reservation->reservation_date
                                    ? \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y')
                                    : '—' }}
                            </td>
                            <td class="muted px-3 py-3">
                                {{ $reservation->guests ?? $reservation->number_of_guests ?? '—' }}
                            </td>
                            <td class="px-3 py-3">
                                <span class="rounded-full px-2 py-1 text-[10px] font-bold capitalize {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted px-3 py-8 text-center">
                                <i class="fas fa-calendar-times mb-2 block text-2xl"></i>
                                No reservations found yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- RECENT ORDERS --}}
    <section class="surface overflow-hidden rounded-xl">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 p-4 dark:border-gray-700">
            <div>
                <h2 class="text-sm font-extrabold">Recent Orders</h2>
                <p class="muted mt-1 text-[11px]">Latest customer orders</p>
            </div>

            <a href="{{ route('admin.orders.index') }}" class="gold text-xs font-bold">View Orders <i class="fas fa-arrow-right ml-1"></i></a>
        </div>

        <div class="overflow-x-auto">
            <table class="admin-table w-full min-w-[480px] text-left text-xs">
                <thead>
                    <tr>
                        <th class="px-3 py-3">Order</th>
                        <th class="px-3 py-3">Customer</th>
                        <th class="px-3 py-3">Amount</th>
                        <th class="px-3 py-3">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="px-3 py-3 font-semibold">{{ $order->order_number }}</td>
                            <td class="px-3 py-3">{{ $order->customer?->name ?? 'Guest' }}</td>
                            <td class="px-3 py-3 font-semibold">Rs. {{ number_format((float) $order->total, 2) }}</td>
                            <td class="px-3 py-3">
                                <span class="rounded-full bg-amber-500/10 px-2 py-1 text-[10px] font-bold capitalize gold">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="muted px-3 py-8 text-center">No orders found yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- MENU ITEMS --}}
    <section class="surface rounded-xl p-4">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
            <div>
                <h2 class="text-sm font-extrabold">Menu Items</h2>
                <p class="muted mt-1 text-[11px]">Recently added restaurant dishes</p>
            </div>

            <a href="{{ route('admin.menu-items.index') }}" class="gold text-xs font-bold"> Manage Menu <i class="fas fa-arrow-right ml-1"></i></a>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($popularMenuItems as $item)
                <div class="menu-tile flex items-center gap-3 rounded-xl border border-gray-200 p-3 dark:border-gray-700">

                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                             class="h-12 w-12 shrink-0 rounded-lg object-cover">
                    @else
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg text-sm text-[#172338]"
                              style="background:linear-gradient(145deg,#ffe2a0,#d49a36)">
                            <i class="fas fa-utensils"></i>
                        </span>
                    @endif

                    <div class="min-w-0">
                        <h4 class="truncate text-[13px] font-bold">{{ $item->name }}</h4>
                        <p class="muted mt-0.5 text-[11px]">{{ $item->category?->name ?? 'Uncategorized' }}</p>
                        <p class="gold mt-1 text-xs font-bold">Rs. {{ number_format((float) ($item->price ?? 0), 2) }}</p>
                    </div>
                </div>
            @empty
                <div class="muted col-span-full py-6 text-center text-xs">No menu items available.</div>
            @endforelse
        </div>
    </section>

</div>

{{-- STYLING --}}
<style>
.dash-page { min-width: 0; max-width: 100%; }
.dash-page .surface { border-radius: 12px; }
.dash-page .grid > *, .dash-page section, .dash-page .surface { min-width: 0; }

.dash-page .admin-add-btn {
    font-size: 12px !important;
    padding: 9px 13px !important;
    border-radius: 8px;
}

.dash-page .admin-table th,
.dash-page .admin-table td { padding: 9px 12px; font-size: 11px; }

.dash-page .menu-tile { transition: border-color .15s ease, transform .15s ease; }
.dash-page .menu-tile:hover { border-color: rgba(212,154,54,.6); transform: translateY(-1px); }

/* Segmented toggles */
.dash-page .seg { display: inline-flex; padding: 3px; gap: 2px; border-radius: 10px; background: rgba(212,154,54,.12); }
.dash-page .seg button {
    font-size: 11px; font-weight: 700; padding: 5px 11px; border-radius: 8px;
    cursor: pointer; background: transparent; transition: all .15s ease;
}
.dash-page .seg button.on {
    background: linear-gradient(145deg,#ffe2a0,#d49a36);
    color: #172338;
    box-shadow: 0 2px 6px rgba(212,154,54,.35);
}

/* Mini stats */
.dash-page .mini-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; }
.dash-page .mini-stat { padding: 10px 12px; border-radius: 10px; background: rgba(212,154,54,.08); min-width: 0; }
.dash-page .mini-stat p { font-size: 10px; }
.dash-page .mini-stat strong { display: block; margin-top: 2px; font-size: 13px; word-break: break-word; }

/* Charts */
.dash-page .chart-box { position: relative; height: 280px; width: 100%; overflow: hidden; }
.dash-page .chart-box canvas { max-width: 100% !important; }
.dash-page .chart-box--donut { height: 210px; max-width: 240px; margin-inline: auto; }
.dash-page .legend-dot { display: inline-block; width: 9px; height: 9px; border-radius: 50%; margin-right: 6px; }

/* Completion ring */
.dash-page .ring-track { background-color: rgba(212,154,54,.18); }
.dash-page .ring-inner { background: #ffffff; }
.dark .dash-page .ring-inner,
html[data-theme="dark"] .dash-page .ring-inner,
body.dark .dash-page .ring-inner { background: #1f2a3d; }

@media (max-width: 639px) {
    .dash-page .mini-grid { gap: 6px; }
    .dash-page .mini-stat { padding: 8px; }
    .dash-page .mini-stat p { font-size: 9px; }
    .dash-page .mini-stat strong { font-size: 11px; }
    .dash-page .seg button { padding: 5px 9px; }
    .dash-page .chart-box { height: 230px; }
    .dash-page .chart-box--donut { height: 190px; }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
(function () {
    if (typeof Chart === 'undefined') return;

    const LABELS = @json($chartLabels);
    const SERIES = { sales: @json($chartSales), orders: @json($chartOrders) };
    const RES = {
        pending: {{ (int) $pendingReservations }},
        confirmed: {{ (int) $confirmedReservations }},
        completed: {{ (int) $completedReservations }},
        cancelled: {{ (int) $cancelledReservations }}
    };

    let metric = 'sales', type = 'line';
    let salesChart = null, resChart = null;

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
    function short(v) { return v >= 1000000 ? (v/1000000).toFixed(1) + 'M' : v >= 1000 ? (v/1000).toFixed(v >= 10000 ? 0 : 1) + 'k' : v; }

    function buildSales() {
        const canvas = document.getElementById('salesChart');
        const ctx = canvas.getContext('2d');
        const t = theme();
        const isMoney = metric === 'sales';

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
                labels: LABELS,
                datasets: [{
                    label: isMoney ? 'Sales' : 'Orders',
                    data: SERIES[metric],
                    borderColor: '#d49a36',
                    backgroundColor: grad,
                    borderWidth: type === 'line' ? 2.5 : 0,
                    fill: type === 'line',
                    tension: 0.4,
                    pointRadius: type === 'line' ? 3.5 : 0,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#d49a36',
                    pointBorderColor: t.ring,
                    pointBorderWidth: 2,
                    borderRadius: 8,
                    maxBarThickness: 42
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
                        callbacks: {
                            label: c => isMoney
                                ? 'Rs. ' + Number(c.parsed.y).toLocaleString(undefined, { maximumFractionDigits: 2 })
                                : c.parsed.y + ' orders'
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, border: { display: false },
                         ticks: { color: t.text, font: { size: 10 }, maxRotation: 0 } },
                    y: { beginAtZero: true, border: { display: false }, grid: { color: t.grid },
                         ticks: { color: t.text, font: { size: 10 }, maxTicksLimit: 5, precision: 0,
                                  callback: v => isMoney ? short(v) : v } }
                }
            }
        });
    }

    function buildReservations() {
        const t = theme();
        const total = RES.pending + RES.confirmed + RES.completed + RES.cancelled;
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
                ctx.fillText('BOOKINGS', x, y + 20);
                ctx.restore();
            }
        };

        if (resChart) resChart.destroy();
        resChart = new Chart(document.getElementById('reservationChart'), {
            type: 'doughnut',
            data: {
                labels: empty ? ['No reservations'] : ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
                datasets: [{
                    data: empty ? [1] : [RES.pending, RES.confirmed, RES.completed, RES.cancelled],
                    backgroundColor: empty ? ['rgba(150,150,150,.25)'] : ['#f59e0b', '#0d9488', '#16a34a', '#ef4444'],
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

    document.getElementById('metricSeg').addEventListener('click', function (e) {
        const b = e.target.closest('button'); if (!b) return;
        metric = b.dataset.metric; setActive(this, 'metric', metric); buildSales();
    });
    document.getElementById('typeSeg').addEventListener('click', function (e) {
        const b = e.target.closest('button'); if (!b) return;
        type = b.dataset.type; setActive(this, 'type', type); buildSales();
    });

    buildSales();
    buildReservations();

    // Re-draw when the admin theme (dark/light) is toggled
    const redraw = function () { buildSales(); buildReservations(); };
    new MutationObserver(redraw).observe(document.documentElement, { attributes: true, attributeFilter: ['class', 'data-theme'] });
    new MutationObserver(redraw).observe(document.body, { attributes: true, attributeFilter: ['class'] });
})();
</script>

@endsection