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
    /* Compact, colored action buttons */
    .res-show-actions .admin-detail-action { transition: transform .18s ease, box-shadow .18s ease; }
    .res-show-actions .admin-detail-action:hover { transform: translateY(-1px); }

    .res-summary-card {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 12px; border: 1px solid var(--border);
        border-radius: 12px; background: var(--bg);
    }
    .res-summary-icon {
        width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 13px;
    }
</style>
@endpush

@section('content')
<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    <!-- PAGE INTRO -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">Management</p>
            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl" style="font-family:'Playfair Display',serif;">Reservation Details</h1>
            <p class="muted mt-1 text-xs">Complete customer booking information.</p>
        </div>

        <div class="res-show-actions flex flex-wrap gap-2">
            <a href="{{ route('admin.reservations.edit', $reservation->id) }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs"
               style="color:#111c18;background:linear-gradient(135deg,#efd39a,#d6ad69 58%,#a17b41);">
                <i class="fas fa-pen"></i> Edit Reservation
            </a>
            <a href="{{ route('admin.reservations.index') }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs border"
               style="border-color:var(--border);">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-green-500/10 p-3 text-xs text-green-600">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- QUICK SUMMARY -->
    <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-3">
        <div class="res-summary-card">
            <div class="res-summary-icon" style="background:linear-gradient(145deg,#efd39a,#a17b41);">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="min-w-0">
                <p class="muted text-[10px] font-medium uppercase tracking-wide">Date</p>
                <p class="truncate text-[13px] font-bold">{{ $dateText }}</p>
            </div>
        </div>
        <div class="res-summary-card">
            <div class="res-summary-icon" style="background:linear-gradient(145deg,#7dc4f5,#1685e8);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="min-w-0">
                <p class="muted text-[10px] font-medium uppercase tracking-wide">Time</p>
                <p class="truncate text-[13px] font-bold">{{ $timeText }}</p>
            </div>
        </div>
        <div class="res-summary-card">
            <div class="res-summary-icon" style="background:linear-gradient(145deg,#5fe0b0,#17966e);">
                <i class="fas fa-users"></i>
            </div>
            <div class="min-w-0">
                <p class="muted text-[10px] font-medium uppercase tracking-wide">Guests</p>
                <p class="truncate text-[13px] font-bold">{{ $guestCount }}</p>
            </div>
        </div>
    </div>

    <!-- DETAILS CARD -->
    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b pb-4" style="border-color:var(--border);">
            <div class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-sm text-gold"
                     style="background:linear-gradient(145deg,#2c4236,#111c18);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold leading-tight">Booking Information</h2>
                    <p class="muted text-[11px]">Reservation #{{ $reservation->id }}</p>
                </div>
            </div>

            <span class="rounded-full px-2.5 py-1 text-[10px] font-bold whitespace-nowrap {{ $statusStyles[$status] ?? 'bg-gray-500/10 text-gray-500' }}">
                <i class="fas fa-circle mr-1 text-[5px]"></i> {{ ucfirst($status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Customer Name</p>
                <p class="text-[13px] font-semibold">{{ $reservation->name ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Email Address</p>
                <p class="break-all text-[13px] font-semibold">{{ $reservation->email ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Phone Number</p>
                <p class="text-[13px] font-semibold">{{ $reservation->phone ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Number of Guests</p>
                <p class="text-[13px] font-semibold">{{ $guestCount }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Reservation Date</p>
                <p class="text-[13px] font-semibold">{{ $dateText }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Reservation Time</p>
                <p class="text-[13px] font-semibold">{{ $timeText }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Created At</p>
                <p class="text-[13px] font-semibold">{{ $reservation->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Last Updated</p>
                <p class="text-[13px] font-semibold">{{ $reservation->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5 sm:col-span-2">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Special Request</p>
                <p class="whitespace-pre-line text-[13px] leading-6">{{ $reservation->message ?? $reservation->special_request ?? 'No special request.' }}</p>
            </div>
        </div>

    </section>
</div>
@endsection