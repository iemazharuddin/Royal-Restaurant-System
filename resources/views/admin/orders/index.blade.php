@extends('layouts.admin')

@section('title', $title)
@section('page-heading', $title)

@push('styles')
<style>
    .resource-search {
        width: 100%;
        min-height: 40px;
        padding: 0 12px 0 34px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        color: var(--text);
        font-size: 12px;
        outline: none;
        transition: .2s ease;
    }

    .resource-search:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(214,173,105,.2);
    }

    .resource-delete-overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: grid;
        place-items: center;
        padding: 16px;
        background: rgba(9,18,14,.6);
        backdrop-filter: blur(4px);
    }

    .resource-delete-overlay.hidden {
        display: none;
    }

    .resource-delete-dialog {
        width: min(100%, 320px);
        padding: 18px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: var(--surface);
        color: var(--text);
        box-shadow: 0 24px 70px rgba(9,18,14,.26);
    }

    .resource-delete-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        color: #dc3545;
        background: rgba(239,68,68,.11);
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
    }

    .resource-delete-cancel {
        border: 1px solid var(--border);
        color: var(--text);
        background: var(--surface);
    }

    .resource-delete-confirm {
        color: white;
        background: linear-gradient(135deg,#ef6472,#c92f49);
    }

    .resource-row .admin-action-btn {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: .2s ease;
    }

    .resource-row .admin-action-view {
        background: #1685e8 !important;
        color: white !important;
        border: 1px solid #1685e8 !important;
    }

    .resource-row .admin-action-view:hover {
        background: #086fc8 !important;
        transform: translateY(-1px);
    }

    .resource-row .admin-action-edit {
        background: #f59e0b !important;
        color: white !important;
        border: 1px solid #f59e0b !important;
    }

    .resource-row .admin-action-edit:hover {
        background: #d97706 !important;
        transform: translateY(-1px);
    }

    .resource-row .admin-action-delete {
        background: #ef4444 !important;
        color: white !important;
        border: 1px solid #ef4444 !important;
    }

    .resource-row .admin-action-delete:hover {
        background: #dc2626 !important;
        transform: translateY(-1px);
    }

    @media (min-width:640px) {
        .resource-search-wrap {
            width: 260px;
        }
    }
</style>
@endpush

@section('content')

<!-- PAGE HEADER -->
<div class="mb-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

    <div>
        <p class="text-[10px] font-bold uppercase tracking-widest gold">
            Management
        </p>

        <h1 class="mt-1 text-xl font-extrabold sm:text-2xl"
            style="font-family:'Playfair Display',serif">
            {{ $title }}
        </h1>

        <p class="mt-1 text-xs muted">
            {{ $description }}
        </p>
    </div>

    <a href="{{ route('admin.' . $resource . '.create') }}"
       class="admin-add-btn inline-flex items-center justify-center gap-2 self-start rounded-xl px-4 py-2.5 text-xs md:self-auto">

        <i class="fas fa-plus"></i>
        Add New Record

    </a>

</div>

<!-- FLASH MESSAGES -->
@if(session('success'))
    <div class="mb-4 rounded-xl bg-green-500/10 p-3 text-xs text-green-600">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 rounded-xl bg-red-500/10 p-3 text-xs text-red-500">
        {{ $errors->first() }}
    </div>
@endif

<!-- RECORDS TABLE -->
<div class="surface min-w-0 overflow-hidden">

    <form method="GET"
          action="{{ route('admin.' . $resource . '.index') }}"
          class="flex flex-col gap-3 border-b p-4 md:flex-row md:items-center md:justify-between"
          style="border-color:var(--border)">

        <div>
            <h3 class="text-base font-bold">All {{ $title }}</h3>
            <p class="mt-0.5 text-[11px] muted">
                {{ $records->total() }} record(s)
            </p>
        </div>

        <div class="flex gap-2">

            <div class="resource-search-wrap relative flex-1 md:flex-none">

                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[11px] muted"></i>

                <input type="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search {{ strtolower($title) }}..."
                       class="resource-search">

            </div>

            <button type="submit"
                    class="admin-add-btn rounded-xl px-3.5 text-xs"
                    aria-label="Search">

                <i class="fas fa-search"></i>

            </button>

        </div>

    </form>

    <div class="overflow-x-auto">

        <table class="admin-table w-full min-w-[700px] text-left text-xs">

            <thead>
                <tr>
                    <th class="px-4 py-3 font-bold muted">#</th>

                    @foreach($columns as $column)
                        <th class="px-4 py-3 font-bold muted">
                            {{ $column['label'] }}
                        </th>
                    @endforeach

                    <th class="px-4 py-3 text-right font-bold muted">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody>

                @forelse($records as $record)

                    @php
                        $recordLabel = \Illuminate\Support\Str::limit(
                            (string) (
                                data_get($record, $columns[0]['key'] ?? 'id')
                                ?? ('Record #' . $record->id)
                            ),
                            60
                        );
                    @endphp

                    <tr class="resource-row border-b"
                        style="border-color:var(--border)">

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

                                    <span class="rounded-full px-2.5 py-1 text-[10px] font-bold whitespace-nowrap {{ $value ? 'bg-green-500/10 text-green-600' : 'bg-gray-500/10 text-gray-500' }}">
                                        {{ $value ? 'Yes' : 'No' }}
                                    </span>

                                @elseif(is_array($value))

                                    {{ implode(', ', array_map(fn ($item) => is_scalar($item) ? $item : json_encode($item), $value)) }}

                                @elseif($value instanceof \DateTimeInterface)

                                    <span class="whitespace-nowrap muted">
                                        {{ $value->format('d M Y H:i') }}
                                    </span>

                                @elseif(in_array($column['key'], ['amount', 'total', 'subtotal', 'salary'], true) && is_numeric($value))

                                    <span class="whitespace-nowrap font-semibold">
                                        Rs. {{ number_format((float)$value, 2) }}
                                    </span>

                                @else

                                    {{ \Illuminate\Support\Str::limit((string)($value ?? '—'), 90) }}

                                @endif

                            </td>

                        @endforeach

                        <!-- ACTION BUTTONS -->
                        <td class="px-4 py-2.5">

                            <div class="flex items-center justify-end gap-1.5">

                                {{-- VIEW / SHOW --}}
                                @if($resource === 'orders')

                                    <a href="{{ route('admin.orders.show', $record->id) }}"
                                       class="admin-action-btn admin-action-view"
                                       title="View Order"
                                       aria-label="View {{ $recordLabel }}">

                                        <i class="fas fa-eye"></i>

                                    </a>

                                @endif

                                {{-- EDIT --}}
                                <a href="{{ route('admin.' . $resource . '.edit', $record->id) }}"
                                   class="admin-action-btn admin-action-edit"
                                   title="Edit"
                                   aria-label="Edit {{ $recordLabel }}">

                                    <i class="fas fa-pen"></i>

                                </a>

                                {{-- DELETE --}}
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
                        <td colspan="{{ count($columns) + 2 }}"
                            class="py-10 text-center">

                            <div class="mb-3 text-3xl muted">
                                <i class="fas fa-inbox"></i>
                            </div>

                            <h3 class="text-base font-bold">
                                No {{ $title }} Found
                            </h3>

                            <p class="mt-1.5 text-xs muted">
                                Records will appear here once you add them.
                            </p>

                            <a href="{{ route('admin.' . $resource . '.create') }}"
                               class="admin-add-btn mt-4 inline-flex rounded-xl px-4 py-2.5 text-xs">

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

        <div class="border-t p-4 text-xs" style="border-color:var(--border)">
            {{ $records->links() }}
        </div>

    @endif

</div>

<!-- DELETE CONFIRMATION -->
<div id="resourceDeleteOverlay"
     class="resource-delete-overlay hidden"
     aria-hidden="true"
     onclick="if(event.target === this) closeResourceDeleteDialog()">

    <section class="resource-delete-dialog"
             role="dialog"
             aria-modal="true"
             aria-labelledby="resourceDeleteTitle">

        <div class="resource-delete-icon mb-3">
            <i class="fas fa-trash-alt"></i>
        </div>

        <h2 id="resourceDeleteTitle" class="text-base font-extrabold">
            Delete record?
        </h2>

        <p class="mt-1.5 text-xs leading-5 muted">
            This will permanently remove the record. This action cannot be undone.
        </p>

        <div class="resource-delete-target">

            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs text-gold"
                 style="background:linear-gradient(145deg,#2c4236,#111c18)">
                <i class="fas fa-inbox"></i>
            </div>

            <div class="min-w-0">
                <p id="resourceDeleteName"
                   class="truncate text-[13px] font-bold"></p>

                <p id="resourceDeleteType"
                   class="mt-0.5 truncate text-[10px] muted"></p>
            </div>

        </div>

        <div class="mt-4 flex justify-end gap-2">

            <button type="button"
                    class="resource-delete-cancel"
                    onclick="closeResourceDeleteDialog()">
                Cancel
            </button>

            <button type="button"
                    class="resource-delete-confirm"
                    onclick="confirmResourceDelete()">

                <i class="fas fa-trash-alt mr-1"></i>
                Delete

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

        document.getElementById('resourceDeleteName').textContent =
            form.dataset.resourceDelete;

        document.getElementById('resourceDeleteType').textContent =
            form.dataset.resourceType || '';

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

    document.addEventListener('keydown', function(event) {

        const overlay = document.getElementById('resourceDeleteOverlay');

        if (
            event.key === 'Escape' &&
            !overlay.classList.contains('hidden')
        ) {
            closeResourceDeleteDialog();
        }

    });
</script>
@endpush
