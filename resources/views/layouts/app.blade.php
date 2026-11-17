
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#111c18">

    <title>@yield('title', 'Admin Panel') | Royal Restaurant</title>

    <script>
        const savedTheme = localStorage.getItem('royal-theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);

        if (localStorage.getItem('royal-sidebar') === 'collapsed' && window.innerWidth >= 1024) {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: ['selector', '[data-theme="dark"]'],
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        display: ['Playfair Display', 'serif']
                    },
                    colors: { gold: '#d6ad69' }
                }
            }
        };
    </script>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style type="text/tailwindcss">
       
        :root {
            --gold:#d6ad69;
            --bg:#f8f5ee;
            --surface:#fff;
            --text:#111c18;
            --muted:#777a73;
            --border:#ded9cd;
        }


        [data-theme="dark"] {
            --bg:#0c1511;
            --surface:#16231c;
            --text:#f5f2ea;
            --muted:#aeb5ad;
            --border:#2a3b31;
            color-scheme:dark;
        }

        html{
            scrollbar-width:none;
        }
        @layer components {
            .surface {
                @apply rounded-[18px] border bg-white dark:bg-[#16231c]
                border-[#ded9cd] dark:border-[#2a3b31];
            }

            .muted { color:var(--muted); }
            .gold { color:var(--gold); }

            .admin-add-btn {
                @apply rounded-xl px-4 py-2.5 font-bold text-[#111c18]
                bg-[linear-gradient(135deg,#efd39a,#d6ad69_58%,#a17b41)]
                hover:-translate-y-0.5 transition;
            }

            .admin-danger-btn {
                @apply rounded-xl px-4 py-2.5 text-white font-bold
                bg-gradient-to-r from-red-500 to-red-700
                hover:-translate-y-0.5 transition;
            }

            .admin-table {
                @apply w-full border-separate border-spacing-0;
            }

            .admin-table th {
                @apply bg-[#f8f5ee] dark:bg-[#0c1511]
                text-[#777a73] dark:text-[#aeb5ad] font-bold;
            }

            .admin-table th,
            .admin-table td {
                @apply border-b border-[#ded9cd] dark:border-[#2a3b31]
                px-4 py-3;
            }
        }

        /* GLOBAL RESPONSIVE */
        html, body {
            min-height:100%;
            width:100%;
            max-width:100%;
            overflow-x:clip;
        }

        body {
            min-height:100vh;
        }

        /* MAIN LAYOUT */
        #adminMain {
            min-height:100vh;
            min-width:0;
            display:flex;
            flex-direction:column;
            flex:1 1 auto;
        }

        #adminMain > .page-content {
            flex:1 0 auto;
            min-width:0;
            width:100%;
        }

        #adminMain footer {
            margin-top:auto;
            flex-shrink:0;
            width:100%;
        }

        /* TABLE RESPONSIVE */
        .mobile-table,
        .overflow-x-auto {
            width:100%;
            max-width:100%;
            overflow-x:auto;
            -webkit-overflow-scrolling:touch;
        }

        .mobile-table table {
            min-width:650px;
        }

        table {
            max-width:100%;
        }

        th, td {
            white-space:nowrap;
        }

        img, video, svg {
            max-width:100%;
        }

        button, a {
            -webkit-tap-highlight-color:transparent;
        }

        /* MOBILE */
        @media(max-width:640px) {
            #adminMain {
                width:100%;
                margin-left:0;
            }

            #adminMain > .page-content {
                padding:12px;
            }

            .mobile-table table {
                min-width:600px;
            }

            th, td {
                padding:9px 10px;
                font-size:12px;
            }
        }

        /* TABLET */
        @media(min-width:641px) and (max-width:1023px) {
            #adminMain {
                width:100%;
                margin-left:0;
            }
        }

        /* LAPTOP */
        @media(min-width:1024px) {
            #adminMain {
                margin-left:236px;
                width:calc(100% - 236px);
            }

            html.sidebar-collapsed #sidebar {
                transform:translateX(-100%);
                visibility:hidden;
            }

            html.sidebar-collapsed #adminMain {
                margin-left:0;
                width:100%;
            }
        }

        .confirm-delete-dialog {
            width:min(100% - 32px,420px);
            border:1px solid rgba(220,53,69,.2);
            border-radius:24px;
            padding:26px;
            color:var(--text);
            background:var(--surface);
            margin:auto;
        }

        .confirm-delete-dialog::backdrop {
            background:rgba(9,18,14,.68);
        }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen w-full flex bg-[#f8f5ee] text-[#111c18]
dark:bg-[#0c1511] dark:text-[#f5f2ea] font-sans">

@php
    $user = auth()->user();

    $navItems = [
        ['admin.dashboard','admin.dashboard','fa-chart-pie','Dashboard'],
        ['admin.reservations.index','admin.reservations.*','fa-calendar-check','Reservations'],
        ['admin.menu-items.index','admin.menu-items.*','fa-utensils','Menu Items'],
        ['admin.categories.index','admin.categories.*','fa-layer-group','Categories'],
        ['admin.contacts.index','admin.contacts.*','fa-envelope','Contact Messages'],
        ['admin.customers.index','admin.customers.*','fa-users','Customers'],
        ['admin.orders.index','admin.orders.*','fa-receipt','Orders'],
        ['admin.tables.index','admin.tables.*','fa-chair','Tables Management'],
        ['admin.payments.index','admin.payments.*','fa-credit-card','Payments'],
        ['admin.offers.index','admin.offers.*','fa-tags','Offers'],
        ['admin.reviews.index','admin.reviews.*','fa-star','Reviews'],
        ['admin.gallery.index','admin.gallery.*','fa-images','Gallery'],
        ['admin.staff.index','admin.staff.*','fa-user-tie','Staff'],
        ['admin.roles.index','admin.roles.*','fa-user-shield','Roles & Permissions'],
        ['admin.reports.index','admin.reports.index','fa-chart-line','Reports'],
        ['admin.notifications.index','admin.notifications.*','fa-bell','Notifications'],
        ['admin.settings.index','admin.settings.*','fa-cog','Settings'],
    ];
@endphp

{{-- MOBILE OVERLAY --}}
<div id="overlay" onclick="closeSidebar()"
     class="hidden fixed inset-0 z-40 bg-black/60 lg:hidden"></div>

{{-- SIDEBAR --}}
<aside id="sidebar"
 class="fixed inset-y-0 left-0 z-50 w-[236px] max-w-[85vw]
 flex flex-col overflow-y-auto text-white
 -translate-x-full lg:translate-x-0 transition-transform duration-300
 bg-[linear-gradient(160deg,#1a2a22,#111c18_55%,#0c1511)]">

    <div class="flex items-center gap-3 px-4 py-4 border-b border-white/10">
        <span class="grid h-10 w-10 place-items-center border border-gold
        font-display text-2xl text-gold">R</span>

        <div>
            <div class="font-display text-xl tracking-[2px]">ROYAL</div>
            <small class="text-[8px] tracking-[3px] text-gold uppercase">Restaurant</small>
        </div>

        <button onclick="closeSidebar()" class="ml-auto lg:hidden">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <nav class="p-3 space-y-1">
        <p class="px-2 py-2 text-[9px] uppercase tracking-widest text-slate-500">
            Main Menu
        </p>

        @foreach($navItems as [$routeName,$pattern,$icon,$label])
            <a href="{{ route($routeName) }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-xs
               font-semibold transition hover:bg-white/10
               {{ request()->routeIs($pattern)
                    ? 'bg-gold/20 text-white border border-gold/20'
                    : 'text-[#b9c4bb] border border-transparent' }}">

                <span class="w-5 text-center">
                    <i class="fas {{ $icon }}"></i>
                </span>
                <span>{{ $label }}</span>
            </a>
        @endforeach
    </nav>

    <div class="mt-auto p-3 border-t border-white/10">
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 p-2 rounded-xl hover:bg-white/10">

            <span class="grid h-9 w-9 place-items-center rounded-lg bg-gold
            text-[#111c18] font-bold">
                {{ strtoupper(substr($user?->name ?? 'A',0,1)) }}
            </span>

            <span class="min-w-0">
                <span class="block text-xs font-bold truncate">
                    {{ $user?->name ?? 'Admin' }}
                </span>
                <span class="block text-[10px] text-slate-400 truncate">
                    {{ $user?->email }}
                </span>
            </span>
        </a>

        <a href="{{ url('/') }}" target="_blank"
           class="flex items-center gap-3 p-2 mt-1 text-xs text-slate-300 hover:text-gold">
            <i class="fas fa-globe"></i> Visit Website
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
            class="flex items-center gap-3 p-2 w-full text-xs text-red-300 hover:text-red-400">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<main id="adminMain"
 class="min-h-screen min-w-0 w-full flex-1 flex flex-col
 lg:ml-[236px] lg:w-[calc(100%-236px)] transition-all duration-300">

    {{-- HEADER --}}
    <header class="sticky top-0 z-30 shrink-0 flex items-center justify-between
    gap-3 px-3 sm:px-5 md:px-8 py-3 sm:py-4 border-b
    bg-white dark:bg-[#16231c] border-[#ded9cd] dark:border-[#2a3b31]">

        <div class="flex items-center gap-3 min-w-0">
            <button id="sidebarToggle" onclick="toggleSidebar()"
            class="h-10 w-10 shrink-0 rounded-xl bg-[#111c18] text-white">
                <i id="sidebarToggleIcon" class="fas fa-bars"></i>
            </button>

            <div class="min-w-0">
                <h2 class="font-extrabold text-base sm:text-2xl truncate">
                    @yield('page-heading','Admin Overview')
                </h2>
                <p class="text-[11px] muted truncate">
                    Welcome back, {{ $user?->name ?? 'Admin' }}!
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <button onclick="toggleTheme()"
            class="h-10 w-10 rounded-xl border border-[#ded9cd] dark:border-[#2a3b31]">
                <i id="themeIcon" class="fas fa-moon"></i>
            </button>

            <span class="hidden sm:block text-xs font-bold">
                {{ now()->format('d M Y') }}
            </span>

            <button onclick="toggleFullscreen()"
            class="h-10 w-10 rounded-xl border border-[#ded9cd] dark:border-[#2a3b31]">
                <i id="fullscreenIcon" class="fas fa-expand"></i>
            </button>

            <a href="{{ route('profile.edit') }}"
            class="h-10 w-10 grid place-items-center rounded-xl border border-[#ded9cd] dark:border-[#2a3b31]">
                <i class="fas fa-user"></i>
            </a>
        </div>
    </header>

    {{-- PAGE CONTENT --}}
    <div class="page-content flex-1 min-w-0 w-full p-3 sm:p-5 md:p-8">
        @yield('content')
    </div>

    {{-- FOOTER --}}
    <footer class="mt-auto shrink-0 w-full px-4 py-5 sm:py-6
    border-t border-[#ded9cd] dark:border-[#2a3b31]
    text-center text-xs muted">

        © {{ date('Y') }}
        <span class="font-bold text-gold">Royal Restaurant</span>
        . All Rights Reserved.

    </footer>
</main>

{{-- DELETE CONFIRMATION --}}
<dialog id="confirmDeleteDialog" class="confirm-delete-dialog">
    <div class="flex items-center gap-3">
        <div class="h-12 w-12 grid place-items-center rounded-xl bg-red-100 text-red-600">
            <i class="fas fa-trash-alt"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-red-500">Confirm action</p>
            <h2 id="confirmDeleteTitle" class="font-extrabold text-xl">
                Delete this item?
            </h2>
        </div>
    </div>

    <p class="mt-5 text-sm muted">
        Are you sure you want to delete
        <strong id="confirmDeleteName"></strong>?
        This action cannot be undone.
    </p>

    <div class="flex justify-end gap-3 mt-7">
        <button onclick="closeDeleteDialog()"
        class="rounded-xl border px-4 py-2 text-sm font-bold">
            Cancel
        </button>

        <button onclick="confirmDelete()"
        class="rounded-xl bg-red-600 text-white px-4 py-2 text-sm font-bold">
            Delete
        </button>
    </div>
</dialog>

{{-- JAVASCRIPT --}}
<script>
    const root = document.documentElement;
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const deleteDialog = document.getElementById('confirmDeleteDialog');

    let pendingDeleteForm = null;

    function isDesktop() {
        return window.innerWidth >= 1024;
    }

    function toggleSidebar() {
        if (isDesktop()) {
            root.classList.toggle('sidebar-collapsed');

            localStorage.setItem(
                'royal-sidebar',
                root.classList.contains('sidebar-collapsed') ? 'collapsed' : 'open'
            );
        } else {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }
    }

    function closeSidebar() {
        if (!isDesktop()) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    window.addEventListener('resize', () => {
        if (isDesktop()) closeSidebar();
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') closeSidebar();
    });

    function toggleTheme() {
        const next = root.getAttribute('data-theme') === 'dark'
            ? 'light' : 'dark';

        root.setAttribute('data-theme', next);
        localStorage.setItem('royal-theme', next);
        updateThemeIcon();
    }

    function updateThemeIcon() {
        document.getElementById('themeIcon').className =
            root.getAttribute('data-theme') === 'dark'
            ? 'fas fa-sun' : 'fas fa-moon';
    }

    async function toggleFullscreen() {
        try {
            if (document.fullscreenElement) {
                await document.exitFullscreen();
            } else {
                await document.documentElement.requestFullscreen();
            }
        } catch (error) {
            console.error(error);
        }
    }

    document.addEventListener('fullscreenchange', () => {
        document.getElementById('fullscreenIcon').className =
            document.fullscreenElement
            ? 'fas fa-compress' : 'fas fa-expand';
    });

    /* DELETE CONFIRMATION */
    document.addEventListener('submit', event => {
        const form = event.target.closest('form[data-confirm-delete]');

        if (!form) return;

        event.preventDefault();
        pendingDeleteForm = form;

        document.getElementById('confirmDeleteName').textContent =
            form.dataset.confirmDelete || 'this item';

        document.getElementById('confirmDeleteTitle').textContent =
            'Delete this item?';

        deleteDialog.showModal();
    });

    function closeDeleteDialog() {
        deleteDialog.close();
        pendingDeleteForm = null;
    }

    function confirmDelete() {
        if (!pendingDeleteForm) return;

        pendingDeleteForm.submit();
        pendingDeleteForm = null;
    }

    deleteDialog.addEventListener('click', event => {
        if (event.target === deleteDialog) closeDeleteDialog();
    });

    updateThemeIcon();
</script>

@stack('scripts')
</body>
</html>
