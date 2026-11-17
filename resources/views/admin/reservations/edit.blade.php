
@extends('layouts.admin')

@php
    $labelClass = 'muted mb-1 block text-[10px] font-semibold uppercase tracking-wide';

    $controlClass = 'w-full rounded-xl border px-3 py-2.5 text-[13px] outline-none transition focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/10';

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
    select.admin-select option {
        font-size: 13px;
        padding: 8px 10px;
        background: var(--surface);
        color: var(--text);
    }

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
            box-shadow: 0 14px 34px rgba(9,18,14,.18);
        }

        select.admin-select:not([multiple])::picker-icon {
            color: var(--muted);
            transition: rotate .2s ease;
        }

        select.admin-select:not([multiple]):open::picker-icon {
            rotate: 180deg;
        }

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
            background: rgba(214,173,105,.18);
        }

        select.admin-select:not([multiple]) option:checked {
            background: rgba(214,173,105,.28);
            font-weight: 700;
        }

        select.admin-select:not([multiple]) option::checkmark {
            display: none;
        }
    }

    /* Gold + White buttons */
    .gold-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border: 1px solid #c69b55;
        border-radius: 11px;
        color: #17251d;
        background: linear-gradient(135deg,#f4dfb5 0%,#d6ad69 55%,#b68b4c 100%);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .2px;
        box-shadow: 0 5px 13px rgba(166,126,61,.20);
        transition: all .25s ease;
        cursor: pointer;
    }

    .gold-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 9px 19px rgba(166,126,61,.30);
        filter: brightness(1.04);
    }

    .gold-btn:active {
        transform: translateY(0) scale(.98);
    }

    .white-gold-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border: 1px solid var(--border);
        border-radius: 11px;
        color: var(--text);
        background: var(--surface);
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 3px 9px rgba(0,0,0,.04);
        transition: all .25s ease;
        cursor: pointer;
    }

    .white-gold-btn:hover {
        transform: translateY(-2px);
        border-color: #d6ad69;
        color: #b68b4c;
        box-shadow: 0 7px 15px rgba(166,126,61,.12);
    }

    .white-gold-btn:active {
        transform: translateY(0) scale(.98);
    }

    .gold-btn:focus-visible,
    .white-gold-btn:focus-visible {
        outline: 3px solid rgba(214,173,105,.35);
        outline-offset: 3px;
    }

    @media(max-width: 480px) {
        .gold-btn,
        .white-gold-btn {
            flex: 1;
            padding: 10px 12px;
        }
    }
</style>
@endpush

@section('content')

<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    {{-- Page Heading --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">
                Management
            </p>

            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl"
                style="font-family:'Playfair Display',serif;">
                Edit Reservation
            </h1>

            <p class="muted mt-1 text-xs">
                Update the existing reservation information.
            </p>
        </div>

        <a href="{{ route('admin.reservations.index') }}"
           class="white-gold-btn">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-500/20 bg-green-500/10 p-3 text-xs text-green-600">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-300 bg-red-50 p-3 text-xs text-red-700">
            <strong>
                <i class="fas fa-exclamation-circle mr-1"></i>
                Please fix the following errors:
            </strong>

            <ul class="mt-1.5 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        {{-- Card Header --}}
        <div class="mb-5 flex items-center gap-3 border-b pb-4"
             style="border-color:var(--border);">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm"
                 style="color:#e6c27e;background:linear-gradient(145deg,#2c4236,#111c18);">
                <i class="fas fa-pen-to-square"></i>
            </div>

            <div class="min-w-0">
                <h2 class="text-base font-bold leading-tight">
                    Update Information
                </h2>

                <p class="muted mt-1 text-[11px]">
                    Reservation for {{ $reservation->name }}
                </p>
            </div>
        </div>

        <form action="{{ route('admin.reservations.update', $reservation->id) }}"
              method="POST"
              id="reservationForm">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                {{-- Customer Name --}}
                <div>
                    <label for="name" class="{{ $labelClass }}">
                        Customer Name <span class="text-red-500">*</span>
                    </label>

                    <input id="name"
                           type="text"
                           name="name"
                           required
                           value="{{ old('name', $reservation->name) }}"
                           placeholder="Enter customer name"
                           class="{{ $controlClass }}"
                           style="{{ $controlStyle }}">

                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="{{ $labelClass }}">
                        Email Address <span class="text-red-500">*</span>
                    </label>

                    <input id="email"
                           type="email"
                           name="email"
                           required
                           value="{{ old('email', $reservation->email) }}"
                           placeholder="Enter email address"
                           class="{{ $controlClass }}"
                           style="{{ $controlStyle }}">

                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="{{ $labelClass }}">
                        Phone Number <span class="text-red-500">*</span>
                    </label>

                    <input id="phone"
                           type="text"
                           name="phone"
                           required
                           value="{{ old('phone', $reservation->phone) }}"
                           placeholder="Enter phone number"
                           class="{{ $controlClass }}"
                           style="{{ $controlStyle }}">

                    @error('phone')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Guests --}}
                <div>
                    <label for="guests" class="{{ $labelClass }}">
                        Number of Guests <span class="text-red-500">*</span>
                    </label>

                    <input id="guests"
                           type="number"
                           name="guests"
                           min="1"
                           max="50"
                           required
                           value="{{ old('guests', $guestsValue) }}"
                           placeholder="Enter number of guests"
                           class="{{ $controlClass }}"
                           style="{{ $controlStyle }}">

                    @error('guests')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date --}}
                <div>
                    <label for="reservation_date" class="{{ $labelClass }}">
                        Reservation Date <span class="text-red-500">*</span>
                    </label>

                    <input id="reservation_date"
                           type="date"
                           name="reservation_date"
                           required
                           value="{{ old('reservation_date', $dateValue) }}"
                           class="{{ $controlClass }}"
                           style="{{ $controlStyle }}">

                    @error('reservation_date')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Time --}}
                <div>
                    <label for="reservation_time" class="{{ $labelClass }}">
                        Reservation Time <span class="text-red-500">*</span>
                    </label>

                    <input id="reservation_time"
                           type="time"
                           name="reservation_time"
                           required
                           value="{{ old('reservation_time', $timeValue) }}"
                           class="{{ $controlClass }}"
                           style="{{ $controlStyle }}">

                    @error('reservation_time')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="sm:col-span-2">
                    <label for="status" class="{{ $labelClass }}">
                        Booking Status <span class="text-red-500">*</span>
                    </label>

                    <select id="status"
                            name="status"
                            required
                            class="{{ $controlClass }} admin-select"
                            style="{{ $controlStyle }}">

                        <option value="pending"
                            {{ old('status', $reservation->status) == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="confirmed"
                            {{ old('status', $reservation->status) == 'confirmed' ? 'selected' : '' }}>
                            Confirmed
                        </option>

                        <option value="cancelled"
                            {{ old('status', $reservation->status) == 'cancelled' ? 'selected' : '' }}>
                            Cancelled
                        </option>
                    </select>

                    @error('status')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Special Request --}}
                <div class="sm:col-span-2">
                    <label for="message" class="{{ $labelClass }}">
                        Special Request
                    </label>

                    <textarea id="message"
                              name="message"
                              rows="4"
                              placeholder="Enter special request"
                              class="{{ $controlClass }} leading-6"
                              style="{{ $controlStyle }}">{{ old('message', $messageValue) }}</textarea>

                    @error('message')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Form Buttons --}}
            <div class="mt-6 flex flex-wrap items-center gap-2.5 border-t pt-5"
                 style="border-color:var(--border);">

                <button type="submit"
                        id="saveButton"
                        class="gold-btn">

                    <i class="fas fa-save"></i>
                    <span>Save Changes</span>
                </button>

                <a href="{{ route('admin.reservations.index') }}"
                   class="white-gold-btn">

                    <i class="fas fa-times"></i>
                    Cancel
                </a>

            </div>

        </form>
    </section>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('reservationForm')?.addEventListener('submit', function () {
        const button = document.getElementById('saveButton');

        if (button) {
            button.disabled = true;
            button.style.opacity = '0.75';
            button.style.cursor = 'wait';

            button.innerHTML = `
                <i class="fas fa-spinner fa-spin"></i>
                <span>Saving...</span>
            `;
        }
    });
</script>
@endpush
