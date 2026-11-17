@extends('layouts.admin')

@php
    // Shared compact styling (same as the Payments form)
    $labelClass   = 'muted mb-1 block text-[10px] font-medium uppercase tracking-wide';
    $controlClass = 'w-full rounded-xl border px-3 py-2.5 text-[13px] outline-none focus:border-yellow-500';
    $controlStyle = 'background:var(--bg);border-color:var(--border);color:var(--text);';

    $guestsValue = $reservation->guests ?? $reservation->number_of_guests ?? '';
    $messageValue = $reservation->message ?? $reservation->special_request ?? '';

    $dateValue = $reservation->reservation_date
        ? \Illuminate\Support\Carbon::parse($reservation->reservation_date)->format('Y-m-d')
        : '';
    $timeValue = $reservation->reservation_time
        ? \Illuminate\Support\Carbon::parse($reservation->reservation_time)->format('H:i')
        : '';
@endphp

@section('title', 'Edit Reservation')
@section('page-heading', 'Edit Reservation')

@push('styles')
<style>
    /* Fallback (Firefox / Safari / older browsers) */
    select.admin-select option {
        font-size: 13px;
        padding: 8px 10px;
        background: var(--surface);
        color: var(--text);
    }

    /* Fully styled dropdown (Chrome / Edge) */
    @supports (appearance: base-select) {
        select.admin-select:not([multiple]),
        select.admin-select:not([multiple])::picker(select) {
            appearance: base-select;
        }

        select.admin-select:not([multiple])::picker(select) {
            margin-top: 4px;
            padding: 4px;
            max-height: 240px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--surface);
            box-shadow: 0 14px 34px rgba(9, 18, 14, .18);
        }

        select.admin-select:not([multiple])::picker-icon {
            color: var(--muted);
            transition: rotate .2s ease;
        }
        select.admin-select:not([multiple]):open::picker-icon { rotate: 180deg; }

        select.admin-select:not([multiple]) option {
            padding: 8px 10px;
            border-radius: 8px;
            font-size: 12.5px;
            background: transparent;
            color: var(--text);
            cursor: pointer;
        }
        select.admin-select:not([multiple]) option:hover,
        select.admin-select:not([multiple]) option:focus {
            background: rgba(214, 173, 105, .18);
        }
        select.admin-select:not([multiple]) option:checked {
            background: rgba(214, 173, 105, .28);
            font-weight: 700;
        }
        select.admin-select:not([multiple]) option::checkmark { display: none; }
    }
</style>
@endpush

@section('content')

<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">Management</p>
            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl" style="font-family:'Playfair Display',serif;">Edit Reservation</h1>
            <p class="muted mt-1 text-xs">Update the existing record information.</p>
        </div>

        <a href="{{ route('admin.reservations.index') }}"
           class="admin-detail-action !px-3.5 !py-2 !text-xs border"
           style="border-color:var(--border);">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-green-500/10 p-3 text-xs text-green-600">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-300 bg-red-50 p-3 text-xs text-red-700">
            <strong><i class="fas fa-exclamation-circle mr-1"></i> Please fix the following errors:</strong>
            <ul class="mt-1.5 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        <div class="mb-4 flex items-center gap-2.5 border-b pb-4" style="border-color:var(--border);">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-sm text-gold"
                 style="background:linear-gradient(145deg,#2c4236,#111c18);">
                <i class="fas fa-pen-to-square"></i>
            </div>
            <div>
                <h2 class="text-base font-bold leading-tight">Update Information</h2>
                <p class="muted text-[11px]">Reservation for {{ $reservation->name }}</p>
            </div>
        </div>

        <form action="{{ route('admin.reservations.update', $reservation->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                {{-- Customer Name --}}
                <div>
                    <label for="name" class="{{ $labelClass }}">Customer Name <span class="text-red-500">*</span></label>
                    <input id="name" type="text" name="name" required
                           value="{{ old('name', $reservation->name) }}"
                           placeholder="Enter customer name"
                           class="{{ $controlClass }}" style="{{ $controlStyle }}">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="{{ $labelClass }}">Email Address <span class="text-red-500">*</span></label>
                    <input id="email" type="email" name="email" required
                           value="{{ old('email', $reservation->email) }}"
                           placeholder="Enter email address"
                           class="{{ $controlClass }}" style="{{ $controlStyle }}">
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="{{ $labelClass }}">Phone Number <span class="text-red-500">*</span></label>
                    <input id="phone" type="text" name="phone" required
                           value="{{ old('phone', $reservation->phone) }}"
                           placeholder="Enter phone number"
                           class="{{ $controlClass }}" style="{{ $controlStyle }}">
                    @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Guests --}}
                <div>
                    <label for="guests" class="{{ $labelClass }}">Number of Guests <span class="text-red-500">*</span></label>
                    <input id="guests" type="number" name="guests" min="1" max="50" required
                           value="{{ old('guests', $guestsValue) }}"
                           placeholder="Enter number of guests"
                           class="{{ $controlClass }}" style="{{ $controlStyle }}">
                    @error('guests') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Date --}}
                <div>
                    <label for="reservation_date" class="{{ $labelClass }}">Reservation Date <span class="text-red-500">*</span></label>
                    <input id="reservation_date" type="date" name="reservation_date" required
                           value="{{ old('reservation_date', $dateValue) }}"
                           class="{{ $controlClass }}" style="{{ $controlStyle }}">
                    @error('reservation_date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Time --}}
                <div>
                    <label for="reservation_time" class="{{ $labelClass }}">Reservation Time <span class="text-red-500">*</span></label>
                    <input id="reservation_time" type="time" name="reservation_time" required
                           value="{{ old('reservation_time', $timeValue) }}"
                           class="{{ $controlClass }}" style="{{ $controlStyle }}">
                    @error('reservation_time') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div class="sm:col-span-2">
                    <label for="status" class="{{ $labelClass }}">Booking Status <span class="text-red-500">*</span></label>
                    <select id="status" name="status" required
                            class="{{ $controlClass }} admin-select" style="{{ $controlStyle }}">
                        <option value="pending" {{ old('status', $reservation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('status', $reservation->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ old('status', $reservation->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Special Request --}}
                <div class="sm:col-span-2">
                    <label for="message" class="{{ $labelClass }}">Special Request</label>
                    <textarea id="message" name="message" rows="4"
                              placeholder="Enter special request"
                              class="{{ $controlClass }} leading-6"
                              style="{{ $controlStyle }}">{{ old('message', $messageValue) }}</textarea>
                    @error('message') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

            </div>

            {{-- Form Buttons --}}
            <div class="mt-5 flex flex-wrap gap-2 border-t pt-4" style="border-color:var(--border);">

                <button type="submit"
                        class="admin-detail-action !px-3.5 !py-2 !text-xs"
                        style="color:#111c18;background:linear-gradient(135deg,#efd39a,#d6ad69 58%,#a17b41);">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>

                <a href="{{ route('admin.reservations.index') }}"
                   class="admin-detail-action !px-3.5 !py-2 !text-xs border"
                   style="border-color:var(--border);">
                    Cancel
                </a>

            </div>

        </form>

    </section>

</div>

@endsection
