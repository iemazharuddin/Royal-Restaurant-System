{{-- resources/views/admin/partials/sidebar.blade.php --}}
{{-- Use in layout: @include('admin.partials.sidebar') --}}

@php
    $user = auth()->user();

    // [route, active pattern, Font Awesome icon, label, data-section, permission]
    $mainMenu = [
        ['admin.dashboard',          'admin.dashboard',        'fa-chart-pie',      'Dashboard',        'overview',      'dashboard.view'],
        ['admin.reservations.index', 'admin.reservations.*',   'fa-calendar-check', 'Reservations',     'orders',        'reservations.view'],
        ['admin.orders.index',       'admin.orders.*',         'fa-receipt',        'Orders',           'orders',        'orders.view'],
        ['admin.menu-items.index',   'admin.menu-items.*',     'fa-utensils',       'Food Menu',        'menu',          'menu-items.view'],
        ['admin.categories.index',   'admin.categories.*',     'fa-layer-group',    'Categories',       'categories',    'categories.view'],
        ['admin.customers.index',    'admin.customers.*',      'fa-users',          'Customers',        'customers',     'customers.view'],
        ['admin.tables.index',       'admin.tables.*',         'fa-chair',          'Tables',           'tables',        'tables.view'],
        ['admin.payments.index',     'admin.payments.*',       'fa-credit-card',    'Payments',         'payments',      'payments.view'],
        ['admin.offers.index',       'admin.offers.*',         'fa-tags',           'Offers',           'offers',        'offers.view'],
        ['admin.reviews.index',      'admin.reviews.*',        'fa-star',           'Reviews',          'reviews',       'reviews.view'],
        ['admin.gallery.index',      'admin.gallery.*',        'fa-images',         'Gallery',          'gallery',       'gallery.view'],
        ['admin.contacts.index',     'admin.contacts.*',       'fa-envelope',       'Contact Messages', 'customers',     'contacts.view'],
        ['admin.notifications.index','admin.notifications.*',  'fa-bell',           'Notifications',    'notifications', 'notifications.view'],
        ['admin.reports.index',      'admin.reports.*',        'fa-chart-line',     'Reports',          'reports',       'reports.view'],
    ];

    $managementMenu = [
        ['admin.staff.index',        'admin.staff.*',          'fa-user-tie',       'Staff',            'staff',         'staff.view'],
        ['admin.roles.index',        'admin.roles.*',          'fa-user-shield',    'Roles & Permissions', 'roles',      'roles.view'],
        ['admin.settings.index',     'admin.settings.*',       'fa-gear',           'Settings',         'settings',      'settings.view'],
    ];

    // Hide menu items the user is not allowed to open, and empty sections.
    $filter = fn ($items) => array_values(array_filter($items, fn ($i) => $user?->can($i[5])));

    $sections = array_filter([
        ['Main Menu',  $filter($mainMenu),       'mt-0'],
        ['Management', $filter($managementMenu), 'mt-8'],
    ], fn ($section) => count($section[1]) > 0);
@endphp

<!-- SIDEBAR -->
<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-50 w-[300px] h-dvh flex flex-col overflow-y-auto
              [scrollbar-width:none] [&::-webkit-scrollbar]:hidden
              text-white border-r border-white/5 shadow-[12px_0_35px_rgba(13,22,43,.12)]
              transition-transform duration-300 -translate-x-full lg:translate-x-0
              bg-[radial-gradient(circle_at_10%_5%,rgba(105,87,190,.24),transparent_32%),linear-gradient(160deg,#172542,#10192f_55%,#0b1223)]">

    <!-- BRAND -->
    <div class="shrink-0 px-5 py-5 border-b border-white/10 flex items-center justify-between gap-3">

        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 min-w-0">
            <div class="w-14 h-14 shrink-0 rounded-2xl flex items-center justify-center text-2xl text-[#17213a]
                        bg-[linear-gradient(145deg,#ffe3a3,#d49b36_55%,#a96b17)]
                        shadow-[0_8px_20px_rgba(214,168,75,.3),inset_0_2px_3px_rgba(255,255,255,.65),inset_0_-3px_5px_rgba(111,67,8,.25)]">
                <i class="fas fa-crown"></i>
            </div>

            <div class="min-w-0">
                <h1 class="font-display text-[1.25rem] leading-tight font-extrabold tracking-tight truncate">
                    Royal Restaurant
                </h1>
                <p class="text-[9px] uppercase tracking-[3px] text-gold mt-2 font-semibold">
                    ADMINISTRATION PANEL
                </p>
            </div>
        </a>

        <button type="button" onclick="closeSidebar()" aria-label="Close sidebar"
                class="lg:hidden shrink-0 w-9 h-9 flex items-center justify-center rounded-xl text-lg text-slate-400
                       border border-white/10 transition hover:text-white hover:bg-white/10">
            <i class="fas fa-xmark"></i>
        </button>

    </div>

    <!-- NAVIGATION -->
    <div class="shrink-0 px-4 py-5">

        @foreach($sections as [$heading, $items, $spacing])

            <p class="text-[10px] uppercase tracking-[2px] text-slate-500 font-bold px-3 mb-4 {{ $spacing }}">
                {{ $heading }}
            </p>

            <nav>
                @foreach($items as [$routeName, $pattern, $icon, $label, $section, $permission])
                    @php $active = request()->routeIs($pattern); @endphp

                    <a href="{{ route($routeName) }}" data-section="{{ $section }}"
                       class="group flex items-center gap-3.5 px-4 py-3 mb-2 rounded-2xl border text-[17px] font-semibold
                              transition duration-200 hover:text-white hover:bg-white/[.07] hover:border-white/[.08] hover:translate-x-[3px]
                              {{ $active
                                  ? 'text-white border-gold/20 bg-[linear-gradient(100deg,rgba(214,168,75,.2),rgba(112,91,194,.13))]'
                                  : 'text-[#b9c5d8] border-transparent' }}">

                        <span class="w-[42px] h-[42px] flex items-center justify-center shrink-0 rounded-xl text-lg transition duration-200
                                     group-hover:-translate-y-0.5 group-hover:text-[#18223a]
                                     group-hover:bg-[linear-gradient(145deg,#f8d48d,#bd8429)]
                                     group-hover:shadow-[0_7px_14px_rgba(214,168,75,.25)]
                                     {{ $active
                                         ? 'text-[#19233b] bg-[linear-gradient(145deg,#ffe2a0,#d49a36)] shadow-[0_5px_14px_rgba(214,168,75,.3),inset_0_2px_3px_rgba(255,255,255,.6)]'
                                         : 'text-white bg-[linear-gradient(145deg,#3b4f6d,#1a2a43)] shadow-[0_5px_10px_rgba(0,0,0,.24),inset_0_1px_2px_rgba(255,255,255,.2),inset_0_-2px_3px_rgba(0,0,0,.2)]' }}">
                            <i class="fas {{ $icon }}"></i>
                        </span>

                        <span>{{ $label }}</span>
                    </a>
                @endforeach
            </nav>

        @endforeach

    </div>

    <!-- USER + LOGOUT (end of the same list, pinned to bottom on tall screens) -->
    <div class="shrink-0 mt-auto px-4 pb-4">

        <div class="flex items-center gap-3 px-3 py-2">
            <div class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl text-lg font-extrabold text-[#1b2440]
                        bg-[linear-gradient(145deg,#fff0c6,#e8b653_55%,#bd7f24)]
                        shadow-[0_5px_12px_rgba(214,168,75,.28),inset_0_2px_3px_rgba(255,255,255,.75)]">
                {{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}
            </div>

            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-white truncate">
                    {{ $user?->name ?? 'Admin' }}
                </p>
                <p class="text-[11px] text-slate-400 truncate">
                    {{ $user?->getRoleNames()->first() ?? 'No role' }}
                </p>
            </div>

            <span class="inline-flex items-center gap-1 text-[10px] text-emerald-400 shrink-0">
                <i class="fas fa-circle text-[5px]"></i> Online
            </span>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-1">
            @csrf

            <button type="submit" title="Logout"
                    class="group w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl border border-transparent cursor-pointer
                           text-[17px] font-semibold text-[#ffc1c1] transition duration-200
                           hover:text-white hover:bg-red-500/15 hover:border-red-500/25 hover:translate-x-[3px]">
                <span class="w-[42px] h-[42px] flex items-center justify-center shrink-0 rounded-xl text-lg text-white transition duration-200
                             bg-[linear-gradient(145deg,#e34c61,#a91f3e)]
                             shadow-[0_5px_10px_rgba(0,0,0,.24),inset_0_1px_2px_rgba(255,255,255,.2),inset_0_-2px_3px_rgba(0,0,0,.2)]
                             group-hover:-translate-y-0.5 group-hover:shadow-[0_7px_14px_rgba(220,38,70,.3)]">
                    <i class="fas fa-sign-out-alt"></i>
                </span>
                <span>Logout</span>
            </button>
        </form>

    </div>

</aside>