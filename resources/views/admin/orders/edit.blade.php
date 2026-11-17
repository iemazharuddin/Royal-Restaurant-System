
@extends('layouts.admin')

@section('title', 'Edit Order')
@section('page-heading', 'Edit Order')

@section('content')

@php
    $existingItems = old('items', $order->items ?? []);

    if (!is_array($existingItems) || count($existingItems) === 0) {
        $existingItems = [
            ['name' => '', 'quantity' => 1, 'price' => '']
        ];
    }

    $existingItems = array_values($existingItems);
@endphp

<div class="max-w-6xl mx-auto space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Edit Order
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Update order information, food items and payment summary.
            </p>
        </div>

        <a href="{{ route('admin.orders.index') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 font-semibold transition">
            <i class="fas fa-arrow-left"></i>
            Back to Orders
        </a>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-100 text-red-800 border border-red-200">
            <div class="font-semibold mb-2">
                <i class="fas fa-exclamation-circle mr-2"></i>
                Please correct the following errors:
            </div>

            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Order Information --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">

        <div class="p-5 sm:p-6 border-b border-gray-200 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                    <i class="fas fa-edit text-lg"></i>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Order Information
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Order #{{ $order->order_number ?? $order->id }}
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.orders.update', $order) }}"
              method="POST"
              id="orderForm"
              class="space-y-6">

            @csrf
            @method('PUT')

            <div class="p-5 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Customer --}}
                <div>
                    <label for="customer_id"
                           class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Select Customer
                    </label>

                    <select name="customer_id"
                            id="customer_id"
                            class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500">

                        <option value="">Guest Customer</option>

                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}"
                                {{ (string) old('customer_id', $order->customer_id) === (string) $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                                @if($customer->phone)
                                    — {{ $customer->phone }}
                                @endif
                            </option>
                        @endforeach

                    </select>

                    @error('customer_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Order Type --}}
                <div>
                    <label for="order_type"
                           class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Order Type <span class="text-red-500">*</span>
                    </label>

                    <select name="order_type"
                            id="order_type"
                            required
                            class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500">

                        <option value="dine_in" {{ old('order_type', $order->order_type) === 'dine_in' ? 'selected' : '' }}>
                            Dine In
                        </option>

                        <option value="takeaway" {{ old('order_type', $order->order_type) === 'takeaway' ? 'selected' : '' }}>
                            Takeaway
                        </option>

                        <option value="delivery" {{ old('order_type', $order->order_type) === 'delivery' ? 'selected' : '' }}>
                            Delivery
                        </option>

                    </select>

                    @error('order_type')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Order Status --}}
                <div>
                    <label for="status"
                           class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Order Status <span class="text-red-500">*</span>
                    </label>

                    <select name="status"
                            id="status"
                            required
                            class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500">

                        @foreach(['pending', 'processing', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}"
                                {{ old('status', $order->status) === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach

                    </select>

                    @error('status')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Order Date --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Order Created
                    </label>

                    <input type="text"
                           value="{{ $order->created_at?->format('d M Y, h:i A') ?? '—' }}"
                           readonly
                           class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-gray-100 dark:bg-slate-900 text-gray-600 dark:text-gray-300 px-4 py-3">
                </div>

            </div>

            {{-- Order Items --}}
            <div class="border-t border-gray-200 dark:border-slate-700">

                <div class="p-5 sm:p-6 border-b border-gray-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                            <i class="fas fa-utensils text-lg"></i>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                Order Items
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Update food items, quantities and prices.
                            </p>
                        </div>
                    </div>

                    <button type="button"
                            id="addItemButton"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold transition">
                        <i class="fas fa-plus"></i>
                        Add Item
                    </button>

                </div>

                <div class="p-5 sm:p-6 space-y-4">

                    <div id="itemsContainer" class="space-y-4">

                        @foreach($existingItems as $index => $item)

                            <div class="item-row p-4 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">

                                    {{-- Item Name --}}
                                    <div class="sm:col-span-5">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Food Item <span class="text-red-500">*</span>
                                        </label>

                                        <input type="text"
                                               name="items[{{ $index }}][name]"
                                               value="{{ $item['name'] ?? '' }}"
                                               maxlength="255"
                                               required
                                               placeholder="e.g. Chicken Burger"
                                               class="item-name w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500">
                                    </div>

                                    {{-- Quantity --}}
                                    <div class="sm:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Quantity
                                        </label>

                                        <input type="number"
                                               name="items[{{ $index }}][quantity]"
                                               value="{{ $item['quantity'] ?? 1 }}"
                                               min="1"
                                               step="1"
                                               required
                                               class="item-quantity w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500">
                                    </div>

                                    {{-- Price --}}
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Unit Price (PKR)
                                        </label>

                                        <input type="number"
                                               name="items[{{ $index }}][price]"
                                               value="{{ $item['price'] ?? '' }}"
                                               min="0"
                                               step="0.01"
                                               required
                                               placeholder="0.00"
                                               class="item-price w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500">
                                    </div>

                                    {{-- Remove Item --}}
                                    <div class="sm:col-span-2">
                                        <button type="button"
                                                class="remove-item w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-red-100 text-red-600 hover:bg-red-200 font-semibold transition">
                                            <i class="fas fa-trash"></i>
                                            Remove
                                        </button>
                                    </div>

                                </div>
                            </div>

                        @endforeach

                    </div>

                    @error('items')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        At least one order item is required. Prices are in PKR.
                    </p>

                </div>
            </div>

            {{-- Order Summary --}}
            <div class="p-5 sm:p-6 border-t border-gray-200 dark:border-slate-700 grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Notes --}}
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fas fa-sticky-note"></i>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            Order Notes
                        </h3>
                    </div>

                    <label for="notes"
                           class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Additional Instructions
                    </label>

                    <textarea name="notes"
                              id="notes"
                              rows="5"
                              maxlength="2000"
                              placeholder="Special instructions or customer requests..."
                              class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500">{{ old('notes', $order->notes) }}</textarea>

                    @error('notes')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Price Summary --}}
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                            <i class="fas fa-calculator"></i>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            Order Summary
                        </h3>
                    </div>

                    {{-- Subtotal --}}
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <span class="text-sm text-gray-600 dark:text-gray-300">
                            Subtotal
                        </span>

                        <span class="font-semibold text-gray-900 dark:text-white">
                            Rs. <span id="subtotalDisplay">0.00</span>
                        </span>
                    </div>

                    {{-- Discount --}}
                    <div class="mb-4">
                        <label for="discount"
                               class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Discount (PKR)
                        </label>

                        <input type="number"
                               name="discount"
                               id="discount"
                               value="{{ old('discount', $order->discount ?? 0) }}"
                               min="0"
                               step="0.01"
                               class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500">

                        @error('discount')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tax --}}
                    <div class="mb-5">
                        <label for="tax"
                               class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Tax (PKR)
                        </label>

                        <input type="number"
                               name="tax"
                               id="tax"
                               value="{{ old('tax', $order->tax ?? 0) }}"
                               min="0"
                               step="0.01"
                               class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500">

                        @error('tax')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Total --}}
                    <div class="p-4 rounded-xl bg-slate-900 text-white">
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-semibold">
                                Grand Total
                            </span>

                            <span class="text-xl sm:text-2xl font-bold text-amber-400">
                                Rs. <span id="totalDisplay">0.00</span>
                            </span>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                        Total = Subtotal - Discount + Tax
                    </p>
                </div>

            </div>

            {{-- Form Actions --}}
            <div class="p-5 border-t border-gray-200 dark:border-slate-700 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">

                <a href="{{ route('admin.orders.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 font-semibold transition">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold shadow-sm transition">
                    <i class="fas fa-save"></i>
                    Update Order
                </button>

            </div>

        </form>

    </div>

</div>

{{-- Dynamic Item Management and Price Calculation --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('itemsContainer');
    const addButton = document.getElementById('addItemButton');
    const discountInput = document.getElementById('discount');
    const taxInput = document.getElementById('tax');

    let itemIndex = {{ count($existingItems) }};

    function formatMoney(amount) {
        return Number(amount).toFixed(2);
    }

    function calculateTotal() {
        let subtotal = 0;

        container.querySelectorAll('.item-row').forEach(function (row) {
            const quantity = parseFloat(
                row.querySelector('.item-quantity').value
            ) || 0;

            const price = parseFloat(
                row.querySelector('.item-price').value
            ) || 0;

            subtotal += quantity * price;
        });

        const discount = parseFloat(discountInput.value) || 0;
        const tax = parseFloat(taxInput.value) || 0;

        const total = subtotal - discount + tax;

        document.getElementById('subtotalDisplay').textContent =
            formatMoney(subtotal);

        document.getElementById('totalDisplay').textContent =
            formatMoney(total);
    }

    function addItem() {
        const row = document.createElement('div');

        row.className = 'item-row p-4 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50';

        row.innerHTML = `
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">

                <div class="sm:col-span-5">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Food Item
                    </label>

                    <input type="text"
                           name="items[${itemIndex}][name]"
                           maxlength="255"
                           required
                           placeholder="e.g. Chicken Burger"
                           class="item-name w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Quantity
                    </label>

                    <input type="number"
                           name="items[${itemIndex}][quantity]"
                           value="1"
                           min="1"
                           step="1"
                           required
                           class="item-quantity w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Unit Price (PKR)
                    </label>

                    <input type="number"
                           name="items[${itemIndex}][price]"
                           value=""
                           min="0"
                           step="0.01"
                           required
                           placeholder="0.00"
                           class="item-price w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-900 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div class="sm:col-span-2">
                    <button type="button"
                            class="remove-item w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-red-100 text-red-600 hover:bg-red-200 font-semibold transition">
                        <i class="fas fa-trash"></i>
                        Remove
                    </button>
                </div>

            </div>
        `;

        container.appendChild(row);
        itemIndex++;
        calculateTotal();
    }

    addButton.addEventListener('click', addItem);

    container.addEventListener('input', function (event) {
        if (event.target.matches('.item-quantity, .item-price')) {
            calculateTotal();
        }
    });

    container.addEventListener('click', function (event) {
        const removeButton = event.target.closest('.remove-item');

        if (!removeButton) {
            return;
        }

        const rows = container.querySelectorAll('.item-row');

        if (rows.length <= 1) {
            alert('At least one order item is required.');
            return;
        }

        removeButton.closest('.item-row').remove();
        calculateTotal();
    });

    discountInput.addEventListener('input', calculateTotal);
    taxInput.addEventListener('input', calculateTotal);

    calculateTotal();
});
</script>

@endsection
