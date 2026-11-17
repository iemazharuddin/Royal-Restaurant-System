
<aside id="sidebar" class="sidebar fixed top-0 left-0 h-screen z-50 border-r flex flex-col">

    <!-- LOGO -->
    <div class="p-6 border-b" style="border-color:var(--border)">
        <a href="{{ route('admin.dashboard') }}"
           class="brand text-2xl font-bold text-gold">
            Royal Restaurant
        </a>

        <p class="text-xs muted mt-1">Administration Panel</p>
    </div>

    <!-- NAVIGATION -->
    <div class="sidebar-scroll flex-1 p-4">

        <p class="text-[10px] font-bold uppercase tracking-widest muted px-3 mb-3">
            Main Menu
        </p>

        <nav class="space-y-2">

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link active">
                <span class="icon-3d">📊</span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.reservations.index') }}"
               class="nav-link">
                <span class="icon-3d">📅</span>
                <span>Reservations</span>
            </a>

            <a href="{{ route('admin.menu-items.index') }}"
               class="nav-link">
                <span class="icon-3d">🍽️</span>
                <span>Food Menu</span>
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="nav-link">
                <span class="icon-3d">📂</span>
                <span>Categories</span>
            </a>

            <a href="{{ route('admin.contacts.index') }}"
               class="nav-link">
                <span class="icon-3d">✉️</span>
                <span>Contact Messages</span>
            </a>

            <a href="{{ route('admin.settings.index') }}"
               class="nav-link">
                <span class="icon-3d">⚙️</span>
                <span>Settings</span>
            </a>

        </nav>

        <p class="text-[10px] font-bold uppercase tracking-widest muted px-3 mt-8 mb-3">
            Website
        </p>

        <a href="{{ route('home') }}"
           target="_blank"
           class="nav-link">
            <span class="icon-3d">🌐</span>
            <span>Visit Website</span>
        </a>

    </div>

    <!-- USER PROFILE + LOGOUT -->
    <div class="p-4 border-t" style="border-color:var(--border)">

        <div class="profile-box">

            <div class="w-11 h-11 rounded-full bg-gold text-black flex items-center justify-center font-bold text-lg flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold truncate">
                    {{ auth()->user()->name ?? 'Administrator' }}
                </p>

                <p class="text-xs muted truncate">
                    {{ auth()->user()->email ?? '' }}
                </p>
            </div>

        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf

            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold text-red-400 hover:bg-red-500/10 transition">

                <span>↪</span>
                <span>Logout</span>

            </button>
        </form>

    </div>

</aside>
