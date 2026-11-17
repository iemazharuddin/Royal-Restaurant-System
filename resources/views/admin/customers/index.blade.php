@extends('layouts.admin')

@section('title', 'Menu Items')
@section('page-heading', 'Menu Items')

@push('styles')
<style>
    .menu-item-search,
    .menu-item-status {
        min-height: 40px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        color: var(--text);
        font-size: 12px;
        box-shadow: 0 3px 10px rgba(17, 28, 24, .04);
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .menu-item-search {
        width: 100%;
        padding-right: 12px;
    }

    .menu-item-search:focus,
    .menu-item-status:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(214, 173, 105, .2);
    }

    .menu-item-status {
        width: 100%;
        appearance: none;
        padding: 0 34px 0 12px;
        cursor: pointer;
    }

    .menu-item-select-wrap {
        position: relative;
        width: 100%;
    }

    .menu-item-select-wrap .fa-chevron-down {
        position: absolute;
        top: 50%;
        right: 13px;
        color: var(--muted);
        font-size: 10px;
        pointer-events: none;
        transform: translateY(-50%);
    }

    /* DELETE CARD (compact) */
    .menu-delete-overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: grid;
        place-items: center;
        padding: 16px;
        background: rgba(9, 18, 14, .6);
        backdrop-filter: blur(4px);
    }

    .menu-delete-overlay.hidden { display: none; }

    .menu-delete-dialog {
        width: min(100%, 320px);
        padding: 18px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: var(--surface);
        color: var(--text);
        box-shadow: 0 24px 70px rgba(9, 18, 14, .26);
        animation: menu-dialog-enter .18s ease-out;
    }

    .menu-delete-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        font-size: 14px;
        display: grid;
        place-items: center;
        color: #dc3545;
        background: rgba(239, 68, 68, .11);
    }

    .menu-delete-target {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 12px;
        padding: 9px 11px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--bg);
    }

    .menu-delete-cancel,
    .menu-delete-confirm {
        min-height: 36px;
        padding: 0 13px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .menu-delete-cancel {
        border: 1px solid var(--border);
        color: var(--text);
        background: var(--surface);
    }

    .menu-delete-cancel:hover { background: var(--bg); }

    .menu-delete-confirm {
        color: white;
        background: linear-gradient(135deg, #ef6472, #c92f49);
        box-shadow: 0 6px 14px rgba(201, 47, 73, .2);
    }

    .menu-delete-confirm:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(201, 47, 73, .28);
    }

    /* Compact row action buttons */
    .menu-row .admin-action-btn {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        font-size: 12px;
    }

    @keyframes menu-dialog-enter {
        from { opacity: 0; transform: translateY(6px) scale(.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @media (min-width: 640px) {
        .menu-item-search-wrap { width: 230px; }
        .menu-item-select-wrap { width: 170px; }
    }
</style>
@endpush

@section('content')

@php
    $totalItems     = $menuItems->total();
    $availableItems = \App\Models\MenuItem::where('is_available', true)->count();
    $featuredItems  = \App\Models\MenuItem::where('is_featured', true)->count();
@endphp

<!-- PAGE INTRO -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">

    <div>
        <p class="text-[10px] gold font-bold uppercase tracking-widest">Management</p>

        <h1 class="text-xl sm:text-2xl font-extrabold mt-1"
            style="font-family:'Playfair Display',serif;">
            Menu Items
        </h1>

        <p class="text-xs muted mt-1">
            Manage your restaurant menu items.
        </p>
    </div>

    <a href="{{ route('admin.menu-items.create') }}"
       class="admin-add-btn px-4 py-2.5 rounded-xl text-xs inline-flex items-center justify-center gap-2 self-start md:self-auto">
        <i class="fas fa-plus"></i>
        Add Menu Item
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
        {{ $errors->first() }}
    </div>
@endif

<!-- STAT CARDS -->
<div class="grid grid-cols-3 gap-2 sm:gap-3 mb-5">

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Total</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $totalItems }}</h3>
        </div>

        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm"
             style="background:linear-gradient(145deg,#a78bfa,#6545c4);box-shadow:0 5px 12px #6545c433;">
            <i class="fas fa-utensils"></i>
        </div>
    </div>

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Available</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $availableItems }}</h3>
        </div>

        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm"
             style="background:linear-gradient(145deg,#5fe0b0,#17966e);box-shadow:0 5px 12px #17966e33;">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Featured</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $featuredItems }}</h3>
        </div>

        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm"
             style="background:linear-gradient(145deg,#ffe2a0,#c78c28);box-shadow:0 5px 12px #c78c2833;">
            <i class="fas fa-star"></i>
        </div>
    </div>

</div>

<!-- MENU ITEMS TABLE -->
<div class="surface min-w-0 overflow-hidden">

    <div class="p-4 border-b flex flex-col md:flex-row gap-3 md:items-center md:justify-between"
         style="border-color:var(--border)">

        <div>
            <h3 class="font-bold text-base">All Menu Items</h3>
            <p class="text-[11px] muted mt-0.5">
                View, search and manage your restaurant menu.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2.5">

            <div class="menu-item-search-wrap relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 muted text-[11px]"></i>

                <input type="text"
                       id="menuSearch"
                       placeholder="Search menu items..."
                       class="menu-item-search pl-8">
            </div>

            <div class="menu-item-select-wrap">
                <select id="categoryFilter" class="menu-item-status">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ strtolower($category->name) }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down"></i>
            </div>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="admin-table w-full text-left text-xs min-w-[680px]" id="menuTable">

            <thead>
                <tr>
                    <th class="px-4 py-3 muted font-bold">Item</th>
                    <th class="px-4 py-3 muted font-bold">Category</th>
                    <th class="px-4 py-3 muted font-bold">Price</th>
                    <th class="px-4 py-3 muted font-bold">Status</th>
                    <th class="px-4 py-3 muted font-bold text-right">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($menuItems as $item)

                    <tr class="menu-row border-b"
                        style="border-color:var(--border)"
                        data-name="{{ strtolower($item->name) }}"
                        data-category="{{ strtolower($item->category->name ?? '') }}">

                        <!-- ITEM -->
                        <td class="px-4 py-2.5">
                            <div class="flex items-center gap-2.5">

                                @if($item->image)
                                    <img src="{{ asset('storage/'.$item->image) }}"
                                         class="w-9 h-9 rounded-lg object-cover shrink-0"
                                         alt="{{ $item->name }}">
                                @else
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-xs shrink-0"
                                         style="background:linear-gradient(145deg,#a78bfa,#6545c4)">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                @endif

                                <div class="min-w-0">
                                    <p class="font-bold text-[13px] leading-tight">
                                        {{ $item->name }}
                                        @if($item->is_featured)
                                            <i class="fas fa-star gold text-[9px] ml-1" title="Featured"></i>
                                        @endif
                                    </p>
                                </div>

                            </div>
                        </td>

                        <!-- CATEGORY -->
                        <td class="px-4 py-2.5 muted whitespace-nowrap">
                            {{ $item->category->name ?? 'Uncategorized' }}
                        </td>

                        <!-- PRICE -->
                        <td class="px-4 py-2.5 font-semibold whitespace-nowrap">
                            Rs. {{ number_format($item->price, 2) }}
                        </td>

                        <!-- STATUS -->
                        <td class="px-4 py-2.5">

                            @if($item->is_available)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-500/10 text-green-600 whitespace-nowrap">
                                    <i class="fas fa-circle text-[5px] mr-1"></i>
                                    Available
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-500/10 text-red-500 whitespace-nowrap">
                                    <i class="fas fa-circle text-[5px] mr-1"></i>
                                    Unavailable
                                </span>
                            @endif

                        </td>

                        <!-- ACTIONS -->
                        <td class="px-4 py-2.5">
                            <div class="flex items-center justify-end gap-1.5">

                                <a href="{{ route('admin.menu-items.show', $item) }}"
                                   class="admin-action-btn admin-action-view"
                                   title="View"
                                   aria-label="View {{ $item->name }}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.menu-items.edit', $item) }}"
                                   class="admin-action-btn admin-action-edit"
                                   title="Edit"
                                   aria-label="Edit {{ $item->name }}">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <form action="{{ route('admin.menu-items.destroy', $item) }}"
                                      method="POST"
                                      data-menu-delete="{{ $item->name }}"
                                      data-menu-category="{{ $item->category->name ?? 'Uncategorized' }}">

                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            onclick="openMenuDeleteDialog(this.form)"
                                            class="admin-action-btn admin-action-delete"
                                            title="Delete"
                                            aria-label="Delete {{ $item->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center py-10">

                            <div class="text-3xl muted mb-3">
                                <i class="fas fa-utensils"></i>
                            </div>

                            <h3 class="font-bold text-base">No Menu Items Found</h3>

                            <p class="text-xs muted mt-1.5">
                                Add your first item to the restaurant menu.
                            </p>

                            <a href="{{ route('admin.menu-items.create') }}"
                               class="admin-add-btn inline-flex mt-4 px-4 py-2.5 rounded-xl text-xs">
                                <i class="fas fa-plus mr-2"></i>
                                Add Menu Item
                            </a>

                        </td>
                    </tr>

                @endforelse

                <tr id="noMenuSearchResults" class="hidden">
                    <td colspan="5" class="text-center py-8 text-xs muted">
                        No matching menu items found.
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    <div class="p-4 border-t text-xs" style="border-color:var(--border)">
        {{ $menuItems->links() }}
    </div>

</div>

<!-- DELETE CARD -->
<div id="menuDeleteOverlay"
     class="menu-delete-overlay hidden"
     aria-hidden="true"
     onclick="if (event.target === this) closeMenuDeleteDialog()">
    <section class="menu-delete-dialog" role="dialog" aria-modal="true" aria-labelledby="menuDeleteTitle">

        <div class="menu-delete-icon mb-3">
            <i class="fas fa-trash-alt"></i>
        </div>

        <h2 id="menuDeleteTitle" class="text-base font-extrabold">Delete menu item?</h2>

        <p class="muted text-xs mt-1.5 leading-5">
            This will permanently remove the menu item. This action cannot be undone.
        </p>

        <div class="menu-delete-target">
            <div class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center text-white text-xs"
                 style="background:linear-gradient(145deg,#a78bfa,#6545c4)">
                <i class="fas fa-utensils"></i>
            </div>
            <div class="min-w-0">
                <p id="menuDeleteName" class="font-bold text-[13px] leading-tight truncate"></p>
                <p id="menuDeleteCategory" class="text-[10px] muted mt-0.5 truncate"></p>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <button type="button" class="menu-delete-cancel" onclick="closeMenuDeleteDialog()">
                Cancel
            </button>
            <button type="button" class="menu-delete-confirm" onclick="confirmMenuDelete()">
                <i class="fas fa-trash-alt mr-1"></i> Delete
            </button>
        </div>

    </section>
</div>

@endsection

@push('scripts')
<script>
    let menuDeleteForm = null;
    let menuDeleteTrigger = null;

    function openMenuDeleteDialog(form) {
        menuDeleteForm = form;
        menuDeleteTrigger = document.activeElement;

        const overlay = document.getElementById('menuDeleteOverlay');
        document.getElementById('menuDeleteName').textContent = form.dataset.menuDelete;
        document.getElementById('menuDeleteCategory').textContent = form.dataset.menuCategory || 'Uncategorized';
        overlay.classList.remove('hidden');
        overlay.setAttribute('aria-hidden', 'false');
        overlay.querySelector('.menu-delete-cancel').focus();
    }

    function closeMenuDeleteDialog() {
        const overlay = document.getElementById('menuDeleteOverlay');
        overlay.classList.add('hidden');
        overlay.setAttribute('aria-hidden', 'true');
        menuDeleteForm = null;
        menuDeleteTrigger?.focus();
    }

    function confirmMenuDelete() {
        if (!menuDeleteForm) return;

        menuDeleteForm.submit();
    }

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !document.getElementById('menuDeleteOverlay').classList.contains('hidden')) {
            closeMenuDeleteDialog();
        }
    });

    // Menu item search and category filter
    const menuSearch = document.getElementById('menuSearch');
    const categoryFilter = document.getElementById('categoryFilter');

    function filterMenuItems() {
        const search = menuSearch.value.toLowerCase().trim();
        const category = categoryFilter.value.toLowerCase();
        const rows = document.querySelectorAll('.menu-row');

        let visibleCount = 0;

        rows.forEach(row => {
            const nameMatch = (row.dataset.name || '').includes(search);
            const categoryMatch = !category || row.dataset.category === category;
            const show = nameMatch && categoryMatch;

            row.style.display = show ? '' : 'none';

            if (show) visibleCount++;
        });

        const noResults = document.getElementById('noMenuSearchResults');

        if (noResults) {
            noResults.classList.toggle(
                'hidden',
                visibleCount !== 0 || rows.length === 0
            );
        }
    }

    menuSearch?.addEventListener('input', filterMenuItems);
    categoryFilter?.addEventListener('change', filterMenuItems);
</script>
@endpush
