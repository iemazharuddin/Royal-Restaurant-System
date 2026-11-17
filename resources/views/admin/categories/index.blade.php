@extends('layouts.admin')

@section('title', 'Categories')
@section('page-heading', 'Categories Management')

@section('content')

@php
    $allCategories = $categories ?? collect();
    $totalCategories = method_exists($allCategories, 'total')
        ? $allCategories->total()
        : $allCategories->count();
    $activeCategories = $allCategories->where('is_active', true)->count();
    $inactiveCategories = max(0, $totalCategories - $activeCategories);

    // Shared icon-button style (Tailwind only)
    $btn = 'inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs text-white shadow-sm ring-1 ring-inset ring-white/20 transition duration-200 hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2';
    $btnView   = 'bg-gradient-to-br from-sky-400 to-blue-600 shadow-blue-500/30 hover:shadow-blue-500/40 focus-visible:ring-sky-400';
    $btnEdit   = 'bg-gradient-to-br from-amber-400 to-orange-500 shadow-orange-500/30 hover:shadow-orange-500/40 focus-visible:ring-amber-400';
    $btnDelete = 'bg-gradient-to-br from-rose-400 to-red-600 shadow-red-500/30 hover:shadow-red-500/40 focus-visible:ring-red-400';

    $statusOpts = [
        ['', 'All Status', '#94a3b8'],
        ['active', 'Active', '#16a34a'],
        ['inactive', 'Inactive', '#ef4444'],
    ];
@endphp

<!-- PAGE INTRO -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
    <div>
        <p class="text-[10px] gold font-bold uppercase tracking-widest">Management</p>
        <h1 class="text-xl sm:text-2xl font-extrabold mt-1" style="font-family:'Playfair Display',serif;">Food Categories</h1>
        <p class="text-xs muted mt-1">Organize and manage your restaurant menu categories.</p>
    </div>
    <a href="{{ route('admin.categories.create') }}"
       class="admin-add-btn px-4 py-2.5 rounded-xl text-xs inline-flex items-center justify-center gap-2 self-start md:self-auto">
        <i class="fas fa-plus"></i> Add New Category
    </a>
</div>

<!-- FLASH MESSAGES -->
@if(session('success'))
    <div class="mb-4 p-3 rounded-xl bg-green-500/10 text-green-600 text-xs">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="mb-4 p-3 rounded-xl bg-red-500/10 text-red-500 text-xs">{{ $errors->first() }}</div>
@endif

<!-- STAT CARDS -->
<div class="grid grid-cols-3 gap-2 sm:gap-3 mb-5">
    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Total</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $totalCategories }}</h3>
        </div>
        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm bg-gradient-to-br from-[#efd39a] to-[#a17b41] shadow-md shadow-[#a17b41]/30">
            <i class="fas fa-layer-group"></i>
        </div>
    </div>
    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Active</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $activeCategories }}</h3>
        </div>
        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm bg-gradient-to-br from-emerald-300 to-[#17966e] shadow-md shadow-[#17966e]/30">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>
    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Inactive</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $inactiveCategories }}</h3>
        </div>
        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm bg-gradient-to-br from-rose-300 to-[#d83d57] shadow-md shadow-[#d83d57]/30">
            <i class="fas fa-ban"></i>
        </div>
    </div>
</div>

<!-- CATEGORIES TABLE -->
<div class="surface min-w-0">

    <div class="p-4 border-b flex flex-col md:flex-row gap-3 md:items-center md:justify-between"
         style="border-color:var(--border)">
        <div>
            <h3 class="font-bold text-base">All Categories</h3>
            <p class="text-[11px] muted mt-0.5">View, search and manage your categories.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2.5">

            <div class="relative sm:w-60">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 muted text-[11px]"></i>
                <input type="text"
                       id="searchInput"
                       placeholder="Search categories..."
                       class="w-full min-h-10 rounded-xl border border-[var(--border)] bg-[var(--surface)] pl-8 pr-3 text-xs text-[var(--text)] shadow-sm outline-none transition focus:border-[var(--gold)] focus:ring-4 focus:ring-amber-400/20">
            </div>

            {{-- Custom status dropdown --}}
            <div class="group/dd relative w-full sm:w-40" id="statusDropdown" data-open="false">

                <input type="hidden" id="statusFilter" value="">

                <button type="button"
                        id="statusDropdownBtn"
                        class="flex min-h-10 w-full cursor-pointer items-center justify-between gap-2.5 rounded-xl border border-[var(--border)] bg-[var(--surface)] px-3 text-xs font-semibold text-[var(--text)] shadow-sm outline-none transition hover:border-[var(--gold)] hover:ring-4 hover:ring-amber-400/20 focus-visible:border-[var(--gold)] focus-visible:ring-4 focus-visible:ring-amber-400/20 group-data-[open=true]/dd:border-[var(--gold)] group-data-[open=true]/dd:ring-4 group-data-[open=true]/dd:ring-amber-400/20"
                        aria-haspopup="listbox"
                        aria-expanded="false">

                    <span class="flex min-w-0 items-center gap-2">
                        <span class="h-2 w-2 shrink-0 rounded-full" id="statusDropdownDot" style="background:#94a3b8"></span>
                        <span id="statusDropdownLabel" class="truncate">All Status</span>
                    </span>

                    <i class="fas fa-chevron-down text-[10px] muted transition duration-200 group-data-[open=true]/dd:rotate-180 group-data-[open=true]/dd:text-amber-500"></i>
                </button>

                <div class="pointer-events-none invisible absolute left-0 right-0 top-[calc(100%+6px)] z-[60] min-w-full origin-top -translate-y-1.5 scale-95 rounded-2xl border border-[var(--border)] bg-[var(--surface)] p-1.5 opacity-0 shadow-2xl shadow-black/20 transition duration-150 group-data-[open=true]/dd:pointer-events-auto group-data-[open=true]/dd:visible group-data-[open=true]/dd:translate-y-0 group-data-[open=true]/dd:scale-100 group-data-[open=true]/dd:opacity-100"
                     role="listbox" aria-label="Filter by status">

                    @foreach($statusOpts as [$val, $label, $color])
                        <button type="button"
                                role="option"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                data-selected="{{ $loop->first ? 'true' : 'false' }}"
                                data-value="{{ $val }}"
                                data-color="{{ $color }}"
                                class="status-option group/opt flex w-full cursor-pointer items-center gap-2 rounded-lg px-2.5 py-2 text-left text-xs font-semibold text-[var(--text)] outline-none transition hover:bg-amber-400/15 focus-visible:bg-amber-400/15 data-[selected=true]:bg-gradient-to-r data-[selected=true]:from-amber-400/25 data-[selected=true]:to-amber-400/5 data-[selected=true]:text-amber-700 dark:data-[selected=true]:text-amber-400 [&:not(:first-child)]:mt-0.5">
                            <span class="h-2 w-2 shrink-0 rounded-full" style="background:{{ $color }}"></span>
                            <span>{{ $label }}</span>
                            <i class="fas fa-check ml-auto text-[10px] text-amber-500 opacity-0 group-data-[selected=true]/opt:opacity-100"></i>
                        </button>
                    @endforeach

                </div>

            </div>

        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="admin-table w-full text-left text-xs min-w-[640px]" id="categoryTable">
            <thead>
                <tr>
                    <th class="px-4 py-3 muted font-bold">Category</th>
                    <th class="px-4 py-3 muted font-bold">Description</th>
                    <th class="px-4 py-3 muted font-bold">Status</th>
                    <th class="px-4 py-3 muted font-bold">Created</th>
                    <th class="px-4 py-3 muted font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="category-row border-b" style="border-color:var(--border)"
                        data-name="{{ strtolower($category->name) }}"
                        data-status="{{ $category->is_active ? 'active' : 'inactive' }}">

                        <!-- CATEGORY -->
                        <td class="px-4 py-2.5">
                            <div class="flex items-center gap-2.5">
                                @if($category->image)
                                    <img src="{{ asset('storage/'.$category->image) }}" class="w-9 h-9 rounded-lg object-cover shrink-0" alt="{{ $category->name }}">
                                @else
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center text-gold text-xs shrink-0 bg-gradient-to-br from-[#2c4236] to-[#111c18]">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-bold text-[13px] leading-tight">{{ $category->name }}</p>
                                    <p class="text-[10px] muted mt-0.5">{{ $category->slug ?? 'No slug' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- DESCRIPTION -->
                        <td class="px-4 py-2.5 muted max-w-[220px]">
                            {{ \Illuminate\Support\Str::limit($category->description ?? 'No description available', 55) }}
                        </td>

                        <!-- STATUS -->
                        <td class="px-4 py-2.5">
                            @if($category->is_active)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-500/10 text-green-600 whitespace-nowrap">
                                    <i class="fas fa-circle text-[5px] mr-1"></i> Active
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-500/10 text-red-500 whitespace-nowrap">
                                    <i class="fas fa-circle text-[5px] mr-1"></i> Inactive
                                </span>
                            @endif
                        </td>

                        <!-- CREATED -->
                        <td class="px-4 py-2.5 muted whitespace-nowrap">{{ $category->created_at?->format('d M Y') ?? '—' }}</td>

                        <!-- ACTIONS -->
                        <td class="px-4 py-2.5">
                            <div class="flex items-center justify-end gap-1.5">

                                <a href="{{ route('admin.categories.show', $category) }}"
                                   class="{{ $btn }} {{ $btnView }}"
                                   title="Show category"
                                   aria-label="Show {{ $category->name }}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="{{ $btn }} {{ $btnEdit }}"
                                   title="Edit"
                                   aria-label="Edit {{ $category->name }}">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <form action="{{ route('admin.categories.destroy', $category) }}"
                                      method="POST"
                                      data-category-delete="{{ $category->name }}"
                                      data-category-slug="{{ $category->slug ?? '' }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="openCategoryDeleteDialog(this.form)"
                                            class="{{ $btn }} {{ $btnDelete }}"
                                            title="Delete"
                                            aria-label="Delete {{ $category->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10">
                            <div class="text-3xl muted mb-3"><i class="fas fa-layer-group"></i></div>
                            <h3 class="font-bold text-base">No Categories Found</h3>
                            <p class="text-xs muted mt-1.5">Start by adding your first restaurant category.</p>
                            <a href="{{ route('admin.categories.create') }}"
                               class="admin-add-btn inline-flex mt-4 px-4 py-2.5 rounded-xl text-xs">
                                <i class="fas fa-plus mr-2"></i> Add Category
                            </a>
                        </td>
                    </tr>
                @endforelse
                <tr id="noSearchResults" class="hidden">
                    <td colspan="5" class="text-center py-8 text-xs muted">No matching categories found.</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if(method_exists($categories, 'links'))
        <div class="p-4 border-t text-xs" style="border-color:var(--border)">
            {{ $categories->links() }}
        </div>
    @endif
</div>

<!-- DELETE CARD -->
<div id="categoryDeleteOverlay"
     class="fixed inset-0 z-[100] hidden place-items-center bg-[#09120e]/60 p-4 backdrop-blur-sm"
     aria-hidden="true"
     onclick="if (event.target === this) closeCategoryDeleteDialog()">

    <section class="w-full max-w-[320px] rounded-2xl border border-[var(--border)] bg-[var(--surface)] p-[18px] text-[var(--text)] shadow-2xl shadow-black/30"
             role="dialog" aria-modal="true" aria-labelledby="categoryDeleteTitle">

        <div class="mb-3 grid h-10 w-10 place-items-center rounded-xl bg-red-500/10 text-sm text-red-500">
            <i class="fas fa-trash-alt"></i>
        </div>

        <h2 id="categoryDeleteTitle" class="text-base font-extrabold">Delete category?</h2>

        <p class="muted mt-1.5 text-xs leading-5">
            This will permanently remove the category. This action cannot be undone.
        </p>

        <div class="mt-3 flex items-center gap-2.5 rounded-xl border border-[var(--border)] bg-[var(--bg)] px-3 py-2.5">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#2c4236] to-[#111c18] text-xs text-gold">
                <i class="fas fa-utensils"></i>
            </div>
            <div class="min-w-0">
                <p id="categoryDeleteName" class="truncate text-[13px] font-bold leading-tight"></p>
                <p id="categoryDeleteSlug" class="muted mt-0.5 truncate text-[10px]"></p>
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <button type="button"
                    class="category-delete-cancel min-h-9 rounded-[10px] border border-[var(--border)] bg-[var(--surface)] px-3.5 text-xs font-bold text-[var(--text)] transition hover:bg-[var(--bg)] focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400"
                    onclick="closeCategoryDeleteDialog()">
                Cancel
            </button>

            <button type="button"
                    class="min-h-9 rounded-[10px] bg-gradient-to-br from-rose-400 to-red-600 px-3.5 text-xs font-bold text-white shadow-md shadow-red-600/25 transition hover:-translate-y-px hover:shadow-lg hover:shadow-red-600/35"
                    onclick="confirmCategoryDelete()">
                <i class="fas fa-trash-alt mr-1"></i> Delete
            </button>
        </div>

    </section>
</div>

@endsection

@push('scripts')
<script>
    let categoryDeleteForm = null;
    let categoryDeleteTrigger = null;

    function openCategoryDeleteDialog(form) {
        categoryDeleteForm = form;
        categoryDeleteTrigger = document.activeElement;

        const overlay = document.getElementById('categoryDeleteOverlay');
        document.getElementById('categoryDeleteName').textContent = form.dataset.categoryDelete;
        document.getElementById('categoryDeleteSlug').textContent = form.dataset.categorySlug || 'No slug';

        overlay.classList.remove('hidden');
        overlay.classList.add('grid');
        overlay.setAttribute('aria-hidden', 'false');
        overlay.querySelector('.category-delete-cancel').focus();
    }

    function closeCategoryDeleteDialog() {
        const overlay = document.getElementById('categoryDeleteOverlay');
        overlay.classList.add('hidden');
        overlay.classList.remove('grid');
        overlay.setAttribute('aria-hidden', 'true');
        categoryDeleteForm = null;
        categoryDeleteTrigger?.focus();
    }

    function confirmCategoryDelete() {
        if (!categoryDeleteForm) return;
        categoryDeleteForm.submit();
    }

    // Search and status filter
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');

    function filterCategories() {
        const search = searchInput.value.toLowerCase().trim();
        const status = statusFilter.value;
        const rows = document.querySelectorAll('.category-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.dataset.name || '';
            const rowStatus = row.dataset.status || '';
            const show = name.includes(search) && (!status || rowStatus === status);
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        const noResults = document.getElementById('noSearchResults');
        if (noResults) {
            noResults.classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
        }
    }

    searchInput?.addEventListener('input', filterCategories);

    // Custom status dropdown (state lives in data-open / data-selected)
    const statusDropdown = document.getElementById('statusDropdown');
    const statusButton = document.getElementById('statusDropdownBtn');
    const statusLabel = document.getElementById('statusDropdownLabel');
    const statusDot = document.getElementById('statusDropdownDot');
    const statusOptions = [...statusDropdown.querySelectorAll('.status-option')];

    const isDropdownOpen = () => statusDropdown.dataset.open === 'true';

    function setStatusDropdown(open) {
        statusDropdown.dataset.open = String(open);
        statusButton.setAttribute('aria-expanded', String(open));
    }

    function chooseStatus(option) {
        statusFilter.value = option.dataset.value;
        statusLabel.textContent = option.querySelector('span:not(.rounded-full)').textContent.trim();
        statusDot.style.background = option.dataset.color;

        statusOptions.forEach(o => {
            const active = o === option;
            o.dataset.selected = String(active);
            o.setAttribute('aria-selected', String(active));
        });

        setStatusDropdown(false);
        filterCategories();
    }

    statusButton.addEventListener('click', () => setStatusDropdown(!isDropdownOpen()));

    statusOptions.forEach(option => {
        option.addEventListener('click', () => chooseStatus(option));
    });

    document.addEventListener('click', event => {
        if (!statusDropdown.contains(event.target)) setStatusDropdown(false);
    });

    statusDropdown.addEventListener('keydown', event => {
        if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') return;

        event.preventDefault();
        setStatusDropdown(true);

        const current = statusOptions.indexOf(document.activeElement);
        const selected = statusOptions.findIndex(o => o.dataset.selected === 'true');
        const step = event.key === 'ArrowDown' ? 1 : -1;
        const next = current === -1
            ? Math.max(selected, 0)
            : (current + step + statusOptions.length) % statusOptions.length;

        statusOptions[next].focus();
    });

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') return;

        if (isDropdownOpen()) {
            setStatusDropdown(false);
            statusButton.focus();
        }

        if (!document.getElementById('categoryDeleteOverlay').classList.contains('hidden')) {
            closeCategoryDeleteDialog();
        }
    });
</script>
@endpush