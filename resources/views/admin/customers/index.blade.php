@extends('layouts.admin')

@section('title', 'Menu Items')
@section('page-heading', 'Menu Items')

@section('content')

@php
    $totalItems     = $menuItems->total();
    $availableItems = \App\Models\MenuItem::where('is_available', true)->count();
    $featuredItems  = \App\Models\MenuItem::where('is_featured', true)->count();

    // Shared icon-button style (Tailwind only)
    $btn = 'inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs text-white shadow-sm ring-1 ring-inset ring-white/20 transition duration-200 hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2';
    $btnView   = 'bg-gradient-to-br from-sky-400 to-blue-600 shadow-blue-500/30 hover:shadow-blue-500/40 focus-visible:ring-sky-400';
    $btnEdit   = 'bg-gradient-to-br from-amber-400 to-orange-500 shadow-orange-500/30 hover:shadow-orange-500/40 focus-visible:ring-amber-400';
    $btnDelete = 'bg-gradient-to-br from-rose-400 to-red-600 shadow-red-500/30 hover:shadow-red-500/40 focus-visible:ring-red-400';
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

        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm bg-gradient-to-br from-violet-400 to-[#6545c4] shadow-md shadow-[#6545c4]/30">
            <i class="fas fa-utensils"></i>
        </div>
    </div>

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Available</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $availableItems }}</h3>
        </div>

        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm bg-gradient-to-br from-emerald-300 to-[#17966e] shadow-md shadow-[#17966e]/30">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Featured</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $featuredItems }}</h3>
        </div>

        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm bg-gradient-to-br from-amber-200 to-[#c78c28] shadow-md shadow-[#c78c28]/30">
            <i class="fas fa-star"></i>
        </div>
    </div>

</div>

<!-- MENU ITEMS TABLE -->
<div class="surface min-w-0">

    <div class="p-4 border-b flex flex-col md:flex-row gap-3 md:items-center md:justify-between"
         style="border-color:var(--border)">

        <div>
            <h3 class="font-bold text-base">All Menu Items</h3>
            <p class="text-[11px] muted mt-0.5">
                View, search and manage your restaurant menu.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2.5">

            <div class="relative sm:w-60">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 muted text-[11px]"></i>

                <input type="text"
                       id="menuSearch"
                       placeholder="Search menu items..."
                       class="w-full min-h-10 rounded-xl border border-[var(--border)] bg-[var(--surface)] pl-8 pr-3 text-xs text-[var(--text)] shadow-sm outline-none transition focus:border-[var(--gold)] focus:ring-4 focus:ring-amber-400/20">
            </div>

            {{-- Custom category dropdown --}}
            <div class="group/dd relative w-full sm:w-48" id="categoryDropdown" data-open="false">

                <input type="hidden" id="categoryFilter" value="">

                <button type="button"
                        id="categoryDropdownBtn"
                        class="flex min-h-10 w-full cursor-pointer items-center justify-between gap-2.5 rounded-xl border border-[var(--border)] bg-[var(--surface)] px-3 text-xs font-semibold text-[var(--text)] shadow-sm outline-none transition hover:border-[var(--gold)] hover:ring-4 hover:ring-amber-400/20 focus-visible:border-[var(--gold)] focus-visible:ring-4 focus-visible:ring-amber-400/20 group-data-[open=true]/dd:border-[var(--gold)] group-data-[open=true]/dd:ring-4 group-data-[open=true]/dd:ring-amber-400/20"
                        aria-haspopup="listbox"
                        aria-expanded="false">

                    <span class="flex min-w-0 items-center gap-2">
                        <i class="fas fa-layer-group text-[11px] gold"></i>
                        <span id="categoryDropdownLabel" class="truncate">All Categories</span>
                    </span>

                    <i class="fas fa-chevron-down text-[10px] muted transition duration-200 group-data-[open=true]/dd:rotate-180 group-data-[open=true]/dd:text-amber-500"></i>
                </button>

                <div class="pointer-events-none invisible absolute left-0 right-0 top-[calc(100%+6px)] z-[60] max-h-[260px] min-w-full origin-top -translate-y-1.5 scale-95 overflow-y-auto rounded-2xl border border-[var(--border)] bg-[var(--surface)] p-1.5 opacity-0 shadow-2xl shadow-black/20 transition duration-150 group-data-[open=true]/dd:pointer-events-auto group-data-[open=true]/dd:visible group-data-[open=true]/dd:translate-y-0 group-data-[open=true]/dd:scale-100 group-data-[open=true]/dd:opacity-100"
                     role="listbox" aria-label="Filter by category">

                    <button type="button"
                            role="option"
                            aria-selected="true"
                            data-selected="true"
                            data-value=""
                            class="cat-option group/opt flex w-full cursor-pointer items-center justify-between gap-2.5 rounded-lg px-2.5 py-2 text-left text-xs font-semibold text-[var(--text)] outline-none transition hover:bg-amber-400/15 focus-visible:bg-amber-400/15 data-[selected=true]:bg-gradient-to-r data-[selected=true]:from-amber-400/25 data-[selected=true]:to-amber-400/5 data-[selected=true]:text-amber-700 dark:data-[selected=true]:text-amber-400">
                        <span>All Categories</span>
                        <i class="fas fa-check text-[10px] text-amber-500 opacity-0 group-data-[selected=true]/opt:opacity-100"></i>
                    </button>

                    @foreach($categories as $category)
                        <button type="button"
                                role="option"
                                aria-selected="false"
                                data-selected="false"
                                data-value="{{ strtolower($category->name) }}"
                                class="cat-option group/opt mt-0.5 flex w-full cursor-pointer items-center justify-between gap-2.5 rounded-lg px-2.5 py-2 text-left text-xs font-semibold text-[var(--text)] outline-none transition hover:bg-amber-400/15 focus-visible:bg-amber-400/15 data-[selected=true]:bg-gradient-to-r data-[selected=true]:from-amber-400/25 data-[selected=true]:to-amber-400/5 data-[selected=true]:text-amber-700 dark:data-[selected=true]:text-amber-400">
                            <span class="truncate">{{ $category->name }}</span>
                            <i class="fas fa-check text-[10px] text-amber-500 opacity-0 group-data-[selected=true]/opt:opacity-100"></i>
                        </button>
                    @endforeach

                </div>

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
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-xs shrink-0 bg-gradient-to-br from-violet-400 to-[#6545c4]">
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
                                   class="{{ $btn }} {{ $btnView }}"
                                   title="View"
                                   aria-label="View {{ $item->name }}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.menu-items.edit', $item) }}"
                                   class="{{ $btn }} {{ $btnEdit }}"
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
                                            class="{{ $btn }} {{ $btnDelete }}"
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
     class="fixed inset-0 z-[100] hidden place-items-center bg-[#09120e]/60 p-4 backdrop-blur-sm"
     aria-hidden="true"
     onclick="if (event.target === this) closeMenuDeleteDialog()">

    <section class="w-full max-w-[320px] rounded-2xl border border-[var(--border)] bg-[var(--surface)] p-[18px] text-[var(--text)] shadow-2xl shadow-black/30"
             role="dialog" aria-modal="true" aria-labelledby="menuDeleteTitle">

        <div class="mb-3 grid h-10 w-10 place-items-center rounded-xl bg-red-500/10 text-sm text-red-500">
            <i class="fas fa-trash-alt"></i>
        </div>

        <h2 id="menuDeleteTitle" class="text-base font-extrabold">Delete menu item?</h2>

        <p class="muted mt-1.5 text-xs leading-5">
            This will permanently remove the menu item. This action cannot be undone.
        </p>

        <div class="mt-3 flex items-center gap-2.5 rounded-xl border border-[var(--border)] bg-[var(--bg)] px-3 py-2.5">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-violet-400 to-[#6545c4] text-xs text-white">
                <i class="fas fa-utensils"></i>
            </div>
            <div class="min-w-0">
                <p id="menuDeleteName" class="truncate text-[13px] font-bold leading-tight"></p>
                <p id="menuDeleteCategory" class="muted mt-0.5 truncate text-[10px]"></p>
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <button type="button"
                    class="menu-delete-cancel min-h-9 rounded-[10px] border border-[var(--border)] bg-[var(--surface)] px-3.5 text-xs font-bold text-[var(--text)] transition hover:bg-[var(--bg)] focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400"
                    onclick="closeMenuDeleteDialog()">
                Cancel
            </button>

            <button type="button"
                    class="min-h-9 rounded-[10px] bg-gradient-to-br from-rose-400 to-red-600 px-3.5 text-xs font-bold text-white shadow-md shadow-red-600/25 transition hover:-translate-y-px hover:shadow-lg hover:shadow-red-600/35"
                    onclick="confirmMenuDelete()">
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
        overlay.classList.add('grid');
        overlay.setAttribute('aria-hidden', 'false');
        overlay.querySelector('.menu-delete-cancel').focus();
    }

    function closeMenuDeleteDialog() {
        const overlay = document.getElementById('menuDeleteOverlay');
        overlay.classList.add('hidden');
        overlay.classList.remove('grid');
        overlay.setAttribute('aria-hidden', 'true');
        menuDeleteForm = null;
        menuDeleteTrigger?.focus();
    }

    function confirmMenuDelete() {
        if (!menuDeleteForm) return;

        menuDeleteForm.submit();
    }

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
            noResults.classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
        }
    }

    menuSearch?.addEventListener('input', filterMenuItems);

    // Custom category dropdown (state lives in data-open / data-selected)
    const catDropdown = document.getElementById('categoryDropdown');
    const catButton = document.getElementById('categoryDropdownBtn');
    const catLabel = document.getElementById('categoryDropdownLabel');
    const catOptions = [...catDropdown.querySelectorAll('.cat-option')];

    const isCatOpen = () => catDropdown.dataset.open === 'true';

    function setCatDropdown(open) {
        catDropdown.dataset.open = String(open);
        catButton.setAttribute('aria-expanded', String(open));
    }

    function chooseCategory(option) {
        categoryFilter.value = option.dataset.value;
        catLabel.textContent = option.querySelector('span').textContent.trim();

        catOptions.forEach(o => {
            const active = o === option;
            o.dataset.selected = String(active);
            o.setAttribute('aria-selected', String(active));
        });

        setCatDropdown(false);
        filterMenuItems();
    }

    catButton.addEventListener('click', () => setCatDropdown(!isCatOpen()));

    catOptions.forEach(option => {
        option.addEventListener('click', () => chooseCategory(option));
    });

    document.addEventListener('click', event => {
        if (!catDropdown.contains(event.target)) setCatDropdown(false);
    });

    catDropdown.addEventListener('keydown', event => {
        if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') return;

        event.preventDefault();
        setCatDropdown(true);

        const current = catOptions.indexOf(document.activeElement);
        const selected = catOptions.findIndex(o => o.dataset.selected === 'true');
        const step = event.key === 'ArrowDown' ? 1 : -1;
        const next = current === -1
            ? Math.max(selected, 0)
            : (current + step + catOptions.length) % catOptions.length;

        catOptions[next].focus();
    });

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') return;

        if (isCatOpen()) {
            setCatDropdown(false);
            catButton.focus();
        }

        if (!document.getElementById('menuDeleteOverlay').classList.contains('hidden')) {
            closeMenuDeleteDialog();
        }
    });
</script>
@endpush