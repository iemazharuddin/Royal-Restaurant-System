<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful | Royal Restaurant</title>
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
    $dateText = isset($reservation) && $reservation->reservation_date
        ? \Illuminate\Support\Carbon::parse($reservation->reservation_date)->format('D, d M Y') : null;
    $timeText = isset($reservation) && $reservation->reservation_time
        ? \Illuminate\Support\Carbon::parse($reservation->reservation_time)->format('h:i A') : null;
@endphp

<body class="relative min-h-screen overflow-x-hidden bg-[#0c1511] font-sans text-white antialiased">

{{-- soft glow background --}}
<div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full bg-[#d6ad69]/20 blur-3xl"></div>
<div class="pointer-events-none absolute -bottom-32 -right-24 h-96 w-96 rounded-full bg-emerald-500/10 blur-3xl"></div>

<main class="relative flex min-h-screen items-center justify-center px-4 py-10">
    <div class="w-full max-w-md animate-rise">

        {{-- Success icon --}}
        <div class="mb-6 text-center">
            <div class="relative mx-auto mb-5 grid h-20 w-20 place-items-center">
                <span class="absolute inset-0 animate-ping rounded-full bg-emerald-400/25"></span>
                <span class="relative grid h-20 w-20 place-items-center rounded-full bg-gradient-to-br from-emerald-300 to-emerald-600 text-3xl text-white shadow-[0_10px_40px_rgba(16,185,129,.45)]">
                    <i class="fas fa-check"></i>
                </span>
            </div>
            <p class="text-[10px] font-bold uppercase tracking-[4px] text-[#d6ad69]">Royal Restaurant</p>
            <h1 class="mt-2 font-serif text-3xl font-semibold">Booking Successful!</h1>
            <p class="mt-2 text-sm text-white/60">Thank you for choosing Royal Restaurant.</p>
        </div>

        {{-- Ticket card --}}
        <section class="overflow-hidden rounded-3xl border border-white/10 bg-white/[0.06] shadow-[0_30px_80px_rgba(0,0,0,.45)] backdrop-blur-xl">

            <div class="flex items-center justify-between gap-3 px-6 pb-5 pt-6">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-white/50">Reservation for</p>
                    <p class="mt-1 text-lg font-semibold">{{ $reservation->name ?? 'Guest' }}</p>
                </div>
                <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full border border-amber-400/30 bg-amber-400/10 px-3 py-1.5 text-[11px] font-bold text-amber-300">
                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-amber-300"></span> Pending
                </span>
            </div>

            {{-- ticket notches + dashed line --}}
            <div class="relative">
                <span class="absolute -left-3 top-1/2 h-6 w-6 -translate-y-1/2 rounded-full bg-[#0c1511]"></span>
                <span class="absolute -right-3 top-1/2 h-6 w-6 -translate-y-1/2 rounded-full bg-[#0c1511]"></span>
                <div class="mx-6 border-t border-dashed border-white/20"></div>
            </div>

            @if(isset($reservation))
            <div class="grid grid-cols-3 gap-3 px-6 py-6 text-center">
                @foreach([
                    ['fa-calendar-day', 'Date',   $dateText ?? $reservation->reservation_date],
                    ['fa-clock',        'Time',   $timeText ?? $reservation->reservation_time],
                    ['fa-users',        'Guests', $reservation->guests],
                ] as [$icon, $lb, $val])
                <div class="rounded-2xl border border-white/10 bg-white/5 px-2 py-4">
                    <i class="fas {{ $icon }} mb-2 text-base text-[#d6ad69]"></i>
                    <p class="text-[9px] font-semibold uppercase tracking-widest text-white/45">{{ $lb }}</p>
                    <p class="mt-1 text-[13px] font-semibold leading-tight">{{ $val }}</p>
                </div>
                @endforeach
            </div>
            @endif

            <div class="mx-6 mb-6 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 p-4 text-xs leading-5 text-emerald-200">
                <i class="fas fa-circle-check mr-1.5"></i>
                Our team will review your booking and confirm it shortly. We look forward to serving you!
            </div>

            <div class="flex flex-col gap-3 border-t border-white/10 bg-black/20 p-5 sm:flex-row">
                <a href="{{ route('home') }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-[#efd39a] via-[#d6ad69] to-[#a17b41] px-5 py-3 text-xs font-bold uppercase tracking-wider text-[#111c18] shadow-[0_8px_20px_rgba(214,173,105,.3)] transition hover:-translate-y-0.5 active:scale-95">
                    <i class="fas fa-home"></i> Back to Home
                </a>
                <a href="{{ route('reservation.create') }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-white/20 px-5 py-3 text-xs font-bold uppercase tracking-wider text-white transition hover:-translate-y-0.5 hover:bg-white/10 active:scale-95">
                    <i class="fas fa-rotate-right"></i> Book Again
                </a>
            </div>
        </section>

        <p class="mt-6 text-center text-xs text-white/40">Thank you for visiting Royal Restaurant!</p>
    </div>
</main>

</body>
</html>