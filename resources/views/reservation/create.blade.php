
@extends('layouts.app')

@section('title', 'Reserve Your Table | Royal Restaurant')

@section('content')

@php
    $label = 'mb-1.5 block text-[11px] font-semibold uppercase tracking-wider text-[#555a53]';
    $input = 'w-full rounded-xl border border-[#e4dfd2] bg-[#faf8f3] py-3 pl-10 pr-3 text-sm text-[#111c18] outline-none transition placeholder:text-[#a3a69e] focus:border-[#d6ad69] focus:bg-white focus:ring-4 focus:ring-[#d6ad69]/15';

    $fields = [
        ['name', 'Full Name', 'text', 'fa-user', 'Enter your name', null],
        ['email', 'Email Address', 'email', 'fa-envelope', 'Enter your email', null],
        ['phone', 'Phone Number', 'tel', 'fa-phone', 'Enter phone number', null],
        ['guests', 'Number of Guests', 'number', 'fa-users', '', 2],
        ['reservation_date', 'Reservation Date', 'date', 'fa-calendar-day', '', null],
        ['reservation_time', 'Reservation Time', 'time', 'fa-clock', '', null],
    ];
@endphp

<div class="min-h-screen bg-[#faf9f5] px-4 py-10 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-5xl">

        {{-- Success Notification --}}
        @if(session('success'))
            <div id="successAlert"
                 class="mb-6 flex items-center justify-between gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800 shadow-sm">

                <div class="flex items-center gap-3">
                    <i class="fas fa-circle-check text-xl text-green-600"></i>
                    <span>{{ session('success') }}</span>
                </div>

                <button type="button" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- Error Notification --}}
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <strong>
                    <i class="fas fa-circle-exclamation mr-2"></i>
                    Please fix the following errors:
                </strong>

                <ul class="mt-2 list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid overflow-hidden rounded-3xl bg-white shadow-xl lg:grid-cols-[0.8fr_1.2fr]">

            {{-- Left Information Panel --}}
            <div class="relative hidden min-h-[650px] overflow-hidden bg-[#111c18] p-10 text-white lg:flex lg:flex-col lg:justify-between">

                <img
                    src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1200&q=85"
                    class="absolute inset-0 h-full w-full object-cover opacity-40"
                    alt="Restaurant dining">

                <div class="absolute inset-0 bg-gradient-to-t from-[#0c1511] via-[#0c1511]/70 to-transparent"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <span class="grid h-12 w-12 place-items-center border border-[#d6ad69] font-serif text-2xl text-[#d6ad69]">
                            R
                        </span>

                        <div>
                            <h2 class="font-serif text-xl tracking-widest">ROYAL</h2>
                            <p class="text-[9px] uppercase tracking-[3px] text-[#d6ad69]">
                                Restaurant
                            </p>
                        </div>
                    </div>
                </div>

                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-[4px] text-[#efd39a]">
                        A Table Awaits
                    </p>

                    <h2 class="mt-4 font-serif text-5xl leading-tight">
                        More than a meal.
                        <span class="block italic text-[#efd39a]">A memory.</span>
                    </h2>

                    <ul class="mt-8 space-y-4 text-sm text-white/80">
                        <li>
                            <i class="fas fa-circle-check mr-2 text-[#d6ad69]"></i>
                            Easy online booking
                        </li>
                        <li>
                            <i class="fas fa-circle-check mr-2 text-[#d6ad69]"></i>
                            Booking confirmation
                        </li>
                        <li>
                            <i class="fas fa-circle-check mr-2 text-[#d6ad69]"></i>
                            Perfect for celebrations
                        </li>
                    </ul>
                </div>

                <p class="relative z-10 text-xs text-white/50">
                    © {{ date('Y') }} Royal Restaurant
                </p>
            </div>

            {{-- Right Booking Form --}}
            <div class="p-5 sm:p-8 lg:p-12">

                <a href="{{ route('home') }}"
                   class="mb-8 inline-flex items-center gap-2 text-xs font-semibold text-[#777a73] hover:text-[#a17b41]">
                    <i class="fas fa-arrow-left"></i>
                    Back to home
                </a>

                <p class="text-[11px] font-bold uppercase tracking-[4px] text-[#a77f42]">
                    Royal Restaurant
                </p>

                <h1 class="mt-2 font-serif text-3xl font-semibold text-[#111c18] sm:text-4xl">
                    Reserve Your Table
                </h1>

                <p class="mt-2 text-sm text-[#777a73]">
                    Complete the form below to book your table.
                </p>

                <form action="{{ route('reservation.store') }}"
                      method="POST"
                      class="mt-8 space-y-5">

                    @csrf

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        @foreach($fields as [$name, $lb, $type, $ic, $ph, $def])

                            <div>
                                <label for="{{ $name }}" class="{{ $label }}">
                                    {{ $lb }}
                                    <span class="text-[#d6ad69]">*</span>
                                </label>

                                <div class="relative">
                                    <input
                                        id="{{ $name }}"
                                        type="{{ $type }}"
                                        name="{{ $name }}"
                                        value="{{ old($name, $def) }}"
                                        placeholder="{{ $ph }}"
                                        required
                                        class="{{ $input }}"
                                        @if($type === 'number')
                                            min="1" max="50"
                                        @endif
                                        @if($name === 'reservation_date')
                                            min="{{ date('Y-m-d') }}"
                                        @endif
                                    >

                                    <i class="fas {{ $ic }} absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-[#b9b4a6]"></i>
                                </div>

                                @error($name)
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                        @endforeach

                    </div>

                    {{-- Special Request --}}
                    <div>
                        <label for="message" class="{{ $label }}">
                            Special Request
                        </label>

                        <textarea
                            id="message"
                            name="message"
                            rows="4"
                            placeholder="Any special request?"
                            class="w-full rounded-xl border border-[#e4dfd2] bg-[#faf8f3] p-4 text-sm outline-none transition focus:border-[#d6ad69] focus:bg-white focus:ring-4 focus:ring-[#d6ad69]/15"
                        >{{ old('message') }}</textarea>

                        @error('message')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button
                        type="submit"
                        class="group flex w-full items-center justify-center gap-3 rounded-xl bg-gradient-to-br from-[#efd39a] via-[#d6ad69] to-[#a17b41] px-6 py-4 text-xs font-bold uppercase tracking-widest text-[#111c18] shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl active:scale-[.98]"
                    >
                        Book Your Table

                        <i class="fas fa-arrow-right transition group-hover:translate-x-1"></i>
                    </button>

                    <p class="text-center text-[11px] text-[#9a9d95]">
                        <i class="fas fa-lock mr-1"></i>
                        Your details are only used to confirm your booking.
                    </p>

                </form>
            </div>

        </div>
    </div>
</div>

{{-- Auto hide success notification --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alert = document.getElementById('successAlert');

        if (alert) {
            setTimeout(function () {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';

                setTimeout(() => alert.remove(), 500);
            }, 5000);
        }
    });
</script>

@endsection
