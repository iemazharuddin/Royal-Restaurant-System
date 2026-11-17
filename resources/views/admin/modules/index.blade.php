@extends('layouts.admin')

@section('title', $title)
@section('page-heading', $title)

@push('styles')
<style>
    .resource-search {
        width: 100%;
        min-height: 40px;
        padding-right: 12px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        color: var(--text);
        font-size: 12px;
        box-shadow: 0 3px 10px rgba(17, 28, 24, .04);
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .resource-search:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(214, 173, 105, .2);
    }

    /* DELETE CARD (compact) */
    .resource-delete-overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: grid;
        place-items: center;
        padding: 16px;
        background: rgba(9, 18, 14, .6);
        backdrop-filter: blur(4px);
    }

    .resource-delete-overlay.hidden { display: none; }

    .resource-delete-dialog {
        width: min(100%, 320px);
        padding: 18px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: var(--surface);
        color: var(--text);
        box-shadow: 0 24px 70px rgba(9, 18, 14, .26);
        animation: resource-dialog-enter .18s ease-out;
    }

    .resource-delete-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        font-size: 14px;
        display: grid;
        place-items: center;
        color: #dc3545;
        background: rgba(239, 68, 68, .11);
    }

    .resource-delete-target {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 12px;
        padding: 9px 11px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--bg);
    }

    .resource-delete-cancel,
    .resource-delete-confirm {
        min-height: 36px;
        padding: 0 13px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .resource-delete-cancel {
        border: 1px solid var(--border);
        color: var(--text);
        background: var(--surface);
    }

    .resource-delete-cancel:hover { background: var(--bg); }

    .resource-delete-confirm {
        color: white;
        background: linear-gradient(135deg, #ef6472, #c92f49);
        box-shadow: 0 6px 14px rgba(201, 47, 73, .2);
    }

    .resource-delete-confirm:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(201, 47, 73, .28);
    }

    /* Compact row action buttons */
    .resource-row .admin-action-btn {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background .18s ease, border-color .18s ease,
                    transform .18s ease, box-shadow .18s ease;
    }

    /* Blue: Show / View */
    .resource-row .admin-action-btn.admin-action-view {
        background: #1685e8 !important;
        color: #ffffff !important;
        border: 1px solid #1685e8 !important;
        box-shadow: 0 3px 8px rgba(22, 133, 232, .18);
    }

    .resource-row .admin-action-btn.admin-action-view:hover {
        background: #086fc8 !important;
        border-color: #086fc8 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(22, 133, 232, .25);
    }

    /* Orange: Edit */
    .resource-row .admin-action-btn.admin-action-edit {
        background: #f59e0b !important;
        color: #ffffff !important;
        border: 1px solid #f59e0b !important;
        box-shadow: 0 3px 8px rgba(245, 158, 11, .18);
    }

    .resource-row .admin-action-btn.admin-action-edit:hover {
        background: #d97706 !important;
        border-color: #d97706 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(217, 119, 6, .25);
    }

    /* Red: Delete */
    .resource-row .admin-action-btn.admin-action-delete {
        background: #ef4444 !important;
        color: #ffffff !important;
        border: 1px solid #ef4444 !important;
        box-shadow: 0 3px 8px rgba(239, 68, 68, .18);
    }

    .resource-row .admin-action-btn.admin-action-delete:hover {
        background: #dc2626 !important;
        border-color: #dc2626 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(220, 38, 38, .25);
    }

    @keyframes resource-dialog-enter {
        from { opacity: 0; transform: translateY(6px) scale(.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @media (min-width: 640px) {
        .resource-search-wrap { width: 260px; }
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
            {{ $title }}
        </h1>

        <p class="text-xs muted mt-1">{{ $description }}</p>
    </div>

    <a href="{{ route('admin.' . $resource . '.create') }}"
       class="admin-add-btn px-4 py-2.5 rounded-xl text-xs inline-flex items-center justify-center gap-2 self-start md:self-auto">
        <i class="fas fa-plus"></i>
        Add New Record
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

<!-- TABLE -->
<div class="surface min-w-0 overflow-hidden">

    <form method="GET"
          action="{{ route('admin.' . $resource . '.index') }}"
          class="p-4 border-b flex flex-col md:flex-row gap-3 md:items-center md:justify-between"
          style="border-color:var(--border)">

        <div>
            <h3 class="font-bold text-base">All {{ $title }}</h3>
            <p class="text-[11px] muted mt-0.5">{{ $records->total() }} record(s)</p>
        </div>

        <div class="flex gap-2">

            <div class="resource-search-wrap relative flex-1 md:flex-none">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 muted text-[11px]"></i>

                <input type="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search {{ strtolower($title) }}..."
                       class="resource-search pl-8">
            </div>

            <button type="submit"
                    class="admin-add-btn rounded-xl px-3.5 text-xs"
                    aria-label="Search">
                <i class="fas fa-search"></i>
            </button>

        </div>

    </form>

    <div class="overflow-x-auto">

        <table class="admin-table w-full text-left text-xs min-w-[640px]">

            <thead>
                <tr>
                    <th class="px-4 py-3 muted font-bold">#</th>
                    @foreach($columns as $column)
                        <th class="px-4 py-3 muted font-bold">{{ $column['label'] }}</th>
                    @endforeach
                    <th class="px-4 py-3 muted font-bold text-right">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($records as $record)

                    @php
                        $recordLabel = \Illuminate\Support\Str::limit(
                            (string) (data_get($record, $columns[0]['key'] ?? 'id') ?? ('Record #' . $record->id)),
                            60
                        );
                    @endphp

                    <tr class="resource-row border-b" style="border-color:var(--border)">

                        <td class="px-4 py-2.5 muted">
                            {{ $records->firstItem() + $loop->index }}
                        </td>

                        @foreach($columns as $column)
                            @php($value = data_get($record, $column['key']))

                            <td class="px-4 py-2.5">
                                @if(($column['type'] ?? null) === 'image' && $value)
                                    <img src="{{ asset('storage/' . $value) }}"
                                         alt="{{ $recordLabel }}"
                                         class="h-9 w-12 rounded-lg object-cover">
                                @elseif(str_starts_with($column['key'], 'is_'))
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold whitespace-nowrap {{ $value ? 'bg-green-500/10 text-green-600' : 'bg-gray-500/10 text-gray-500' }}">
                                        {{ $value ? 'Yes' : 'No' }}
                                    </span>
                                @elseif(is_array($value))
                                    {{ implode(', ', array_map(fn ($item) => is_scalar($item) ? $item : json_encode($item), $value)) }}
                                @elseif($value instanceof \DateTimeInterface)
                                    <span class="muted whitespace-nowrap">{{ $value->format('d M Y H:i') }}</span>
                                @elseif(in_array($column['key'], ['amount', 'total', 'subtotal', 'salary'], true) && is_numeric($value))
                                    <span class="font-semibold whitespace-nowrap">Rs. {{ number_format((float) $value, 2) }}</span>
                                @else
                                    {{ \Illuminate\Support\Str::limit((string) ($value ?? '—'), 90) }}
                                @endif
                            </td>
                        @endforeach

                        <td class="px-4 py-2.5">
                            <div class="flex items-center justify-end gap-1.5">

                                <a href="{{ route('admin.' . $resource . '.edit', $record->id) }}"
                                   class="admin-action-btn admin-action-edit"
                                   title="Edit"
                                   aria-label="Edit {{ $recordLabel }}">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.' . $resource . '.destroy', $record->id) }}"
                                      data-resource-delete="{{ $recordLabel }}"
                                      data-resource-type="{{ $title }}">

                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            onclick="openResourceDeleteDialog(this.form)"
                                            class="admin-action-btn admin-action-delete"
                                            title="Delete"
                                            aria-label="Delete {{ $recordLabel }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="{{ count($columns) + 2 }}" class="text-center py-10">

                            <div class="text-3xl muted mb-3">
                                <i class="fas fa-inbox"></i>
                            </div>

                            <h3 class="font-bold text-base">No {{ $title }} Found</h3>

                            <p class="text-xs muted mt-1.5">
                                Records will appear here once you add them.
                            </p>

                            <a href="{{ route('admin.' . $resource . '.create') }}"
                               class="admin-add-btn inline-flex mt-4 px-4 py-2.5 rounded-xl text-xs">
                                <i class="fas fa-plus mr-2"></i>
                                Add New Record
                            </a>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    @if($records->hasPages())
        <div class="p-4 border-t text-xs" style="border-color:var(--border)">
            {{ $records->links() }}
        </div>
    @endif

</div>

<!-- DELETE CARD -->
<div id="resourceDeleteOverlay"
     class="resource-delete-overlay hidden"
     aria-hidden="true"
     onclick="if (event.target === this) closeResourceDeleteDialog()">
    <section class="resource-delete-dialog" role="dialog" aria-modal="true" aria-labelledby="resourceDeleteTitle">

        <div class="resource-delete-icon mb-3">
            <i class="fas fa-trash-alt"></i>
        </div>

        <h2 id="resourceDeleteTitle" class="text-base font-extrabold">Delete record?</h2>

        <p class="muted text-xs mt-1.5 leading-5">
            This will permanently remove the record. This action cannot be undone.
        </p>

        <div class="resource-delete-target">
            <div class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center text-gold text-xs"
                 style="background:linear-gradient(145deg,#2c4236,#111c18)">
                <i class="fas fa-inbox"></i>
            </div>
            <div class="min-w-0">
                <p id="resourceDeleteName" class="font-bold text-[13px] leading-tight truncate"></p>
                <p id="resourceDeleteType" class="text-[10px] muted mt-0.5 truncate"></p>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <button type="button" class="resource-delete-cancel" onclick="closeResourceDeleteDialog()">
                Cancel
            </button>
            <button type="button" class="resource-delete-confirm" onclick="confirmResourceDelete()">
                <i class="fas fa-trash-alt mr-1"></i> Delete
            </button>
        </div>

    </section>
</div>

@endsection

@push('scripts')
<script>
    let resourceDeleteForm = null;
    let resourceDeleteTrigger = null;

    function openResourceDeleteDialog(form) {
        resourceDeleteForm = form;
        resourceDeleteTrigger = document.activeElement;

        const overlay = document.getElementById('resourceDeleteOverlay');
        document.getElementById('resourceDeleteName').textContent = form.dataset.resourceDelete;
        document.getElementById('resourceDeleteType').textContent = form.dataset.resourceType || '';
        overlay.classList.remove('hidden');
        overlay.setAttribute('aria-hidden', 'false');
        overlay.querySelector('.resource-delete-cancel').focus();
    }

    function closeResourceDeleteDialog() {
        const overlay = document.getElementById('resourceDeleteOverlay');
        overlay.classList.add('hidden');
        overlay.setAttribute('aria-hidden', 'true');
        resourceDeleteForm = null;
        resourceDeleteTrigger?.focus();
    }

    function confirmResourceDelete() {
        if (!resourceDeleteForm) return;

        resourceDeleteForm.submit();
    }

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !document.getElementById('resourceDeleteOverlay').classList.contains('hidden')) {
            closeResourceDeleteDialog();
        }
    });
</script>
@endpush
