
<!-- OVERVIEW -->
<section id="overview" class="page-section mb-9">

    <!-- PAGE TITLE -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">

        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">
                Welcome Back,
                <span class="text-gold">
                    {{ auth()->user()->name ?? 'Admin' }}
                </span>
            </h1>

            <p class="text-sm muted mt-2">
                Manage your restaurant from one place.
            </p>
        </div>

        <a href="{{ route('admin.menu-items.create') }}"
           class="inline-flex items-center justify-center bg-gold text-black px-5 py-3 rounded-lg text-sm font-bold hover:bg-yellow-300 transition">
            + Add New Dish
        </a>

    </div>

    <!-- STATISTICS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        <!-- CATEGORIES -->
        <div class="panel rounded-2xl p-5">
            <div class="flex justify-between items-center">
                <p class="text-sm muted">Total Categories</p>
                <span class="stat-icon">📂</span>
            </div>

            <h2 class="text-2xl font-extrabold mt-4">
                {{ $stats['categories'] ?? 0 }}
            </h2>

            <p class="text-xs muted mt-2">Food categories</p>
        </div>

        <!-- MENU ITEMS -->
        <div class="panel rounded-2xl p-5">
            <div class="flex justify-between items-center">
                <p class="text-sm muted">Total Menu Items</p>
                <span class="stat-icon">🍽️</span>
            </div>

            <h2 class="text-2xl font-extrabold mt-4">
                {{ $stats['menu_items'] ?? 0 }}
            </h2>

            <p class="text-xs muted mt-2">Restaurant dishes</p>
        </div>

        <!-- RESERVATIONS -->
        <div class="panel rounded-2xl p-5">
            <div class="flex justify-between items-center">
                <p class="text-sm muted">Total Reservations</p>
                <span class="stat-icon">🛍️</span>
            </div>

            <h2 class="text-2xl font-extrabold mt-4">
                {{ $stats['reservations'] ?? 0 }}
            </h2>

            <p class="text-xs muted mt-2">All reservations</p>
        </div>

        <!-- CONTACTS -->
        <div class="panel rounded-2xl p-5">
            <div class="flex justify-between items-center">
                <p class="text-sm muted">Contact Messages</p>
                <span class="stat-icon">✉️</span>
            </div>

            <h2 class="text-2xl font-extrabold mt-4">
                {{ $stats['contacts'] ?? 0 }}
            </h2>

            <p class="text-xs muted mt-2">Customer messages</p>
        </div>

    </div>

    <!-- PENDING RESERVATIONS -->
    <div class="panel rounded-2xl p-5 mt-4 flex flex-wrap items-center justify-between gap-3">

        <div>
            <h3 class="font-bold">Pending Reservations</h3>
            <p class="text-xs muted mt-1">
                Reservations waiting for confirmation
            </p>
        </div>

        <div class="text-2xl font-extrabold text-yellow-500">
            {{ $stats['pending_reservations'] ?? 0 }}
        </div>

    </div>

</section>
