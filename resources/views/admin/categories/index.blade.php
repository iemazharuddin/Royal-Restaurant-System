@extends('layouts.admin')

@section('title', 'Categories')
@section('page-heading', 'Categories Management')

@push('styles')
<style>
    .category-search,
    .category-status {
        min-height: 40px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        color: var(--text);
        font-size: 12px;
        box-shadow: 0 3px 10px rgba(17, 28, 24, .04);
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .category-search { width: 100%; padding-right: 12px; }
    .category-search:focus,
    .category-status:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(214, 173, 105, .2);
    }
    .category-status {
        width: 100%; appearance: none; padding: 0 34px 0 12px; cursor: pointer;
    }
    .category-status-wrap { position: relative; width: 100%; }
    .category-status-wrap .fa-chevron-down {
        position: absolute; top: 50%; right: 13px; color: var(--muted);
        font-size: 10px; pointer-events: none; transform: translateY(-50%);
    }

    .category-delete-overlay {
        position: fixed; inset: 0; z-index: 100; display: grid; place-items: center;
        padding: 16px; background: rgba(9, 18, 14, .6); backdrop-filter: blur(4px);
    }
    .category-delete-overlay.hidden { display: none; }
    .category-delete-dialog {
        width: min(100%, 320px); padding: 18px; border: 1px solid var(--border);
        border-radius: 16px; background: var(--surface); color: var(--text);
        box-shadow: 0 24px 70px rgba(9, 18, 14, .26);
        animation: category-dialog-enter .18s ease-out;
    }
    .category-delete-icon {
        width: 38px; height: 38px; border-radius: 12px; font-size: 14px;
        display: grid; place-items: center; color: #dc3545; background: rgba(239, 68, 68, .11);
    }
    .category-delete-target {
        display: flex; align-items: center; gap: 10px; margin-top: 12px;
        padding: 9px 11px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg);
    }
    .category-delete-cancel,
    .category-delete-confirm {
        min-height: 36px; padding: 0 13px; border-radius: 10px;
        font-size: 12px; font-weight: 700; transition: .2s ease;
    }
    .category-delete-cancel { border: 1px solid var(--border); color: var(--text); background: var(--surface); }
    .category-delete-cancel:hover { background: var(--bg); }
    .category-delete-confirm {
        color: white; background: linear-gradient(135deg, #ef6472, #c92f49);
        box-shadow: 0 6px 14px rgba(201, 47, 73, .2);
    }
    .category-delete-confirm:hover { transform: translateY(-1px); box-shadow: 0 8px 18px rgba(201, 47, 73, .28); }

    /* Compact, colored Show / Edit / Delete buttons */
    .category-row .admin-action-btn {
        width: 30px; height: 30px; border-radius: 9px; font-size: 12px;
        display: inline-flex; align-items: center; justify-content: center;
        transition: background .18s ease, border-color .18s ease, transform .18s ease, box-shadow .18s ease;
    }
    .category-row .admin-action-btn.admin-action-view {
        background: #1685e8 !important; color: #fff !important; border: 1px solid #1685e8 !important;
        box-shadow: 0 3px 8px rgba(22, 133, 232, .18);
    }
    .category-row .admin-action-btn.admin-action-view:hover {
        background: #086fc8 !important; border-color: #086fc8 !important; color: #fff !important;
        transform: translateY(-1px); box-shadow: 0 5px 12px rgba(22, 133, 232, .25);
    }
    .category-row .admin-action-btn.admin-action-edit {
        background: #f59e0b !important; color: #fff !important; border: 1px solid #f59e0b !important;
        box-shadow: 0 3px 8px rgba(245, 158, 11, .18);
    }
    .category-row .admin-action-btn.admin-action-edit:hover {
        background: #d97706 !important; border-color: #d97706 !important; color: #fff !important;
        transform: translateY(-1px); box-shadow: 0 5px 12px rgba(217, 119, 6, .25);
    }
    .category-row .admin-action-btn.admin-action-delete {
        background: #ef4444 !important; color: #fff !important; border: 1px solid #ef4444 !important;
        box-shadow: 0 3px 8px rgba(239, 68, 68, .18);
    }
    .category-row .admin-action-btn.admin-action-delete:hover {
        background: #dc2626 !important; border-color: #dc2626 !important; color: #fff !important;
        transform: translateY(-1px); box-shadow: 0 5px 12px rgba(220, 38, 38, .25);
    }

    @keyframes category-dialog-enter {
        from { opacity: 0; transform: translateY(6px) scale(.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @media (min-width: 640px) {
        .category-search-wrap { width: 230px; }
        .category-status-wrap { width: 140px; }
    }
</style>
@endpush

@section('content')

@php
    $allCategories = $categories ?? collect();
    $totalCategories = method_exists($allCategories, 'total')
        ? $allCategories->total()
        : $allCategories->count();
    $activeCategories = $allCategories->where('is_active', true)->count();
    $inactiveCategories = max(0, $totalCategories - $activeCategories);
@endphp

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

@if(session('success'))
    <div class="mb-4 p-3 rounded-xl bg-green-500/10 text-green-600 text-xs">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="mb-4 p-3 rounded-xl bg-red-500/10 text-red-500 text-xs">{{ $errors->first() }}</div>
@endif

<div class="grid grid-cols-3 gap-2 sm:gap-3 mb-5">
    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Total</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $totalCategories }}</h3>
        </div>
        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm"
             style="background:linear-gradient(145deg,#efd39a,#a17b41);box-shadow:0 5px 12px #a17b4133;">
            <i class="fas fa-layer-group"></i>
        </div>
    </div>
    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Active</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $activeCategories }}</h3>
        </div>
        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm"
             style="background:linear-gradient(145deg,#5fe0b0,#17966e);box-shadow:0 5px 12px #17966e33;">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>
    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Inactive</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $inactiveCategories }}</h3>
        </div>
        <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-xl items-center justify-center text-white text-sm"
             style="background:linear-gradient(145deg,#ff9a9e,#d83d57);box-shadow:0 5px 12px #d83d5733;">
            <i class="fas fa-ban"></i>
        </div>
    </div>
</div>

<div class="surface min-w-0 overflow-hidden">
    <div class="p-4 border-b flex flex-col md:flex-row gap-3 md:items-center md:justify-between"
         style="border-color:var(--border)">
        <div>
            <h3 class="font-bold text-base">All Categories</h3>
            <p class="text-[11px] muted mt-0.5">View, search and manage your categories.</p>
        </div>
        <div class="category-filter-controls flex flex-col sm:flex-row gap-2.5">
            <div class="category-search-wrap relative">
                <i class="fas fa-search absolute left-3 top-3 muted text-[11px]"></i>
                <input type="text" id="searchInput" placeholder="Search categories..." class="category-search pl-8">
            </div>
            <div class="category-status-wrap">
                <select id="statusFilter" class="category-status">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <i class="fas fa-chevron-down"></i>
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
                        <td class="px-4 py-2.5">
                            <div class="flex items-center gap-2.5">
                                @if($category->image)
                                    <img src="{{ asset('storage/'.$category->image) }}" class="w-9 h-9 rounded-lg object-cover shrink-0" alt="{{ $category->name }}">
                                @else
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center text-gold text-xs shrink-0"
                                         style="background:linear-gradient(145deg,#2c4236,#111c18)">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-bold text-[13px] leading-tight">{{ $category->name }}</p>
                                    <p class="text-[10px] muted mt-0.5">{{ $category->slug ?? 'No slug' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2.5 muted max-w-[220px]">
                            {{ \Illuminate\Support\Str::limit($category->description ?? 'No description available', 55) }}
                        </td>
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
                        <td class="px-4 py-2.5 muted whitespace-nowrap">{{ $category->created_at?->format('d M Y') ?? '—' }}</td>
                        <td class="px-4 py-2.5">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.categories.show', $category) }}"
                                   class="admin-action-btn admin-action-view" title="Show category"
                                   aria-label="Show {{ $category->name }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="admin-action-btn admin-action-edit" title="Edit"
                                   aria-label="Edit {{ $category->name }}">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}"
                                      method="POST" data-category-delete="{{ $category->name }}"
                                      data-category-slug="{{ $category->slug ?? '' }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="openCategoryDeleteDialog(this.form)"
                                            class="admin-action-btn admin-action-delete" title="Delete"
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

<div id="categoryDeleteOverlay" class="category-delete-overlay hidden" aria-hidden="true"
     onclick="if (event.target === this) closeCategoryDeleteDialog()">
    <section class="category-delete-dialog" role="dialog" aria-modal="true" aria-labelledby="categoryDeleteTitle">
        <div class="category-delete-icon mb-3"><i class="fas fa-trash-alt"></i></div>
        <h2 id="categoryDeleteTitle" class="text-base font-extrabold">Delete category?</h2>
        <p class="muted text-xs mt-1.5 leading-5">This will permanently remove the category. This action cannot be undone.</p>
        <div class="category-delete-target">
            <div class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center text-gold text-xs"
                 style="background:linear-gradient(145deg,#2c4236,#111c18)">
                <i class="fas fa-utensils"></i>
            </div>
            <div class="min-w-0">
                <p id="categoryDeleteName" class="font-bold text-[13px] leading-tight truncate"></p>
                <p id="categoryDeleteSlug" class="text-[10px] muted mt-0.5 truncate"></p>
            </div>
        </div>
        <div class="flex justify-end gap-2 mt-4">
            <button type="button" class="category-delete-cancel" onclick="closeCategoryDeleteDialog()">Cancel</button>
            <button type="button" class="category-delete-confirm" onclick="confirmCategoryDelete()">
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
        overlay.setAttribute('aria-hidden', 'false');
        overlay.querySelector('.category-delete-cancel').focus();
    }

    function closeCategoryDeleteDialog() {
        const overlay = document.getElementById('categoryDeleteOverlay');
        overlay.classList.add('hidden');
        overlay.setAttribute('aria-hidden', 'true');
        categoryDeleteForm = null;
        categoryDeleteTrigger?.focus();
    }

    function confirmCategoryDelete() {
        if (!categoryDeleteForm) return;
        categoryDeleteForm.submit();
    }

    document.addEventListener('keydown', event => {
        const overlay = document.getElementById('categoryDeleteOverlay');
        if (event.key === 'Escape' && overlay && !overlay.classList.contains('hidden')) {
            closeCategoryDeleteDialog();
        }
    });

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
    statusFilter?.addEventListener('change', filterCategories);
</script>
@endpush
