@extends('layouts.admin')

@section('title', 'Reservations')
@section('page-heading', 'Reservations Management')

@section('content')

@php
    $totalBookings = $totalCount ?? $reservations->total();

    // Shared icon-button style (Tailwind only)
    $btn = 'inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs text-white shadow-sm ring-1 ring-inset ring-white/20 transition duration-200 hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2';
    $btnView    = 'bg-gradient-to-br from-sky-400 to-blue-600 shadow-blue-500/30 hover:shadow-blue-500/40 focus-visible:ring-sky-400';
    $btnEdit    = 'bg-gradient-to-br from-amber-400 to-orange-500 shadow-orange-500/30 hover:shadow-orange-500/40 focus-visible:ring-amber-400';
    $btnConfirm = 'bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-emerald-500/30 hover:shadow-emerald-500/40 focus-visible:ring-emerald-400';
    $btnCancel  = 'bg-gradient-to-br from-slate-400 to-slate-600 shadow-slate-500/30 hover:shadow-slate-500/40 focus-visible:ring-slate-400';
    $btnDelete  = 'bg-gradient-to-br from-rose-400 to-red-600 shadow-red-500/30 hover:shadow-red-500/40 focus-visible:ring-red-400';
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

        <div class="w-9 h-9 sm:w-10 sm:h-10 shrink-0 rounded-xl flex items-center justify-center text-white text-sm bg-gradient-to-br from-[#efd39a] to-[#a17b41] shadow-md shadow-[#a17b41]/30">
            <i class="fas fa-calendar-alt"></i>
        </div>
    </div>

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Pending</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $pendingCount ?? 0 }}</h3>
        </div>

        <div class="w-9 h-9 sm:w-10 sm:h-10 shrink-0 rounded-xl flex items-center justify-center text-white text-sm bg-gradient-to-br from-amber-300 to-[#d9961a] shadow-md shadow-[#d9961a]/30">
            <i class="fas fa-clock"></i>
        </div>
    </div>

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Confirmed</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $confirmedCount ?? 0 }}</h3>
        </div>

        <div class="w-9 h-9 sm:w-10 sm:h-10 shrink-0 rounded-xl flex items-center justify-center text-white text-sm bg-gradient-to-br from-emerald-300 to-[#17966e] shadow-md shadow-[#17966e]/30">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>

    <div class="surface min-w-0 p-3 sm:p-4 flex items-center justify-between gap-2">
        <div class="min-w-0">
            <p class="text-[10px] sm:text-[11px] font-medium uppercase tracking-wide leading-tight muted">Cancelled</p>
            <h3 class="text-lg sm:text-2xl font-extrabold mt-1">{{ $cancelledCount ?? 0 }}</h3>
        </div>

        <div class="w-9 h-9 sm:w-10 sm:h-10 shrink-0 rounded-xl flex items-center justify-center text-white text-sm bg-gradient-to-br from-rose-300 to-[#d83d57] shadow-md shadow-[#d83d57]/30">
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

            <div class="relative sm:w-60">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 muted text-[11px]"></i>

                <input type="text"
                       id="searchInput"
                       placeholder="Search name, email, phone..."
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

                    @php
                        $statusOpts = [
                            ['', 'All Status', '#94a3b8'],
                            ['pending', 'Pending', '#eab308'],
                            ['confirmed', 'Confirmed', '#16a34a'],
                            ['cancelled', 'Cancelled', '#ef4444'],
                        ];
                    @endphp

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
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-gold text-xs shrink-0 bg-gradient-to-br from-[#2c4236] to-[#111c18]">
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
                                                class="{{ $btn }} {{ $btnConfirm }}"
                                                title="Confirm"
                                                aria-label="Confirm {{ $reservation->name }}">
                                            <i class="fas fa-check"></i>
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
                                                class="{{ $btn }} {{ $btnCancel }}"
                                                title="Cancel"
                                                aria-label="Cancel {{ $reservation->name }}">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- VIEW --}}
                                <a href="{{ route('admin.reservations.show', $reservation->id) }}"
                                   class="{{ $btn }} {{ $btnView }}"
                                   title="View">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- EDIT --}}
                                <a href="{{ route('admin.reservations.edit', $reservation->id) }}"
                                   class="{{ $btn }} {{ $btnEdit }}"
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
                                            class="{{ $btn }} {{ $btnDelete }}"
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
     class="fixed inset-0 z-[100] hidden place-items-center bg-[#09120e]/60 p-4 backdrop-blur-sm"
     aria-hidden="true"
     onclick="if (event.target === this) closeReservationDialog()">

    <section class="w-full max-w-[320px] rounded-2xl border border-[var(--border)] bg-[var(--surface)] p-[18px] text-[var(--text)] shadow-2xl shadow-black/30"
             role="dialog" aria-modal="true" aria-labelledby="reservationDialogTitle">

        <div id="reservationDialogIcon"
             class="mb-3 grid h-10 w-10 place-items-center rounded-xl bg-emerald-500/10 text-sm text-emerald-600">
            <i class="fas fa-check-circle"></i>
        </div>

        <h2 id="reservationDialogTitle" class="text-base font-extrabold">Confirm booking?</h2>

        <p class="muted mt-1.5 text-xs leading-5">
            <span id="reservationDialogText"></span>
            <strong id="reservationDialogName" class="font-bold" style="color:var(--text);"></strong>?
        </p>

        <div class="mt-5 flex justify-end gap-2">
            <button type="button"
                    class="reservation-dialog-back min-h-9 rounded-[10px] border border-[var(--border)] bg-[var(--surface)] px-3.5 text-xs font-bold text-[var(--text)] transition hover:bg-[var(--bg)] focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400"
                    onclick="closeReservationDialog()">
                Go Back
            </button>

            <button type="button"
                    id="reservationDialogAction"
                    class="min-h-9 rounded-[10px] bg-gradient-to-br from-emerald-400 to-emerald-600 px-3.5 text-xs font-bold text-white shadow-md shadow-emerald-600/25 transition hover:-translate-y-px"
                    onclick="confirmReservationDialog()">
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

    // Full Tailwind class strings (kept complete so Tailwind can detect them)
    const dialogIconBase = 'mb-3 grid h-10 w-10 place-items-center rounded-xl text-sm ';
    const dialogActionBase = 'min-h-9 rounded-[10px] px-3.5 text-xs font-bold text-white transition hover:-translate-y-px ';

    const reservationDialogConfig = {
        confirm: {
            icon: 'fa-check-circle',
            title: 'Confirm booking?',
            text: 'Are you sure you want to confirm the booking of',
            button: '<i class="fas fa-check mr-1"></i> Yes, Confirm',
            iconClass: 'bg-emerald-500/10 text-emerald-600',
            actionClass: 'bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-md shadow-emerald-600/25'
        },
        cancel: {
            icon: 'fa-calendar-times',
            title: 'Cancel booking?',
            text: 'Are you sure you want to cancel the booking of',
            button: '<i class="fas fa-ban mr-1"></i> Yes, Cancel',
            iconClass: 'bg-slate-500/10 text-slate-600',
            actionClass: 'bg-gradient-to-br from-slate-400 to-slate-600 shadow-md shadow-slate-600/25'
        },
        delete: {
            icon: 'fa-trash-alt',
            title: 'Delete reservation?',
            text: 'This will permanently delete the reservation of',
            button: '<i class="fas fa-trash-alt mr-1"></i> Delete',
            iconClass: 'bg-red-500/10 text-red-500',
            actionClass: 'bg-gradient-to-br from-rose-400 to-red-600 shadow-md shadow-red-600/25'
        }
    };

    function openReservationDialog(form) {
        reservationForm = form;
        reservationTrigger = document.activeElement;

        const config = reservationDialogConfig[form.dataset.reservationType];

        const overlay = document.getElementById('reservationDialogOverlay');
        const icon = document.getElementById('reservationDialogIcon');
        const action = document.getElementById('reservationDialogAction');

        icon.className = dialogIconBase + config.iconClass;
        icon.innerHTML = '<i class="fas ' + config.icon + '"></i>';

        action.className = dialogActionBase + config.actionClass;
        action.innerHTML = config.button;

        document.getElementById('reservationDialogTitle').textContent = config.title;
        document.getElementById('reservationDialogText').textContent = config.text;
        document.getElementById('reservationDialogName').textContent = form.dataset.reservationName;

        overlay.classList.remove('hidden');
        overlay.classList.add('grid');
        overlay.setAttribute('aria-hidden', 'false');
        overlay.querySelector('.reservation-dialog-back').focus();
    }

    function closeReservationDialog() {
        const overlay = document.getElementById('reservationDialogOverlay');
        overlay.classList.add('hidden');
        overlay.classList.remove('grid');
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
    const statusFilter = document.getElementById('statusFilter');

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
        filterReservations();
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

        if (!document.getElementById('reservationDialogOverlay').classList.contains('hidden')) {
            closeReservationDialog();
        }
    });
</script>
@endpush