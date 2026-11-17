
@extends('layouts.admin')

@section('title', 'Order Details')
@section('page-heading', 'Order Details')

@section('content')

@php
    $status = strtolower($order->status ?? 'pending');

    $statusStyles = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'processing' => 'bg-blue-100 text-blue-800',
        'completed' => 'bg-green-100 text-green-800',
        'cancelled' => 'bg-red-100 text-red-800',
    ];

    $statusClass = $statusStyles[$status] ?? 'bg-gray-100 text-gray-700';

    $items = is_array($order->items) ? $order->items : [];

    $subtotal = (float) $order->subtotal;
    $discount = (float) $order->discount;
    $tax = (float) $order->tax;
    $total = (float) $order->total;
@endphp

<div class="max-w-6xl mx-auto space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Order Details
            </h2>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Complete information about this restaurant order.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">

            <a href="{{ route('admin.orders.index') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 font-semibold transition">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

            <a href="{{ route('admin.orders.edit', $order) }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold transition">
                <i class="fas fa-edit"></i>
                Edit Order
            </a>

        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-100 text-green-800 border border-green-200">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Order Summary Header --}}
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-6 sm:p-8 shadow-lg">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

            <div>
                <p class="text-sm text-slate-300 mb-2">
                    Order Number
                </p>

                <h3 class="text-2xl sm:text-3xl font-bold text-white">
                    {{ $order->order_number ?? 'ORD-' . $order->id }}
                </h3>

                <p class="text-sm text-slate-300 mt-3">
                    <i class="far fa-calendar-alt text-amber-400 mr-2"></i>
                    {{ $order->created_at?->format('d M Y, h:i A') ?? '—' }}
                </p>
            </div>

            <div class="flex flex-col sm:items-end gap-3">

                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold {{ $statusClass }}">
                    <span class="w-2 h-2 rounded-full bg-current"></span>
                    {{ ucfirst($status) }}
                </span>

                <p class="text-sm text-slate-300">
                    Order ID: #{{ $order->id }}
                </p>

            </div>

        </div>
    </div>

    {{-- Order Information Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- Customer --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-5 shadow-sm">

            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-user text-lg"></i>
                </div>

                <h3 class="font-bold text-gray-900 dark:text-white">
                    Customer
                </h3>
            </div>

            <p class="font-semibold text-gray-900 dark:text-white">
                {{ $order->customer?->name ?? 'Guest Customer' }}
            </p>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                {{ $order->customer?->email ?? 'No email provided' }}
            </p>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $order->customer?->phone ?? 'No phone provided' }}
            </p>

        </div>

        {{-- Order Type --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-5 shadow-sm">

            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                    <i class="fas fa-utensils text-lg"></i>
                </div>

                <h3 class="font-bold text-gray-900 dark:text-white">
                    Order Type
                </h3>
            </div>

            <p class="text-lg font-bold text-gray-900 dark:text-white">
                {{ ucwords(str_replace('_', ' ', $order->order_type ?? 'dine_in')) }}
            </p>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                Restaurant order category
            </p>

        </div>

        {{-- Grand Total --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-5 shadow-sm">

            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-wallet text-lg"></i>
                </div>

                <h3 class="font-bold text-gray-900 dark:text-white">
                    Grand Total
                </h3>
            </div>

            <p class="text-2xl font-bold text-amber-600">
                Rs. {{ number_format($total, 2) }}
            </p>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                Total order amount
            </p>

        </div>

    </div>

    {{-- Order Items --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">

        <div class="p-5 sm:p-6 border-b border-gray-200 dark:border-slate-700">

            <div class="flex items-center gap-3">

                <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                    <i class="fas fa-hamburger text-lg"></i>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Ordered Food Items
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ count($items) }} {{ count($items) === 1 ? 'item' : 'items' }} in this order
                    </p>
                </div>

            </div>

        </div>

        @if(count($items) > 0)

            <div class="overflow-x-auto">

                <table class="w-full min-w-[600px] text-left">

                    <thead class="bg-gray-50 dark:bg-slate-900/60">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400">
                                #
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400">
                                Food Item
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400">
                                Quantity
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400">
                                Unit Price
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400 text-right">
                                Total
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">

                        @foreach($items as $item)

                            @php
                                $quantity = (int) ($item['quantity'] ?? 0);
                                $price = (float) ($item['price'] ?? 0);
                                $lineTotal = $quantity * $price;
                            @endphp

                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">

                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $item['name'] ?? 'Food Item' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $quantity }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                    Rs. {{ number_format($price, 2) }}
                                </td>

                                <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                    Rs. {{ number_format($lineTotal, 2) }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="p-10 text-center">
                <i class="fas fa-utensils text-3xl text-gray-400 mb-3"></i>

                <p class="text-gray-500 dark:text-gray-400">
                    No food items found for this order.
                </p>
            </div>

        @endif

    </div>

    {{-- Order Total and Notes --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Notes --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-5 sm:p-6 shadow-sm">

            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-sticky-note"></i>
                </div>

                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    Order Notes
                </h3>
            </div>

            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                {{ $order->notes ?: 'No additional notes for this order.' }}
            </p>

        </div>

        {{-- Price Breakdown --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-5 sm:p-6 shadow-sm">

            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5">
                <i class="fas fa-calculator text-amber-500 mr-2"></i>
                Price Breakdown
            </h3>

            <div class="space-y-4">

                <div class="flex justify-between gap-3 text-sm">
                    <span class="text-gray-600 dark:text-gray-300">
                        Subtotal
                    </span>

                    <span class="font-semibold text-gray-900 dark:text-white">
                        Rs. {{ number_format($subtotal, 2) }}
                    </span>
                </div>

                <div class="flex justify-between gap-3 text-sm">
                    <span class="text-gray-600 dark:text-gray-300">
                        Discount
                    </span>

                    <span class="font-semibold text-red-600">
                        - Rs. {{ number_format($discount, 2) }}
                    </span>
                </div>

                <div class="flex justify-between gap-3 text-sm">
                    <span class="text-gray-600 dark:text-gray-300">
                        Tax
                    </span>

                    <span class="font-semibold text-gray-900 dark:text-white">
                        Rs. {{ number_format($tax, 2) }}
                    </span>
                </div>

                <div class="border-t border-gray-200 dark:border-slate-700 pt-4">

                    <div class="flex justify-between items-center gap-3">

                        <span class="font-bold text-gray-900 dark:text-white">
                            Grand Total
                        </span>

                        <span class="text-xl font-bold text-amber-600">
                            Rs. {{ number_format($total, 2) }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Payment Records --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">

        <div class="p-5 sm:p-6 border-b border-gray-200 dark:border-slate-700">

            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-credit-card text-lg"></i>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Payment Records
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Payments associated with this order.
                    </p>
                </div>
            </div>

        </div>

        @if($order->payments->count() > 0)

            <div class="overflow-x-auto">

                <table class="w-full min-w-[650px] text-left">

                    <thead class="bg-gray-50 dark:bg-slate-900/60">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400">
                                Payment Number
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400">
                                Method
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400">
                                Amount
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400">
                                Status
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400">
                                Paid At
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">

                        @foreach($order->payments as $payment)

                            @php
                                $paymentStatus = strtolower($payment->status ?? 'pending');

                                $paymentStyles = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    'refunded' => 'bg-purple-100 text-purple-800',
                                ];
                            @endphp

                            <tr>

                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $payment->payment_number ?? 'PAY-' . $payment->id }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ ucwords(str_replace('_', ' ', $payment->payment_method ?? 'cash')) }}
                                </td>

                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                    Rs. {{ number_format((float) $payment->amount, 2) }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-bold {{ $paymentStyles[$paymentStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($paymentStatus) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                    {{ $payment->paid_at?->format('d M Y, h:i A') ?? '—' }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="p-10 text-center">
                <i class="fas fa-credit-card text-3xl text-gray-400 mb-3"></i>

                <p class="text-gray-500 dark:text-gray-400">
                    No payment records linked to this order.
                </p>

            </div>

        @endif

    </div>

    {{-- Bottom Actions --}}
    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">

        <form action="{{ route('admin.orders.destroy', $order) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to delete this order?')">

            @csrf
            @method('DELETE')

            <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-red-100 text-red-700 hover:bg-red-200 font-semibold transition">
                <i class="fas fa-trash"></i>
                Delete Order
            </button>

        </form>

        <a href="{{ route('admin.orders.edit', $order) }}"
           class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold transition">
            <i class="fas fa-edit"></i>
            Update Order
        </a>

    </div>

</div>

@endsection
