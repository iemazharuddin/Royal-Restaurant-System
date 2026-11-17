@extends('layouts.admin')

@section('title', 'Orders Management')
@section('page-heading', 'Orders')

@push('styles')
<style>
    .order-search,
    .order-status {
        min-height: 40px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        color: var(--text);
        font-size: 12px;
        box-shadow: 0 3px 10px rgba(17, 28, 24, .04);
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .order-search {
        width: 100%;
        padding-right: 12px;
    }

    .order-search:focus,
    .order-status:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(214, 173, 105, .2);
    }

    .order-status {
        width: 100%;
        appearance: none;
        padding: 0 34px 0 12px;
        cursor: pointer;
    }

    .order-status-wrap {
        position: relative;
        width: 100%;
    }

    .order-status-wrap .fa-chevron-down {
        position: absolute;
        top: 50%;
        right: 13px;
        color: var(--muted);
        font-size: 10px;
        pointer-events: none;
        transform: translateY(-50%);
    }

    /* DELETE CARD (compact) */
    .order-delete-overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: grid;
        place-items: center;
        padding: 16px;
        background: rgba(9, 18, 14, .6);
        backdrop-filter: blur(4px);
    }

    .order-delete-overlay.hidden { display: none; }

    .order-delete-dialog {
        width: min(100%, 320px);
        padding: 18px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: var(--surface);
        color: var(--text);
        box-shadow: 0 24px 70px rgba(9, 18, 14, .26);
        animation: order-dialog-enter .18s ease-out;
    }

    .order-delete-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        font-size: 14px;
        display: grid;
        place-items: center;
        color: #dc3545;
        background: rgba(239, 68, 68, .11);
    }

    .order-delete-target {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 12px;
        padding: 9px 11px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--bg);
    }

    .order-delete-cancel,
    .order-delete-confirm {
        min-height: 36px;
        padding: 0 13px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .order-delete-cancel {
        border: 1px solid var(--border);
        color: var(--text);
        background: var(--surface);
    }

    .order-delete-cancel:hover { background: var(--bg); }

    .order-delete-confirm {
        color: white;
        background: linear-gradient(135deg, #ef6472, #c92f49);
        box-shadow: 0 6px 14px rgba(201, 47, 73, .2);
    }

    .order-delete-confirm:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(201, 47, 73, .28);
    }

    /* Compact row action buttons */
    .order-row .admin-action-btn {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        font-size: 12px;
    }

    @keyframes order-dialog-enter {
        from { opacity: 0; transform: translateY(6px) scale(.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @media (min-width: 640px) {
        .order-search-wrap { width: 230px; }
        .order-status-wrap { width: 150px; }
    }
</style>
@endpush

@section('content')

<!-- PAGE INTRO -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">

    <div>
        <p class="text-[10px] gold font-bold uppercase tracking-widest">Management</p>

        <h1 class="text-xl sm:text-2xl font-extrabold mt-1"
            style="font-family:'Playfair Display',serif;">
            Orders Management
        </h1>

        <p class="text-xs muted mt-1">
            Manage and monitor all restaurant orders.
        </p>
    </div>

    <a href="{{ route('admin.orders.create') }}"
       class="admin-add-btn px-4 py-2.5 rounded-xl text-xs inline-flex items-center justify-center gap-2 self-start md:self-auto">
        <i class="fas fa-plus"></i>
        Create New Order
    </a>

</div>

<!-- FLASH MESSAGES -->
@if(session('success'))
    <div class="mb-4 p-3 rounded-xl bg-green-500/10 text-green-600 text-xs">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 p-3 rounded-xl bg-red-500/10 text-red-500 text-xs">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<!-- STAT CARDS -->
<div class="grid grid-cols-3 gap-2 sm:gap-3 mb-5">

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Total Orders</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $orders->total() }}</h3>
        </div>

        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm"
             style="background:linear-gradient(145deg,#efd39a,#a17b41);box-shadow:0 5px 12px #a17b4133;">
            <i class="fas fa-shopping-bag"></i>
        </div>
    </div>

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">On This Page</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $orders->count() }}</h3>
        </div>

        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm"
             style="background:linear-gradient(145deg,#8ec5ff,#2f6fd0);box-shadow:0 5px 12px #2f6fd033;">
            <i class="fas fa-list-alt"></i>
        </div>
    </div>

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Showing Page</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $orders->currentPage() }}</h3>
        </div>

        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm"
             style="background:linear-gradient(145deg,#a78bfa,#6545c4);box-shadow:0 5px 12px #6545c433;">
            <i class="fas fa-file-alt"></i>
        </div>
    </div>

</div>

<!-- ORDERS TABLE -->
<div class="surface min-w-0 overflow-hidden">

    <div class="p-4 border-b flex flex-col md:flex-row gap-3 md:items-center md:justify-between"
         style="border-color:var(--border)">

        <div>
            <h3 class="font-bold text-base">All Orders</h3>
            <p class="text-[11px] muted mt-0.5">
                Restaurant order records and details.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2.5">

            <div class="order-search-wrap relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 muted text-[11px]"></i>

                <input type="text"
                       id="searchInput"
                       placeholder="Search orders..."
                       class="order-search pl-8">
            </div>

            <div class="order-status-wrap">
                <select id="statusFilter" class="order-status">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <i class="fas fa-chevron-down"></i>
            </div>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="admin-table w-full text-left text-xs min-w-[900px]" id="orderTable">

            <thead>
                <tr>
                    <th class="px-4 py-3 muted font-bold">#</th>
                    <th class="px-4 py-3 muted font-bold">Order</th>
                    <th class="px-4 py-3 muted font-bold">Customer</th>
                    <th class="px-4 py-3 muted font-bold">Type</th>
                    <th class="px-4 py-3 muted font-bold">Items</th>
                    <th class="px-4 py-3 muted font-bold">Total</th>
                    <th class="px-4 py-3 muted font-bold">Status</th>
                    <th class="px-4 py-3 muted font-bold">Date</th>
                    <th class="px-4 py-3 muted font-bold text-right">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($orders as $order)

                    @php
                        $status = strtolower($order->status ?? 'pending');

                        $statusStyles = [
                            'pending'    => 'bg-yellow-500/10 text-yellow-600',
                            'processing' => 'bg-blue-500/10 text-blue-600',
                            'completed'  => 'bg-green-500/10 text-green-600',
                            'cancelled'  => 'bg-red-500/10 text-red-500',
                        ];

                        $statusClass = $statusStyles[$status] ?? 'bg-gray-500/10 muted';

                        $items = is_array($order->items) ? $order->items : [];
                        $itemCount = count($items);

                        $orderNumber = $order->order_number ?? 'ORD-' . $order->id;
                        $customerName = $order->customer?->name ?? 'Guest Customer';
                        $orderType = ucwords(str_replace('_', ' ', $order->order_type ?? 'dine_in'));
                    @endphp

                    <tr class="order-row border-b"
                        style="border-color:var(--border)"
                        data-search="{{ strtolower($orderNumber.' '.$customerName.' '.($order->customer?->email ?? '').' '.$orderType) }}"
                        data-status="{{ $status }}">

                        <!-- # -->
                        <td class="px-4 py-2.5 muted">
                            {{ $orders->firstItem() + $loop->index }}
                        </td>

                        <!-- ORDER NUMBER -->
                        <td class="px-4 py-2.5 font-bold whitespace-nowrap">
                            {{ $orderNumber }}
                        </td>

                        <!-- CUSTOMER -->
                        <td class="px-4 py-2.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-gold text-xs font-bold shrink-0"
                                     style="background:linear-gradient(145deg,#2c4236,#111c18)">
                                    {{ strtoupper(substr($customerName, 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <p class="font-bold text-[13px] leading-tight">{{ $customerName }}</p>
                                    <p class="text-[10px] muted mt-0.5">{{ $order->customer?->email ?? 'No email' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- TYPE -->
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5">
                                @if(($order->order_type ?? '') === 'delivery')
                                    <i class="fas fa-motorcycle gold"></i>
                                @elseif(($order->order_type ?? '') === 'takeaway')
                                    <i class="fas fa-shopping-bag gold"></i>
                                @else
                                    <i class="fas fa-utensils gold"></i>
                                @endif

                                {{ $orderType }}
                            </span>
                        </td>

                        <!-- ITEMS -->
                        <td class="px-4 py-2.5 muted whitespace-nowrap">
                            {{ $itemCount }} {{ $itemCount === 1 ? 'Item' : 'Items' }}
                        </td>

                        <!-- TOTAL -->
                        <td class="px-4 py-2.5 font-bold whitespace-nowrap">
                            Rs. {{ number_format((float) $order->total, 2) }}
                        </td>

                        <!-- STATUS -->
                        <td class="px-4 py-2.5">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold whitespace-nowrap {{ $statusClass }}">
                                <i class="fas fa-circle text-[5px] mr-1"></i>
                                {{ ucfirst($status) }}
                            </span>
                        </td>

                        <!-- DATE -->
                        <td class="px-4 py-2.5 muted whitespace-nowrap">
                            {{ $order->created_at?->format('d M Y') ?? '—' }}
                            <p class="text-[10px] mt-0.5">{{ $order->created_at?->format('h:i A') ?? '' }}</p>
                        </td>

                        <!-- ACTIONS -->
                        <td class="px-4 py-2.5">
                            <div class="flex items-center justify-end gap-1.5">

                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="admin-action-btn admin-action-view"
                                   title="View Order"
                                   aria-label="View order {{ $orderNumber }}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.orders.edit', $order) }}"
                                   class="admin-action-btn admin-action-edit"
                                   title="Edit Order"
                                   aria-label="Edit order {{ $orderNumber }}">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <form action="{{ route('admin.orders.destroy', $order) }}"
                                      method="POST"
                                      data-order-delete="{{ $orderNumber }}"
                                      data-order-customer="{{ $customerName }}">

                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            onclick="openOrderDeleteDialog(this.form)"
                                            class="admin-action-btn admin-action-delete"
                                            title="Delete Order"
                                            aria-label="Delete order {{ $orderNumber }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="text-center py-10">

                            <div class="text-3xl muted mb-3">
                                <i class="fas fa-shopping-bag"></i>
                            </div>

                            <h3 class="font-bold text-base">No Orders Found</h3>

                            <p class="text-xs muted mt-1.5">
                                You haven't created any restaurant orders yet.
                            </p>

                            <a href="{{ route('admin.orders.create') }}"
                               class="admin-add-btn inline-flex mt-4 px-4 py-2.5 rounded-xl text-xs">
                                <i class="fas fa-plus mr-2"></i>
                                Create First Order
                            </a>

                        </td>
                    </tr>

                @endforelse

                <tr id="noSearchResults" class="hidden">
                    <td colspan="9" class="text-center py-8 text-xs muted">
                        No matching orders found.
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    @if($orders->hasPages())
        <div class="p-4 border-t text-xs" style="border-color:var(--border)">
            {{ $orders->links() }}
        </div>
    @endif

</div>

<!-- DELETE CARD -->
<div id="orderDeleteOverlay"
     class="order-delete-overlay hidden"
     aria-hidden="true"
     onclick="if (event.target === this) closeOrderDeleteDialog()">
    <section class="order-delete-dialog" role="dialog" aria-modal="true" aria-labelledby="orderDeleteTitle">

        <div class="order-delete-icon mb-3">
            <i class="fas fa-trash-alt"></i>
        </div>

        <h2 id="orderDeleteTitle" class="text-base font-extrabold">Delete order?</h2>

        <p class="muted text-xs mt-1.5 leading-5">
            This will permanently remove the order. This action cannot be undone.
        </p>

        <div class="order-delete-target">
            <div class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center text-gold text-xs"
                 style="background:linear-gradient(145deg,#2c4236,#111c18)">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="min-w-0">
                <p id="orderDeleteName" class="font-bold text-[13px] leading-tight truncate"></p>
                <p id="orderDeleteCustomer" class="text-[10px] muted mt-0.5 truncate"></p>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <button type="button" class="order-delete-cancel" onclick="closeOrderDeleteDialog()">
                Cancel
            </button>
            <button type="button" class="order-delete-confirm" onclick="confirmOrderDelete()">
                <i class="fas fa-trash-alt mr-1"></i> Delete
            </button>
        </div>

    </section>
</div>

@endsection

@push('scripts')
<script>
    let orderDeleteForm = null;
    let orderDeleteTrigger = null;

    function openOrderDeleteDialog(form) {
        orderDeleteForm = form;
        orderDeleteTrigger = document.activeElement;

        const overlay = document.getElementById('orderDeleteOverlay');
        document.getElementById('orderDeleteName').textContent = form.dataset.orderDelete;
        document.getElementById('orderDeleteCustomer').textContent = form.dataset.orderCustomer || '';
        overlay.classList.remove('hidden');
        overlay.setAttribute('aria-hidden', 'false');
        overlay.querySelector('.order-delete-cancel').focus();
    }

    function closeOrderDeleteDialog() {
        const overlay = document.getElementById('orderDeleteOverlay');
        overlay.classList.add('hidden');
        overlay.setAttribute('aria-hidden', 'true');
        orderDeleteForm = null;
        orderDeleteTrigger?.focus();
    }

    function confirmOrderDelete() {
        if (!orderDeleteForm) return;

        orderDeleteForm.submit();
    }

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !document.getElementById('orderDeleteOverlay').classList.contains('hidden')) {
            closeOrderDeleteDialog();
        }
    });

    // Order search and status filter
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');

    function filterOrders() {
        const search = searchInput.value.toLowerCase().trim();
        const status = statusFilter.value;
        const rows = document.querySelectorAll('.order-row');

        let visibleCount = 0;

        rows.forEach(row => {
            const matchesSearch = (row.dataset.search || '').includes(search);
            const matchesStatus = !status || row.dataset.status === status;
            const show = matchesSearch && matchesStatus;

            row.style.display = show ? '' : 'none';

            if (show) visibleCount++;
        });

        const noResults = document.getElementById('noSearchResults');

        if (noResults) {
            noResults.classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
        }
    }

    searchInput?.addEventListener('input', filterOrders);
    statusFilter?.addEventListener('change', filterOrders);
</script>
@endpush