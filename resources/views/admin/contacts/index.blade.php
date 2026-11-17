@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('page-heading', 'Contact Messages')

@push('styles')
<style>
    .contact-search {
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

    .contact-search:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(214, 173, 105, .2);
    }

    /* DELETE CARD (compact) */
    .contact-delete-overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: grid;
        place-items: center;
        padding: 16px;
        background: rgba(9, 18, 14, .6);
        backdrop-filter: blur(4px);
    }

    .contact-delete-overlay.hidden { display: none; }

    .contact-delete-dialog {
        width: min(100%, 320px);
        padding: 18px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: var(--surface);
        color: var(--text);
        box-shadow: 0 24px 70px rgba(9, 18, 14, .26);
        animation: contact-dialog-enter .18s ease-out;
    }

    .contact-delete-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        font-size: 14px;
        display: grid;
        place-items: center;
        color: #dc3545;
        background: rgba(239, 68, 68, .11);
    }

    .contact-delete-target {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 12px;
        padding: 9px 11px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--bg);
    }

    .contact-delete-cancel,
    .contact-delete-confirm {
        min-height: 36px;
        padding: 0 13px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .contact-delete-cancel {
        border: 1px solid var(--border);
        color: var(--text);
        background: var(--surface);
    }

    .contact-delete-cancel:hover { background: var(--bg); }

    .contact-delete-confirm {
        color: white;
        background: linear-gradient(135deg, #ef6472, #c92f49);
        box-shadow: 0 6px 14px rgba(201, 47, 73, .2);
    }

    .contact-delete-confirm:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(201, 47, 73, .28);
    }

    /* Compact, colored Show / Edit / Delete buttons */
    .contact-row .admin-action-btn {
        width: 30px; height: 30px; border-radius: 9px; font-size: 12px;
        display: inline-flex; align-items: center; justify-content: center;
        transition: background .18s ease, border-color .18s ease, transform .18s ease, box-shadow .18s ease;
    }
    .contact-row .admin-action-btn.admin-action-view {
        background: #1685e8 !important; color: #fff !important; border: 1px solid #1685e8 !important;
        box-shadow: 0 3px 8px rgba(22, 133, 232, .18);
    }
    .contact-row .admin-action-btn.admin-action-view:hover {
        background: #086fc8 !important; border-color: #086fc8 !important; transform: translateY(-1px);
    }
    .contact-row .admin-action-btn.admin-action-edit {
        background: #f59e0b !important; color: #fff !important; border: 1px solid #f59e0b !important;
        box-shadow: 0 3px 8px rgba(245, 158, 11, .18);
    }
    .contact-row .admin-action-btn.admin-action-edit:hover {
        background: #d97706 !important; border-color: #d97706 !important; transform: translateY(-1px);
    }
    .contact-row .admin-action-btn.admin-action-delete {
        background: #ef4444 !important; color: #fff !important; border: 1px solid #ef4444 !important;
        box-shadow: 0 3px 8px rgba(239, 68, 68, .18);
    }
    .contact-row .admin-action-btn.admin-action-delete:hover {
        background: #dc2626 !important; border-color: #dc2626 !important; transform: translateY(-1px);
    }

    @keyframes contact-dialog-enter {
        from { opacity: 0; transform: translateY(6px) scale(.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @media (min-width: 640px) {
        .contact-search-wrap { width: 240px; }
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
            Contact Messages
        </h1>

        <p class="text-xs muted mt-1">
            Manage messages received from website visitors.
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-2.5 self-start md:self-auto">

        <div class="surface rounded-xl px-3 py-2">
            <span class="text-xs gold">
                Total Messages:
                <strong>{{ $messages->total() }}</strong>
            </span>
        </div>

        <a href="{{ route('admin.contacts.create') }}"
           class="admin-add-btn px-4 py-2.5 rounded-xl text-xs inline-flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i>
            Add New Message
        </a>

    </div>

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

<!-- MESSAGES TABLE -->
<div class="surface min-w-0 overflow-hidden">

    <div class="p-4 border-b flex flex-col md:flex-row gap-3 md:items-center md:justify-between"
         style="border-color:var(--border)">

        <div>
            <h3 class="font-bold text-base">All Messages</h3>
            <p class="text-[11px] muted mt-0.5">
                View, search and manage visitor messages.
            </p>
        </div>

        <div class="contact-search-wrap relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 muted text-[11px]"></i>

            <input type="text"
                   id="searchInput"
                   placeholder="Search messages..."
                   class="contact-search pl-8">
        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="admin-table w-full text-left text-xs min-w-[820px]" id="contactTable">

            <thead>
                <tr>
                    <th class="px-4 py-3 muted font-bold">#</th>
                    <th class="px-4 py-3 muted font-bold">Sender</th>
                    <th class="px-4 py-3 muted font-bold">Subject</th>
                    <th class="px-4 py-3 muted font-bold">Message</th>
                    <th class="px-4 py-3 muted font-bold">Date</th>
                    <th class="px-4 py-3 muted font-bold text-right">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($messages as $message)

                    <tr class="contact-row border-b"
                        style="border-color:var(--border)"
                        data-search="{{ strtolower($message->name.' '.$message->email.' '.($message->subject ?? '').' '.$message->message) }}">

                        <!-- # -->
                        <td class="px-4 py-2.5 muted">
                            {{ $messages->firstItem() + $loop->index }}
                        </td>

                        <!-- SENDER -->
                        <td class="px-4 py-2.5">
                            <p class="font-bold text-[13px] leading-tight">{{ $message->name }}</p>
                            <p class="text-[10px] muted mt-0.5">{{ $message->email }}</p>
                        </td>

                        <!-- SUBJECT -->
                        <td class="px-4 py-2.5 max-w-[180px]">
                            <p class="truncate">{{ $message->subject ?: 'No Subject' }}</p>
                        </td>

                        <!-- MESSAGE -->
                        <td class="px-4 py-2.5 muted max-w-[240px]">
                            <p class="truncate">{{ $message->message }}</p>
                        </td>

                        <!-- DATE -->
                        <td class="px-4 py-2.5 muted whitespace-nowrap">
                            {{ $message->created_at?->format('d M Y') ?? '—' }}
                        </td>

                        <!-- ACTIONS -->
                        <td class="px-4 py-2.5">
                            <div class="flex flex-nowrap items-center justify-end gap-1.5">

                                <a href="{{ route('admin.contacts.show', $message->id) }}"
                                   class="admin-action-btn admin-action-view"
                                   title="View message"
                                   aria-label="View message from {{ $message->name }}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.contacts.edit', $message->id) }}"
                                   class="admin-action-btn admin-action-edit"
                                   title="Edit message"
                                   aria-label="Edit message from {{ $message->name }}">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <form action="{{ route('admin.contacts.destroy', $message->id) }}"
                                      method="POST"
                                      data-contact-delete="{{ $message->name }}"
                                      data-contact-subject="{{ $message->subject ?: 'No Subject' }}">

                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            onclick="openContactDeleteDialog(this.form)"
                                            class="admin-action-btn admin-action-delete"
                                            title="Delete message"
                                            aria-label="Delete message from {{ $message->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-10">

                            <div class="text-3xl muted mb-3">
                                <i class="fas fa-envelope-open-text"></i>
                            </div>

                            <h3 class="font-bold text-base">No Messages Found</h3>

                            <p class="text-xs muted mt-1.5">
                                Contact messages will appear here.
                            </p>

                        </td>
                    </tr>

                @endforelse

                <tr id="noSearchResults" class="hidden">
                    <td colspan="6" class="text-center py-8 text-xs muted">
                        No matching messages found.
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    @if($messages->hasPages())
        <div class="p-4 border-t text-xs" style="border-color:var(--border)">
            {{ $messages->links() }}
        </div>
    @endif

</div>

<!-- DELETE CARD -->
<div id="contactDeleteOverlay"
     class="contact-delete-overlay hidden"
     aria-hidden="true"
     onclick="if (event.target === this) closeContactDeleteDialog()">
    <section class="contact-delete-dialog" role="dialog" aria-modal="true" aria-labelledby="contactDeleteTitle">

        <div class="contact-delete-icon mb-3">
            <i class="fas fa-trash-alt"></i>
        </div>

        <h2 id="contactDeleteTitle" class="text-base font-extrabold">Delete message?</h2>

        <p class="muted text-xs mt-1.5 leading-5">
            This will permanently remove the message. This action cannot be undone.
        </p>

        <div class="contact-delete-target">
            <div class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center text-gold text-xs"
                 style="background:linear-gradient(145deg,#2c4236,#111c18)">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="min-w-0">
                <p id="contactDeleteName" class="font-bold text-[13px] leading-tight truncate"></p>
                <p id="contactDeleteSubject" class="text-[10px] muted mt-0.5 truncate"></p>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <button type="button" class="contact-delete-cancel" onclick="closeContactDeleteDialog()">
                Cancel
            </button>
            <button type="button" class="contact-delete-confirm" onclick="confirmContactDelete()">
                <i class="fas fa-trash-alt mr-1"></i> Delete
            </button>
        </div>

    </section>
</div>

@endsection

@push('scripts')
<script>
    let contactDeleteForm = null;
    let contactDeleteTrigger = null;

    function openContactDeleteDialog(form) {
        contactDeleteForm = form;
        contactDeleteTrigger = document.activeElement;

        const overlay = document.getElementById('contactDeleteOverlay');
        document.getElementById('contactDeleteName').textContent = form.dataset.contactDelete;
        document.getElementById('contactDeleteSubject').textContent = form.dataset.contactSubject || 'No Subject';
        overlay.classList.remove('hidden');
        overlay.setAttribute('aria-hidden', 'false');
        overlay.querySelector('.contact-delete-cancel').focus();
    }

    function closeContactDeleteDialog() {
        const overlay = document.getElementById('contactDeleteOverlay');
        overlay.classList.add('hidden');
        overlay.setAttribute('aria-hidden', 'true');
        contactDeleteForm = null;
        contactDeleteTrigger?.focus();
    }

    function confirmContactDelete() {
        if (!contactDeleteForm) return;

        contactDeleteForm.submit();
    }

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !document.getElementById('contactDeleteOverlay').classList.contains('hidden')) {
            closeContactDeleteDialog();
        }
    });

    // Message search
    const searchInput = document.getElementById('searchInput');

    function filterMessages() {
        const search = searchInput.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.contact-row');

        let visibleCount = 0;

        rows.forEach(row => {
            const show = (row.dataset.search || '').includes(search);

            row.style.display = show ? '' : 'none';

            if (show) visibleCount++;
        });

        const noResults = document.getElementById('noSearchResults');

        if (noResults) {
            noResults.classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
        }
    }

    searchInput?.addEventListener('input', filterMessages);
</script>
@endpush