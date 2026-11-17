
@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
@php
    $customer = $order->customer ?? null;
    $table = $order->table ?? null;
    $items = $order->items ?? collect();
    $payments = $order->payments ?? collect();

    $orderNumber = $order->order_number ?? ('#'.$order->id);
    $customerName = $customer->name ?? 'Walk-in Customer';
    $customerEmail = $customer->email ?? 'N/A';
    $customerPhone = $customer->phone ?? 'N/A';
    $orderType = $order->order_type ?? 'N/A';
    $status = $order->status ?? 'Pending';

    $orderDate = $order->created_at
        ? $order->created_at->format('d M Y, h:i A')
        : 'N/A';

    $subtotal = (float)($order->subtotal ?? 0);
    $discount = (float)($order->discount ?? 0);
    $tax = (float)($order->tax ?? 0);
    $total = (float)($order->total ?? $order->grand_total ?? 0);

    $money = fn($amount) => 'Rs. '.number_format((float)$amount, 2);

    $itemName = fn($item) =>
        data_get($item, 'name')
        ?? data_get($item, 'food.name')
        ?? data_get($item, 'food_name')
        ?? data_get($item, 'product.name')
        ?? 'Food Item';

    $itemPrice = fn($item) =>
        data_get($item, 'pivot.price')
        ?? data_get($item, 'price')
        ?? data_get($item, 'unit_price')
        ?? data_get($item, 'item_price')
        ?? data_get($item, 'food.price')
        ?? data_get($item, 'product.price')
        ?? 0;

    $itemQty = fn($item) =>
        data_get($item, 'pivot.quantity')
        ?? data_get($item, 'quantity')
        ?? 1;

    $itemTotal = function($item) use ($itemPrice, $itemQty) {
        return data_get($item, 'pivot.total')
            ?? data_get($item, 'total')
            ?? ((float)$itemPrice($item) * (int)$itemQty($item));
    };

    $statusColor = match(strtolower($status)) {
        'confirmed', 'completed', 'delivered', 'paid' => 'text-emerald-400',
        'cancelled', 'rejected' => 'text-red-400',
        'pending', 'processing' => 'text-amber-400',
        default => 'text-blue-400',
    };
@endphp

<style>
.order-page{color:var(--text)}
.acrylic-card{
    position:relative;overflow:hidden;padding:15px;
    min-height:108px;border-radius:15px;
    background:linear-gradient(145deg,#292929,#111,#080808);
    border:1px solid #393939;
    box-shadow:inset 0 1px 1px #ffffff20,0 7px 18px #0004;
    transition:.25s
}
.acrylic-card:hover{transform:translateY(-3px)}
.acrylic-card:after{
    content:"";position:absolute;width:100px;height:100px;
    right:-40px;top:-50px;border-radius:50%;
    filter:blur(35px);opacity:.4
}
.acrylic-gold:after{background:#d6ad69}
.acrylic-blue:after{background:#3b82f6}
.acrylic-green:after{background:#10b981}
.acrylic-purple:after{background:#a855f7}
.acrylic-icon{
    width:34px;height:34px;border-radius:10px;
    display:grid;place-items:center;background:#ffffff15
}
.order-panel,.detail-card{
    padding:17px;border-radius:14px;
    background:var(--surface);border:1px solid var(--border);
    box-shadow:0 5px 15px #0000000c
}
.detail-card{box-shadow:0 5px 16px #00000012,inset 0 1px #ffffff12}
.detail-heading{display:flex;align-items:center;gap:10px;font-weight:800;font-size:14px}
.detail-icon{
    width:34px;height:34px;border-radius:10px;
    display:grid;place-items:center;color:white
}
.icon-food{background:linear-gradient(135deg,#d6ad69,#98672c)}
.icon-notes{background:linear-gradient(135deg,#3b82f6,#1d4ed8)}
.icon-payment{background:linear-gradient(135deg,#10b981,#047857)}
.info-row{
    display:flex;justify-content:space-between;gap:12px;
    padding:11px 0;border-bottom:1px solid var(--border);font-size:12px
}
.info-row:last-child{border-bottom:0}
.gold-btn,.white-gold-btn{
    display:inline-flex;align-items:center;justify-content:center;
    gap:8px;padding:10px 16px;border-radius:10px;
    font-size:12px;font-weight:800;transition:.2s
}
.gold-btn{
    color:#17251d;
    background:linear-gradient(135deg,#f4dfb5,#d6ad69 55%,#b68b4c);
    box-shadow:0 4px 12px #b68b4c35
}
.gold-btn:hover,.white-gold-btn:hover{transform:translateY(-2px)}
.white-gold-btn{background:var(--surface);border:1px solid var(--border);color:var(--text)}
.white-gold-btn:hover{border-color:#c69b55;color:#c69b55}
.order-table{width:100%;border-collapse:collapse;font-size:12px}
.order-table th{text-align:left;padding:11px;background:var(--bg);color:var(--muted)}
.order-table td{padding:12px 11px;border-bottom:1px solid var(--border)}
.detail-total{
    background:linear-gradient(135deg,#171717,#292929);
    color:white;border:1px solid #b68b4c;border-radius:10px;padding:12px
}
.detail-total strong{color:#f4d49b}
#printReceipt{display:none}
.receipt-header{
    text-align:center;background:#171717;color:#f0ce91;
    padding:12px 5px;border-bottom:3px solid #c69b55
}
.receipt-header h1{font-size:22px;font-weight:900;letter-spacing:4px}
.receipt-header h2{font-size:11px;letter-spacing:4px}
.receipt-header p{font-size:9px;margin-top:5px}
.receipt-info{padding:10px 0;font-size:10px}
.receipt table{width:100%;border-collapse:collapse;font-size:10px}
.receipt th{
    background:#252525;color:#f0ce91;text-align:left;padding:7px 3px
}
.receipt td{padding:7px 3px;border-bottom:1px dashed #aaa}
.receipt-totals{font-size:10px;padding-top:8px}
.receipt-totals p{display:flex;justify-content:space-between;margin:5px 0}
.receipt-grand{
    display:flex;justify-content:space-between;
    background:#171717;color:#f0ce91;padding:9px 5px;
    margin-top:9px;font-weight:bold
}
.receipt-footer{
    text-align:center;margin-top:12px;padding:10px 0;
    border-top:2px solid #c69b55;font-size:11px
}
@media(max-width:640px){
    .order-panel,.detail-card{padding:13px}
    .acrylic-card{padding:12px;min-height:100px}
    .order-actions>*{flex:1}
    .order-table{min-width:470px}
    .info-row{font-size:11px}
}
@media print{
    @page{size:80mm auto;margin:3mm}
    body *{visibility:hidden!important}
    #printReceipt,#printReceipt *{visibility:visible!important}
    #printReceipt{
        display:block!important;position:absolute!important;
        top:0!important;left:0!important;width:74mm!important;
        color:#111!important;background:white!important;
        font-family:Arial,sans-serif
    }
    .receipt{font-size:10px;line-height:1.4}
    .receipt-header,.receipt th,.receipt-grand{
        -webkit-print-color-adjust:exact!important;
        print-color-adjust:exact!important
    }
}
</style>

<div class="order-page space-y-4">

    {{-- Header --}}
    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <p class="text-xs mb-1" style="color:var(--muted)">Restaurant Management</p>
            <h1 class="text-2xl font-extrabold">Order Details</h1>
            <p class="text-sm mt-1" style="color:var(--muted)">Order #{{ $orderNumber }}</p>
        </div>
        <div class="order-actions flex flex-wrap gap-2">
            <button onclick="window.print()" class="gold-btn">
                <i class="fas fa-print"></i> Print Order Slip
            </button>
            <a href="{{ route('admin.orders.index') }}" class="white-gold-btn">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>

    {{-- Four Small 3D Cards --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-3">
        <div class="acrylic-card acrylic-gold">
            <div class="flex justify-between">
                <span class="acrylic-icon text-amber-300"><i class="fas fa-wallet"></i></span>
                <i class="fas fa-chart-line text-amber-300"></i>
            </div>
            <p class="text-xs text-gray-400 mt-3">Total Amount</p>
            <h3 class="text-lg font-extrabold text-white">{{ $money($total) }}</h3>
            <p class="text-[10px] text-gray-500">Order value</p>
        </div>

        <div class="acrylic-card acrylic-blue">
            <div class="flex justify-between">
                <span class="acrylic-icon text-blue-400"><i class="fas fa-check-circle"></i></span>
                <i class="fas fa-shield-alt text-blue-400"></i>
            </div>
            <p class="text-xs text-gray-400 mt-3">Order Status</p>
            <h3 class="text-lg font-extrabold {{ $statusColor }}">{{ ucfirst($status) }}</h3>
            <p class="text-[10px] text-gray-500">Current status</p>
        </div>

        <div class="acrylic-card acrylic-green">
            <div class="flex justify-between">
                <span class="acrylic-icon text-emerald-400"><i class="fas fa-user"></i></span>
                <i class="fas fa-user-check text-emerald-400"></i>
            </div>
            <p class="text-xs text-gray-400 mt-3">Customer</p>
            <h3 class="text-sm font-extrabold text-white truncate">{{ $customerName }}</h3>
            <p class="text-[10px] text-gray-500">Customer name</p>
        </div>

        <div class="acrylic-card acrylic-purple">
            <div class="flex justify-between">
                <span class="acrylic-icon text-purple-400"><i class="fas fa-motorcycle"></i></span>
                <i class="fas fa-box text-purple-400"></i>
            </div>
            <p class="text-xs text-gray-400 mt-3">Order Type</p>
            <h3 class="text-lg font-extrabold text-white">{{ ucfirst($orderType) }}</h3>
            <p class="text-[10px] text-gray-500">Order category</p>
        </div>
    </div>

    {{-- Customer and Order Information --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
        <section class="order-panel">
            <h2 class="font-bold text-sm mb-3">
                <i class="fas fa-user-circle text-amber-500 mr-2"></i>Customer Information
            </h2>
            <div class="info-row"><span style="color:var(--muted)">Name</span><strong>{{ $customerName }}</strong></div>
            <div class="info-row"><span style="color:var(--muted)">Email</span><strong class="break-all">{{ $customerEmail }}</strong></div>
            <div class="info-row"><span style="color:var(--muted)">Phone</span><strong>{{ $customerPhone }}</strong></div>
        </section>

        <section class="order-panel">
            <h2 class="font-bold text-sm mb-3">
                <i class="fas fa-receipt text-amber-500 mr-2"></i>Order Information
            </h2>
            <div class="info-row"><span style="color:var(--muted)">Order Number</span><strong>{{ $orderNumber }}</strong></div>
            <div class="info-row"><span style="color:var(--muted)">Order Type</span><strong>{{ ucfirst($orderType) }}</strong></div>
            <div class="info-row"><span style="color:var(--muted)">Date</span><strong>{{ $orderDate }}</strong></div>
            @if($table)
                <div class="info-row"><span style="color:var(--muted)">Table</span><strong>{{ $table->name ?? $table->number ?? 'N/A' }}</strong></div>
            @endif
        </section>
    </div>

    {{-- Food Items --}}
    <section class="detail-card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="detail-heading">
                <span class="detail-icon icon-food"><i class="fas fa-utensils"></i></span>
                Food Items
            </h2>
            <span class="text-xs px-3 py-1 rounded-full bg-amber-500/10 text-amber-600 font-bold">
                {{ count($items) }} Items
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="order-table">
                <thead>
                    <tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td class="font-bold">{{ $itemName($item) }}</td>
                            <td>{{ $money($itemPrice($item)) }}</td>
                            <td>{{ $itemQty($item) }}</td>
                            <td class="font-extrabold text-amber-600">{{ $money($itemTotal($item)) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-5">No food items found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="max-w-xs ml-auto mt-4 space-y-2 text-xs">
            <div class="flex justify-between"><span style="color:var(--muted)">Subtotal</span><strong>{{ $money($subtotal) }}</strong></div>
            <div class="flex justify-between"><span style="color:var(--muted)">Discount</span><strong class="text-red-500">{{ $money($discount) }}</strong></div>
            <div class="flex justify-between"><span style="color:var(--muted)">Tax</span><strong>{{ $money($tax) }}</strong></div>
            <div class="detail-total flex justify-between items-center text-sm font-extrabold">
                <span>Grand Total</span><strong>{{ $money($total) }}</strong>
            </div>
        </div>
    </section>

    {{-- Order Notes --}}
    @if($order->notes)
        <section class="detail-card">
            <h2 class="detail-heading mb-3">
                <span class="detail-icon icon-notes"><i class="fas fa-sticky-note"></i></span>
                Order Notes
            </h2>
            <div class="rounded-xl p-3 text-sm border border-blue-500/20 bg-blue-500/5">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                {{ $order->notes }}
            </div>
        </section>
    @endif

    {{-- Payment Records --}}
    <section class="detail-card">
        <h2 class="detail-heading mb-3">
            <span class="detail-icon icon-payment"><i class="fas fa-credit-card"></i></span>
            Payment Records
        </h2>
        @forelse($payments as $payment)
            <div class="flex justify-between items-center gap-3 p-3 mb-2 rounded-xl border"
                 style="border-color:var(--border);background:var(--bg)">
                <div>
                    <p class="font-bold text-sm">{{ ucfirst($payment->method ?? $payment->payment_method ?? 'Payment') }}</p>
                    <p class="text-xs" style="color:var(--muted)">
                        {{ $payment->created_at ? $payment->created_at->format('d M Y') : '' }}
                    </p>
                </div>
                <strong class="text-emerald-500 text-sm">{{ $money($payment->amount ?? 0) }}</strong>
            </div>
        @empty
            <div class="text-center rounded-xl p-4 bg-emerald-500/5 border border-emerald-500/15">
                <i class="fas fa-wallet text-emerald-500 text-xl mb-2"></i>
                <p class="text-xs font-semibold" style="color:var(--muted)">No payment records found.</p>
            </div>
        @endforelse
    </section>
</div>

{{-- Compact Colorful 80mm Receipt --}}
<div id="printReceipt">
    <div class="receipt">
        <div class="receipt-header">
            <h1>ROYAL</h1>
            <h2>RESTAURANT</h2>
            <p>Thank you for dining with us!</p>
        </div>

        <div class="receipt-info">
            <p>Order: <strong>{{ $orderNumber }}</strong></p>
            <p>Customer: {{ $customerName }}</p>
            <p>Phone: {{ $customerPhone }}</p>
            <p>Type: {{ ucfirst($orderType) }}</p>
            @if($table)
                <p>Table: {{ $table->name ?? $table->number ?? 'N/A' }}</p>
            @endif
        </div>

        <table>
            <thead>
                <tr><th>Item</th><th>Qty</th><th>Total</th></tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $itemName($item) }}</td>
                        <td>{{ $itemQty($item) }}</td>
                        <td>{{ number_format($itemTotal($item),2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="receipt-totals">
            <p><span>Subtotal</span><strong>{{ $money($subtotal) }}</strong></p>
            <p><span>Discount</span><strong>{{ $money($discount) }}</strong></p>
            <p><span>Tax</span><strong>{{ $money($tax) }}</strong></p>
            <div class="receipt-grand">
                <span>GRAND TOTAL</span><strong>{{ $money($total) }}</strong>
            </div>
        </div>

        @if($order->notes)
            <p style="font-size:10px;border-top:1px dashed #888;padding-top:8px;margin-top:10px">
                Notes: {{ $order->notes }}
            </p>
        @endif

        <div class="receipt-footer">
            <strong>THANK YOU!</strong>
            <p>Please visit us again.</p>
        </div>
    </div>
</div>
@endsection
