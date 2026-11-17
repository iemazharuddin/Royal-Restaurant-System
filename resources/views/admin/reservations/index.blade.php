@extends('layouts.admin')

@section('title', 'Reservations')
@section('page-heading', 'Reservations Management')

@push('styles')
<style>
    .reservation-search {
        min-height: 40px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        color: var(--text);
        font-size: 12px;
        box-shadow: 0 3px 10px rgba(17, 28, 24, .04);
        transition: border-color .2s ease, box-shadow .2s ease;
        width: 100%;
        padding-right: 12px;
    }

    .reservation-search:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(214, 173, 105, .2);
    }

    /* ---------- STATUS DROPDOWN (custom, themed) ---------- */
    .status-dd {
        position: relative;
        width: 100%;
    }

    .status-dd-btn {
        width: 100%;
        min-height: 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 0 12px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        color: var(--text);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(17, 28, 24, .04);
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .status-dd-btn:hover,
    .status-dd-btn:focus-visible,
    .status-dd.open .status-dd-btn {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(214, 173, 105, .2);
    }

    .status-dd-btn .chev {
        font-size: 10px;
        color: var(--muted);
        transition: transform .2s ease, color .2s ease;
    }

    .status-dd.open .status-dd-btn .chev {
        transform: rotate(180deg);
        color: var(--gold);
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        flex-shrink: 0;
    }

    .status-dd-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        z-index: 60;
        min-width: 100%;
        padding: 6px;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: var(--surface);
        box-shadow: 0 18px 40px rgba(9, 18, 14, .16);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transform: translateY(-6px) scale(.98);
        transform-origin: top;
        transition: opacity .16s ease, transform .16s ease, visibility 0s linear .16s;
    }

    .status-dd.open .status-dd-menu {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transform: translateY(0) scale(1);
        transition: opacity .16s ease, transform .16s ease, visibility 0s;
    }

    .status-dd-option {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 9px 10px;
        border-radius: 9px;
        color: var(--text);
        font-size: 12px;
        font-weight: 600;
        text-align: left;
        cursor: pointer;
        transition: background .15s ease, color .15s ease;
    }

    .status-dd-option + .status-dd-option { margin-top: 2px; }

    .status-dd-option:hover,
    .status-dd-option:focus-visible {
        outline: none;
        background: rgba(214, 173, 105, .14);
    }

    .status-dd-option .check {
        margin-left: auto;
        font-size: 10px;
        color: var(--gold);
        opacity: 0;
    }

    .status-dd-option.selected {
        color: #a17b41;
        background: linear-gradient(100deg, rgba(214, 173, 105, .24), rgba(214, 173, 105, .07));
    }

    [data-theme="dark"] .status-dd-option.selected { color: var(--gold); }

    .status-dd-option.selected .check { opacity: 1; }

    /* SMALL SHARED MODAL (confirm / cancel / delete) */
    .reservation-dialog-overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: grid;
        place-items: center;
        padding: 16px;
        background: rgba(9, 18, 14, .6);
        backdrop-filter: blur(4px);
    }

    .reservation-dialog-overlay.hidden { display: none; }

    .reservation-dialog {
        width: min(100%, 320px);
        padding: 18px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: var(--surface);
        color: var(--text);
        box-shadow: 0 24px 70px rgba(9, 18, 14, .26);
        animation: reservation-dialog-enter .18s ease-out;
    }

    .reservation-dialog-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        font-size: 14px;
        display: grid;
        place-items: center;
    }

    .reservation-dialog-icon.is-confirm { color: #16a34a; background: rgba(34, 197, 94, .12); }
    .reservation-dialog-icon.is-cancel,
    .reservation-dialog-icon.is-delete  { color: #dc3545; background: rgba(239, 68, 68, .11); }

    .reservation-dialog-back,
    .reservation-dialog-action {
        min-height: 36px;
        padding: 0 13px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .reservation-dialog-back {
        border: 1px solid var(--border);
        color: var(--text);
        background: var(--surface);
    }

    .reservation-dialog-back:hover { background: var(--bg); }

    .reservation-dialog-action {
        color: white;
    }

    .reservation-dialog-action:hover { transform: translateY(-1px); }

    .reservation-dialog-action.is-confirm {
        background: linear-gradient(135deg, #34d399, #16a34a);
        box-shadow: 0 6px 14px rgba(22, 163, 74, .22);
    }

    .reservation-dialog-action.is-cancel,
    .reservation-dialog-action.is-delete {
        background: linear-gradient(135deg, #ef6472, #c92f49);
        box-shadow: 0 6px 14px rgba(201, 47, 73, .2);
    }

    /* Compact row action buttons (view / edit / delete) */
    .reservation-row .admin-action-btn {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        font-size: 12px;
    }

    @keyframes reservation-dialog-enter {
        from { opacity: 0; transform: translateY(6px) scale(.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @media (min-width: 640px) {
        .reservation-search-wrap { width: 230px; }
        .status-dd { width: 160px; }
    }

    /* Colored backgrounds for View, Edit and Delete action buttons */
    .reservation-row .admin-action-btn.admin-action-view {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #1685e8 !important;
        color: #ffffff !important;
        border: 1px solid #1685e8 !important;
        box-shadow: 0 3px 8px rgba(22, 133, 232, .18);
        transition: background .18s ease, transform .18s ease, box-shadow .18s ease;
    }
    .reservation-row .admin-action-btn.admin-action-view:hover {
        background: #086fc8 !important;
        border-color: #086fc8 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(22, 133, 232, .25);
    }

    .reservation-row .admin-action-btn.admin-action-edit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f59e0b !important;
        color: #ffffff !important;
        border: 1px solid #f59e0b !important;
        box-shadow: 0 3px 8px rgba(245, 158, 11, .18);
        transition: background .18s ease, transform .18s ease, box-shadow .18s ease;
    }
    .reservation-row .admin-action-btn.admin-action-edit:hover {
        background: #d97706 !important;
        border-color: #d97706 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(217, 119, 6, .25);
    }

    .reservation-row .admin-action-btn.admin-action-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #ef4444 !important;
        color: #ffffff !important;
        border: 1px solid #ef4444 !important;
        box-shadow: 0 3px 8px rgba(239, 68, 68, .18);
        transition: background .18s ease, transform .18s ease, box-shadow .18s ease;
    }
    .reservation-row .admin-action-btn.admin-action-delete:hover {
        background: #dc2626 !important;
        border-color: #dc2626 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(220, 38, 38, .25);
    }

</style>
@endpush

@section('content')

@php
    $totalBookings = $totalCount ?? $reservations->total();
@endphp

<!-- PAGE INTRO -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">

    <div>
        <p class="text-[10px] gold font-bold uppercase tracking-widest">Management</p>

        <h1 class="text-xl sm:text-2xl font-extrabold mt-1"
            style="font-family:'Playfair Display',serif;">
            Reservations
        </h1>

        <p class="text-xs muted mt-1">
            Manage customer bookings, review requests and keep every reservation organized.
        </p>
    </div>

    <a href="{{ route('reservation.create') }}"
       class="admin-add-btn px-4 py-2.5 rounded-xl text-xs inline-flex items-center justify-center gap-2 self-start md:self-auto">
        <i class="fas fa-plus"></i>
        Add Reservation
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
<div class="grid grid-cols-2 xl:grid-cols-4 gap-2.5 sm:gap-3 mb-5">

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Total Bookings</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $totalBookings }}</h3>
        </div>

        <div class="w-9 h-9 sm:w-10 sm:h-10 shrink-0 rounded-xl flex items-center justify-center text-white text-sm"
             style="background:linear-gradient(145deg,#efd39a,#a17b41);box-shadow:0 5px 12px #a17b4133;">
            <i class="fas fa-calendar-alt"></i>
        </div>
    </div>

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Pending</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $pendingCount ?? 0 }}</h3>
        </div>

        <div class="w-9 h-9 sm:w-10 sm:h-10 shrink-0 rounded-xl flex items-center justify-center text-white text-sm"
             style="background:linear-gradient(145deg,#fcd34d,#d9961a);box-shadow:0 5px 12px #d9961a33;">
            <i class="fas fa-clock"></i>
        </div>
    </div>

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Confirmed</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $confirmedCount ?? 0 }}</h3>
        </div>

        <div class="w-9 h-9 sm:w-10 sm:h-10 shrink-0 rounded-xl flex items-center justify-center text-white text-sm"
             style="background:linear-gradient(145deg,#5fe0b0,#17966e);box-shadow:0 5px 12px #17966e33;">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Cancelled</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $cancelledCount ?? 0 }}</h3>
        </div>

        <div class="w-9 h-9 sm:w-10 sm:h-10 shrink-0 rounded-xl flex items-center justify-center text-white text-sm"
             style="background:linear-gradient(145deg,#ff9a9e,#d83d57);box-shadow:0 5px 12px #d83d5733;">
            <i class="fas fa-calendar-times"></i>
        </div>
    </div>

</div>

<!-- RESERVATION TABLE -->
<div class="surface min-w-0">

    <div class="p-4 border-b flex flex-col md:flex-row gap-3 md:items-center md:justify-between"
         style="border-color:var(--border)">

        <div>
            <h3 class="font-bold text-base">All Reservations</h3>
            <p class="text-[11px] muted mt-0.5">
                View, search and manage customer booking records.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2.5">

            <div class="reservation-search-wrap relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 muted text-[11px]"></i>

                <input type="text"
                       id="searchInput"
                       placeholder="Search name, email, phone..."
                       class="reservation-search pl-8">
            </div>

            {{-- Custom status dropdown --}}
            <div class="status-dd" id="statusDropdown">

                <input type="hidden" id="statusFilter" value="">

                <button type="button"
                        id="statusDropdownBtn"
                        class="status-dd-btn"
                        aria-haspopup="listbox"
                        aria-expanded="false">

                    <span class="flex items-center gap-2 min-w-0">
                        <span class="status-dot" id="statusDropdownDot" style="background:#94a3b8"></span>
                        <span id="statusDropdownLabel" class="truncate">All Status</span>
                    </span>

                    <i class="fas fa-chevron-down chev"></i>
                </button>

                <div class="status-dd-menu" role="listbox" aria-label="Filter by status">

                    <button type="button" class="status-dd-option selected" role="option" aria-selected="true"
                            data-value="" data-color="#94a3b8">
                        <span class="status-dot" style="background:#94a3b8"></span>
                        <span>All Status</span>
                        <i class="fas fa-check check"></i>
                    </button>

                    <button type="button" class="status-dd-option" role="option" aria-selected="false"
                            data-value="pending" data-color="#eab308">
                        <span class="status-dot" style="background:#eab308"></span>
                        <span>Pending</span>
                        <i class="fas fa-check check"></i>
                    </button>

                    <button type="button" class="status-dd-option" role="option" aria-selected="false"
                            data-value="confirmed" data-color="#16a34a">
                        <span class="status-dot" style="background:#16a34a"></span>
                        <span>Confirmed</span>
                        <i class="fas fa-check check"></i>
                    </button>

                    <button type="button" class="status-dd-option" role="option" aria-selected="false"
                            data-value="cancelled" data-color="#ef4444">
                        <span class="status-dot" style="background:#ef4444"></span>
                        <span>Cancelled</span>
                        <i class="fas fa-check check"></i>
                    </button>

                </div>

            </div>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="admin-table w-full text-left text-xs min-w-[860px]" id="reservationTable">

            <thead>
                <tr>
                    <th class="px-4 py-3 muted font-bold">#</th>
                    <th class="px-4 py-3 muted font-bold">Customer</th>
                    <th class="px-4 py-3 muted font-bold">Phone</th>
                    <th class="px-4 py-3 muted font-bold">Date</th>
                    <th class="px-4 py-3 muted font-bold">Time</th>
                    <th class="px-4 py-3 muted font-bold">Guests</th>
                    <th class="px-4 py-3 muted font-bold">Status</th>
                    <th class="px-4 py-3 muted font-bold text-right">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($reservations as $reservation)

                    @php
                        $status = strtolower($reservation->status ?? 'pending');
                        $guestCount = $reservation->guests ?? $reservation->number_of_guests ?? '';
                        $messageValue = $reservation->message ?? $reservation->special_request ?? '';
                    @endphp

                    <tr class="reservation-row border-b"
                        style="border-color:var(--border)"
                        data-search="{{ strtolower($reservation->name.' '.$reservation->email.' '.$reservation->phone) }}"
                        data-status="{{ $status }}">

                        <!-- # -->
                        <td class="px-4 py-2.5 muted">
                            {{ $reservations->firstItem() + $loop->index }}
                        </td>

                        <!-- CUSTOMER -->
                        <td class="px-4 py-2.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-gold text-xs shrink-0"
                                     style="background:linear-gradient(145deg,#2c4236,#111c18)">
                                    <i class="fas fa-user"></i>
                                </div>

                                <div class="min-w-0">
                                    <p class="font-bold text-[13px] leading-tight">{{ $reservation->name }}</p>
                                    <p class="text-[10px] muted mt-0.5">{{ $reservation->email }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- PHONE -->
                        <td class="px-4 py-2.5 muted">
                            {{ $reservation->phone }}
                        </td>

                        <!-- DATE -->
                        <td class="px-4 py-2.5 muted whitespace-nowrap">
                            {{ $reservation->reservation_date
                                ? \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y')
                                : 'N/A' }}
                        </td>

                        <!-- TIME -->
                        <td class="px-4 py-2.5 muted whitespace-nowrap">
                            {{ $reservation->reservation_time ?? 'N/A' }}
                        </td>

                        <!-- GUESTS -->
                        <td class="px-4 py-2.5 muted">
                            {{ $guestCount ?: 'N/A' }}
                        </td>

                        <!-- STATUS -->
                        <td class="px-4 py-2.5">
                            @if($status === 'confirmed')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-500/10 text-green-600 whitespace-nowrap">
                                    <i class="fas fa-circle text-[5px] mr-1"></i> Confirmed
                                </span>
                            @elseif($status === 'cancelled')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-500/10 text-red-500 whitespace-nowrap">
                                    <i class="fas fa-circle text-[5px] mr-1"></i> Cancelled
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-yellow-500/10 text-yellow-600 whitespace-nowrap">
                                    <i class="fas fa-circle text-[5px] mr-1"></i> {{ ucfirst($status) }}
                                </span>
                            @endif
                        </td>

                        <!-- ACTIONS -->
                        <td class="px-4 py-2.5">
                            <div class="flex items-center justify-end gap-1.5">

                                {{-- CONFIRM --}}
                                @if($status !== 'confirmed')
                                    <form action="{{ route('admin.reservations.update', $reservation->id) }}"
                                          method="POST"
                                          data-reservation-type="confirm"
                                          data-reservation-name="{{ $reservation->name }}">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="name" value="{{ $reservation->name }}">
                                        <input type="hidden" name="email" value="{{ $reservation->email }}">
                                        <input type="hidden" name="phone" value="{{ $reservation->phone }}">
                                        <input type="hidden" name="reservation_date" value="{{ $reservation->reservation_date }}">
                                        <input type="hidden" name="reservation_time" value="{{ $reservation->reservation_time }}">
                                        <input type="hidden" name="guests" value="{{ $guestCount }}">
                                        <input type="hidden" name="message" value="{{ $messageValue }}">
                                        <input type="hidden" name="status" value="confirmed">

                                        <button type="button"
                                                onclick="openReservationDialog(this.form)"
                                                class="rounded-lg bg-green-500 px-2.5 py-1.5 text-[11px] font-bold text-white transition hover:bg-green-600"
                                                title="Confirm"
                                                aria-label="Confirm {{ $reservation->name }}">
                                            <i class="fas fa-check mr-1"></i> Confirm
                                        </button>
                                    </form>
                                @endif

                                {{-- CANCEL --}}
                                @if($status !== 'cancelled')
                                    <form action="{{ route('admin.reservations.update', $reservation->id) }}"
                                          method="POST"
                                          data-reservation-type="cancel"
                                          data-reservation-name="{{ $reservation->name }}">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="name" value="{{ $reservation->name }}">
                                        <input type="hidden" name="email" value="{{ $reservation->email }}">
                                        <input type="hidden" name="phone" value="{{ $reservation->phone }}">
                                        <input type="hidden" name="reservation_date" value="{{ $reservation->reservation_date }}">
                                        <input type="hidden" name="reservation_time" value="{{ $reservation->reservation_time }}">
                                        <input type="hidden" name="guests" value="{{ $guestCount }}">
                                        <input type="hidden" name="message" value="{{ $messageValue }}">
                                        <input type="hidden" name="status" value="cancelled">

                                        <button type="button"
                                                onclick="openReservationDialog(this.form)"
                                                class="rounded-lg bg-red-500 px-2.5 py-1.5 text-[11px] font-bold text-white transition hover:bg-red-600"
                                                title="Cancel"
                                                aria-label="Cancel {{ $reservation->name }}">
                                            <i class="fas fa-times mr-1"></i> Cancel
                                        </button>
                                    </form>
                                @endif

                                {{-- VIEW --}}
                                <a href="{{ route('admin.reservations.show', $reservation->id) }}"
                                   class="admin-action-btn admin-action-view"
                                   title="View">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- EDIT --}}
                                <a href="{{ route('admin.reservations.edit', $reservation->id) }}"
                                   class="admin-action-btn admin-action-edit"
                                   title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>

                                {{-- DELETE --}}
                                <form action="{{ route('admin.reservations.destroy', $reservation->id) }}"
                                      method="POST"
                                      data-reservation-type="delete"
                                      data-reservation-name="{{ $reservation->name }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            onclick="openReservationDialog(this.form)"
                                            class="admin-action-btn admin-action-delete"
                                            title="Delete"
                                            aria-label="Delete {{ $reservation->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="text-center py-10">

                            <div class="text-3xl muted mb-3">
                                <i class="fas fa-calendar-times"></i>
                            </div>

                            <h3 class="font-bold text-base">No Reservations Found</h3>

                            <p class="text-xs muted mt-1.5">
                                Customer bookings will appear here.
                            </p>

                        </td>
                    </tr>

                @endforelse

                <tr id="noSearchResults" class="hidden">
                    <td colspan="8" class="text-center py-8 text-xs muted">
                        No matching reservations found.
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    @if($reservations->hasPages())
        <div class="p-4 border-t text-xs" style="border-color:var(--border)">
            {{ $reservations->links() }}
        </div>
    @endif

</div>

<!-- SMALL SHARED DIALOG: CONFIRM / CANCEL / DELETE -->
<div id="reservationDialogOverlay"
     class="reservation-dialog-overlay hidden"
     aria-hidden="true"
     onclick="if (event.target === this) closeReservationDialog()">
    <section class="reservation-dialog" role="dialog" aria-modal="true" aria-labelledby="reservationDialogTitle">

        <div id="reservationDialogIcon" class="reservation-dialog-icon is-confirm mb-3">
            <i class="fas fa-check-circle"></i>
        </div>

        <h2 id="reservationDialogTitle" class="text-base font-extrabold">Confirm booking?</h2>

        <p class="muted text-xs mt-1.5 leading-5">
            <span id="reservationDialogText"></span>
            <strong id="reservationDialogName" class="font-bold" style="color:var(--text);"></strong>?
        </p>

        <div class="flex justify-end gap-2 mt-5">
            <button type="button" class="reservation-dialog-back" onclick="closeReservationDialog()">
                Go Back
            </button>
            <button type="button" id="reservationDialogAction" class="reservation-dialog-action is-confirm" onclick="confirmReservationDialog()">
                <i class="fas fa-check mr-1"></i> Yes, Confirm
            </button>
        </div>

    </section>
</div>

@endsection

@push('scripts')
<script>
    let reservationForm = null;
    let reservationTrigger = null;

    const reservationDialogConfig = {
        confirm: {
            icon: 'fa-check-circle',
            title: 'Confirm booking?',
            text: 'Are you sure you want to confirm the booking of',
            button: '<i class="fas fa-check mr-1"></i> Yes, Confirm'
        },
        cancel: {
            icon: 'fa-calendar-times',
            title: 'Cancel booking?',
            text: 'Are you sure you want to cancel the booking of',
            button: '<i class="fas fa-times mr-1"></i> Yes, Cancel'
        },
        delete: {
            icon: 'fa-trash-alt',
            title: 'Delete reservation?',
            text: 'This will permanently delete the reservation of',
            button: '<i class="fas fa-trash-alt mr-1"></i> Delete'
        }
    };

    function openReservationDialog(form) {
        reservationForm = form;
        reservationTrigger = document.activeElement;

        const type = form.dataset.reservationType;
        const config = reservationDialogConfig[type];

        const overlay = document.getElementById('reservationDialogOverlay');
        const icon = document.getElementById('reservationDialogIcon');
        const action = document.getElementById('reservationDialogAction');

        icon.className = 'reservation-dialog-icon mb-3 is-' + type;
        icon.innerHTML = '<i class="fas ' + config.icon + '"></i>';

        action.className = 'reservation-dialog-action is-' + type;
        action.innerHTML = config.button;

        document.getElementById('reservationDialogTitle').textContent = config.title;
        document.getElementById('reservationDialogText').textContent = config.text;
        document.getElementById('reservationDialogName').textContent = form.dataset.reservationName;

        overlay.classList.remove('hidden');
        overlay.setAttribute('aria-hidden', 'false');
        overlay.querySelector('.reservation-dialog-back').focus();
    }

    function closeReservationDialog() {
        const overlay = document.getElementById('reservationDialogOverlay');
        overlay.classList.add('hidden');
        overlay.setAttribute('aria-hidden', 'true');
        reservationForm = null;
        reservationTrigger?.focus();
    }

    function confirmReservationDialog() {
        if (!reservationForm) return;

        const form = reservationForm;
        reservationForm = null;
        form.submit();
    }

    // Search and status filter
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter'); // hidden input, set by the dropdown

    function filterReservations() {
        const search = searchInput.value.toLowerCase().trim();
        const status = statusFilter.value;
        const rows = document.querySelectorAll('.reservation-row');

        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.dataset.search || '';
            const rowStatus = row.dataset.status || '';

            const show = text.includes(search) && (!status || rowStatus === status);

            row.style.display = show ? '' : 'none';

            if (show) visibleCount++;
        });

        const noResults = document.getElementById('noSearchResults');

        if (noResults) {
            noResults.classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
        }
    }

    searchInput?.addEventListener('input', filterReservations);

    // Custom status dropdown
    const statusDropdown = document.getElementById('statusDropdown');
    const statusButton = document.getElementById('statusDropdownBtn');
    const statusLabel = document.getElementById('statusDropdownLabel');
    const statusDot = document.getElementById('statusDropdownDot');
    const statusOptions = [...statusDropdown.querySelectorAll('.status-dd-option')];

    function setStatusDropdown(open) {
        statusDropdown.classList.toggle('open', open);
        statusButton.setAttribute('aria-expanded', String(open));
    }

    function chooseStatus(option) {
        statusFilter.value = option.dataset.value;
        statusLabel.textContent = option.querySelector('span:not(.status-dot)').textContent.trim();
        statusDot.style.background = option.dataset.color;

        statusOptions.forEach(o => {
            const active = o === option;
            o.classList.toggle('selected', active);
            o.setAttribute('aria-selected', String(active));
        });

        setStatusDropdown(false);
        filterReservations();
    }

    statusButton.addEventListener('click', () => {
        setStatusDropdown(!statusDropdown.classList.contains('open'));
    });

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
        const selected = statusOptions.findIndex(o => o.classList.contains('selected'));
        const step = event.key === 'ArrowDown' ? 1 : -1;
        const next = current === -1
            ? Math.max(selected, 0)
            : (current + step + statusOptions.length) % statusOptions.length;

        statusOptions[next].focus();
    });

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') return;

        if (statusDropdown.classList.contains('open')) {
            setStatusDropdown(false);
            statusButton.focus();
        }

        if (!document.getElementById('reservationDialogOverlay').classList.contains('hidden')) {
            closeReservationDialog();
        }
    });
</script>
@endpush