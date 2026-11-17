<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Table | Royal Restaurant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: { serif: ['"Playfair Display"', 'serif'], sans: ['"DM Sans"', 'sans-serif'] },
            keyframes: { rise: { '0%': { opacity: 0, transform: 'translateY(18px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } } },
            animation: { rise: 'rise .7s ease-out both' }
        } } };
    </script>
</head>

@php
    $label = 'mb-1.5 block text-[10px] font-semibold uppercase tracking-widest text-white/55';
    $input = 'w-full rounded-xl border border-white/10 bg-white/5 py-3 pl-10 pr-3 text-sm text-white outline-none transition placeholder:text-white/30 focus:border-[#d6ad69] focus:bg-white/10 focus:ring-[3px] focus:ring-[#d6ad69]/20 [color-scheme:dark]';
    $icon  = 'pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-[#d6ad69]';

    // [name, label, type, icon, placeholder, default]
    $fields = [
        ['name',             'Full Name',          'text',  'fa-user',         'Enter your name',    null],
        ['email',            'Email',              'email', 'fa-envelope',     'Enter your email',   null],
        ['phone',            'Phone',              'tel',   'fa-phone',        'Enter phone number', null],
        ['guests',           'Number of Guests',   'number','fa-users',        '',                   2],
        ['reservation_date', 'Reservation Date',   'date',  'fa-calendar-day', '',                   null],
        ['reservation_time', 'Reservation Time',   'time',  'fa-clock',        '',                   null],
    ];
@endphp

<body class="relative min-h-screen overflow-x-hidden bg-[#0c1511] font-sans text-white antialiased">

<div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full bg-[#d6ad69]/20 blur-3xl"></div>
<div class="pointer-events-none absolute -bottom-32 -right-24 h-96 w-96 rounded-full bg-emerald-500/10 blur-3xl"></div>

<main class="relative flex min-h-screen items-center justify-center px-4 py-10">
    <div class="w-full max-w-xl animate-rise">

        <div class="mb-6 text-center">
            <p class="text-[10px] font-bold uppercase tracking-[4px] text-[#d6ad69]">Royal Restaurant</p>
            <h1 class="mt-2 font-serif text-3xl font-semibold sm:text-4xl">Book a Table</h1>
            <p class="mt-2 text-sm text-white/60">Fill out the form to reserve your table.</p>
        </div>

        @if($errors->any())
            <div class="mb-4 rounded-2xl border border-red-400/30 bg-red-500/10 p-4 text-xs text-red-200">
                <strong><i class="fas fa-circle-exclamation mr-1"></i> Please fix the following errors:</strong>
                <ul class="mt-1.5 list-disc pl-5">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <section class="overflow-hidden rounded-3xl border border-white/10 bg-white/[0.06] shadow-[0_30px_80px_rgba(0,0,0,.45)] backdrop-blur-xl">

            <div class="flex items-center gap-3 border-b border-white/10 px-6 py-5">
                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-[#efd39a] to-[#a17b41] text-sm text-[#111c18]">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold leading-tight">Reservation Details</h2>
                    <p class="text-[11px] text-white/50">Fill in the required fields below.</p>
                </div>
            </div>

            <form action="{{ route('reservation.store') }}" method="POST" class="px-6 py-6">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    @foreach($fields as [$name, $lb, $type, $ic, $ph, $def])
                    <div>
                        <label for="{{ $name }}" class="{{ $label }}">{{ $lb }} <span class="text-[#d6ad69]">*</span></label>
                        <div class="relative">
                            <i class="fas {{ $ic }} {{ $icon }}"></i>
                            <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $def) }}" required
                                   placeholder="{{ $ph }}" class="{{ $input }}"
                                   @if($type === 'number') min="1" max="50" @endif
                                   @if($name === 'reservation_date') min="{{ date('Y-m-d') }}" @endif>
                        </div>
                        @error($name)<p class="mt-1 text-xs text-red-300">{{ $message }}</p>@enderror
                    </div>
                    @endforeach

                    <div class="sm:col-span-2">
                        <label for="message" class="{{ $label }}">Special Request</label>
                        <div class="relative">
                            <i class="fas fa-message absolute left-3.5 top-4 text-xs text-[#d6ad69]"></i>
                            <textarea id="message" name="message" rows="4" placeholder="Any special request?"
                                      class="{{ $input }} resize-y leading-6">{{ old('message') }}</textarea>
                        </div>
                        @error('message')<p class="mt-1 text-xs text-red-300">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-6 flex flex-col gap-3 border-t border-white/10 pt-5 sm:flex-row">
                    <button type="submit" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-[#efd39a] via-[#d6ad69] to-[#a17b41] px-5 py-3 text-xs font-bold uppercase tracking-wider text-[#111c18] shadow-[0_8px_20px_rgba(214,173,105,.3)] transition hover:-translate-y-0.5 active:scale-95">
                        <i class="fas fa-paper-plane"></i> Submit Reservation
                    </button>
                    <a href="{{ route('home') }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-white/20 px-5 py-3 text-xs font-bold uppercase tracking-wider text-white transition hover:-translate-y-0.5 hover:bg-white/10 active:scale-95">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                </div>
            </form>
        </section>

        <p class="mt-6 text-center text-xs text-white/40">We look forward to welcoming you at Royal Restaurant.</p>
    </div>
</main>

</body>
</html>