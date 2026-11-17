
@extends('layouts.admin')

@php
    $status = strtolower($reservation->status ?? 'pending');

    $statusStyles = [
        'pending'   => 'bg-amber-500/10 text-amber-600',
        'confirmed' => 'bg-green-500/10 text-green-600',
        'cancelled' => 'bg-red-500/10 text-red-500',
    ];

    $dateText = $reservation->reservation_date
        ? \Illuminate\Support\Carbon::parse($reservation->reservation_date)->format('d M Y')
        : 'N/A';

    $timeText = $reservation->reservation_time
        ? \Illuminate\Support\Carbon::parse($reservation->reservation_time)->format('h:i A')
        : 'N/A';

    $guestCount = $reservation->guests ?? $reservation->number_of_guests ?? 'N/A';
@endphp

@section('title', 'Reservation Details')
@section('page-heading', 'Reservation Details')

@push('styles')
<style>
    .res-summary-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--bg);
    }

    .res-summary-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 13px;
    }

    /* Gold + White Action Buttons */
    .res-gold-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 17px;
        border: 1px solid #c69b55;
        border-radius: 11px;
        color: #17251d;
        background: linear-gradient(135deg,#f4dfb5 0%,#d6ad69 55%,#b68b4c 100%);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .2px;
        box-shadow: 0 5px 13px rgba(166,126,61,.20);
        transition: all .25s ease;
    }

    .res-gold-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 9px 19px rgba(166,126,61,.30);
        filter: brightness(1.04);
    }

    .res-gold-btn:active {
        transform: translateY(0) scale(.98);
    }

    .res-white-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 17px;
        border: 1px solid var(--border);
        border-radius: 11px;
        color: var(--text);
        background: var(--surface);
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 3px 9px rgba(0,0,0,.04);
        transition: all .25s ease;
    }

    .res-white-btn:hover {
        transform: translateY(-2px);
        border-color: #d6ad69;
        color: #b68b4c;
        box-shadow: 0 7px 15px rgba(166,126,61,.12);
    }

    .res-white-btn:active {
        transform: translateY(0) scale(.98);
    }

    .res-gold-btn:focus-visible,
    .res-white-btn:focus-visible {
        outline: 3px solid rgba(214,173,105,.35);
        outline-offset: 3px;
    }

    @media(max-width:480px) {
        .res-show-actions {
            width: 100%;
        }

        .res-show-actions a {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')

<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    {{-- PAGE INTRO --}}
    <div class="flex flex-wrap items-center justify-between gap-3">

        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">
                Management
            </p>

            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl"
                style="font-family:'Playfair Display',serif;">
                Reservation Details
            </h1>

            <p class="muted mt-1 text-xs">
                Complete customer booking information.
            </p>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="res-show-actions flex flex-wrap gap-2">

            <a href="{{ route('admin.reservations.edit', $reservation->id) }}"
               class="res-gold-btn">
                <i class="fas fa-pen"></i>
                Edit Reservation
            </a>

            <a href="{{ route('admin.reservations.index') }}"
               class="res-white-btn">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="rounded-xl bg-green-500/10 p-3 text-xs text-green-600">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- QUICK SUMMARY --}}
    <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-3">

        <div class="res-summary-card">
            <div class="res-summary-icon"
                 style="background:linear-gradient(145deg,#efd39a,#a17b41);">
                <i class="fas fa-calendar-day"></i>
            </div>

            <div class="min-w-0">
                <p class="muted text-[10px] font-medium uppercase tracking-wide">
                    Date
                </p>
                <p class="truncate text-[13px] font-bold">
                    {{ $dateText }}
                </p>
            </div>
        </div>

        <div class="res-summary-card">
            <div class="res-summary-icon"
                 style="background:linear-gradient(145deg,#7dc4f5,#1685e8);">
                <i class="fas fa-clock"></i>
            </div>

            <div class="min-w-0">
                <p class="muted text-[10px] font-medium uppercase tracking-wide">
                    Time
                </p>
                <p class="truncate text-[13px] font-bold">
                    {{ $timeText }}
                </p>
            </div>
        </div>

        <div class="res-summary-card">
            <div class="res-summary-icon"
                 style="background:linear-gradient(145deg,#5fe0b0,#17966e);">
                <i class="fas fa-users"></i>
            </div>

            <div class="min-w-0">
                <p class="muted text-[10px] font-medium uppercase tracking-wide">
                    Guests
                </p>
                <p class="truncate text-[13px] font-bold">
                    {{ $guestCount }}
                </p>
            </div>
        </div>

    </div>

    {{-- DETAILS CARD --}}
    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        {{-- CARD HEADER --}}
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b pb-4"
             style="border-color:var(--border);">

            <div class="flex items-center gap-2.5">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-sm text-gold"
                     style="background:linear-gradient(145deg,#2c4236,#111c18);">
                    <i class="fas fa-calendar-check"></i>
                </div>

                <div>
                    <h2 class="text-base font-bold leading-tight">
                        Booking Information
                    </h2>

                    <p class="muted text-[11px]">
                        Reservation #{{ $reservation->id }}
                    </p>
                </div>

            </div>

            <span class="rounded-full px-2.5 py-1 text-[10px] font-bold whitespace-nowrap {{ $statusStyles[$status] ?? 'bg-gray-500/10 text-gray-500' }}">
                <i class="fas fa-circle mr-1 text-[5px]"></i>
                {{ ucfirst($status) }}
            </span>

        </div>

        {{-- INFORMATION GRID --}}
        <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Customer Name
                </p>
                <p class="text-[13px] font-semibold">
                    {{ $reservation->name ?? 'N/A' }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Email Address
                </p>
                <p class="break-all text-[13px] font-semibold">
                    {{ $reservation->email ?? 'N/A' }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Phone Number
                </p>
                <p class="text-[13px] font-semibold">
                    {{ $reservation->phone ?? 'N/A' }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Number of Guests
                </p>
                <p class="text-[13px] font-semibold">
                    {{ $guestCount }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Reservation Date
                </p>
                <p class="text-[13px] font-semibold">
                    {{ $dateText }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Reservation Time
                </p>
                <p class="text-[13px] font-semibold">
                    {{ $timeText }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Created At
                </p>
                <p class="text-[13px] font-semibold">
                    {{ $reservation->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Last Updated
                </p>
                <p class="text-[13px] font-semibold">
                    {{ $reservation->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5 sm:col-span-2">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Special Request
                </p>
                <p class="whitespace-pre-line text-[13px] leading-6">
                    {{ $reservation->message ?? $reservation->special_request ?? 'No special request.' }}
                </p>
            </div>

        </div>

    </section>

</div>

@endsection
