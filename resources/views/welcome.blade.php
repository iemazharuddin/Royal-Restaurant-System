<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111c18">
    <meta name="description" content="Welcome to Royal Restaurant. Enjoy exceptional food, memorable dining and warm hospitality.">
    <title>Royal Restaurant | A Royal Dining Experience</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = { theme: { extend: {
        colors: { gold:'#d6ad69','gold-light':'#efd39a','gold-dark':'#a17b41',dark:'#111c18','dark-2':'#192720',cream:'#f8f5ee',sand:'#f0ece3' },
        fontFamily: { serif:['"Playfair Display"','serif'], sans:['"DM Sans"','sans-serif'] }
    } } };
    </script>
    <style type="text/tailwindcss">
        @layer components {
            .section-label { @apply mb-4 text-[11px] font-bold uppercase tracking-[4px] text-[#a77f42]; }
            .section-title { @apply font-serif text-[clamp(34px,5vw,57px)] font-medium leading-[1.15]; }
            .section-desc { @apply text-[15px] leading-[1.9] text-[#777a73]; }
            .gold-btn { @apply inline-flex cursor-pointer items-center justify-center gap-2 border border-gold bg-gold px-5 py-3 text-[10px] font-bold uppercase tracking-[1.5px] text-[#172019] transition duration-300 hover:-translate-y-0.5 hover:bg-transparent hover:text-gold active:scale-95 sm:gap-3 sm:px-6 sm:py-4 sm:text-xs; }
            .outline-btn { @apply inline-flex items-center justify-center gap-2 border border-white/70 px-5 py-3 text-[10px] font-bold uppercase tracking-[1.5px] text-white transition duration-300 hover:bg-white hover:text-dark active:scale-95 sm:gap-3 sm:px-6 sm:py-4 sm:text-[11px]; }
            .nav-link { @apply text-[11px] uppercase tracking-[1px] transition hover:text-gold; }
            .menu-link { @apply mt-5 inline-flex items-center gap-2.5 text-[10px] font-bold uppercase tracking-[1.5px] text-[#9e773c] transition-all hover:gap-4; }
            .reveal { @apply translate-y-9 opacity-0 transition duration-700 ease-out; }
            .reveal.visible { @apply translate-y-0 opacity-100; }
            .footer-link { @apply text-[13px] text-[#adb5ae] transition-all hover:pl-1 hover:text-gold; }
        }
        html { scrollbar-width: none; }
        html, body { max-width: 100%; overflow-x: clip; }
        .hero-video { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; z-index:0; }
        .hero-overlay {position:absolute; inset:0; z-index:1; background: linear-gradient(90deg,rgba(9,18,14,.82),rgba(9,18,14,.35) 65%,rgba(9,18,14,.2)), linear-gradient(0deg,rgba(8,16,12,.62),transparent 65%); }
        .menu-card { border-bottom:3px solid transparent; transition: translate .5s ease, box-shadow .5s ease, border-color .5s ease, opacity .7s ease, transform .7s ease; }
        .menu-card img { transition: transform .7s ease; }
        .menu-card h3 { transition: color .4s ease; }
        .menu-card:hover, .menu-card.is-active { translate:0 -8px; border-color:#d6ad69; box-shadow:0 22px 45px rgba(0,0,0,.12); }
        .menu-card:hover img, .menu-card.is-active img { transform:scale(1.1); }
        .menu-card:hover h3, .menu-card.is-active h3 { color:#a17b41; }
        .menu-card:hover .menu-link, .menu-card.is-active .menu-link { gap:1rem; }
        .music-player-button {position:fixed; right:22px; bottom:22px; z-index:99999; display:flex; align-items:center; gap:10px; padding:13px 19px; border:1px solid #efd39a; background:#d6ad69; color:#111c18; font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; box-shadow:0 8px 25px rgba(0,0,0,.25); transition:all .3s ease; cursor:pointer; }
        .music-player-button:hover { background:#efd39a; transform:translateY(-3px); }
        .music-player-button.playing { background:#111c18; color:#efd39a; }
        .logout-btn { cursor:pointer; }
        /* Carousels / tabs / map */
        .rc-track{display:flex;gap:14px;overflow-x:auto;scroll-snap-type:x mandatory;scroll-behavior:smooth;scrollbar-width:none}
        .rc-track::-webkit-scrollbar{display:none}
        .rc-track>*{scroll-snap-align:start;flex:0 0 auto}
        .rc-arrow{position:absolute;top:50%;z-index:5;display:grid;height:42px;width:42px;translate:0 -50%;place-items:center;border-radius:9999px;background:#d6ad69;color:#111c18;box-shadow:0 6px 18px rgba(0,0,0,.25);transition:.3s}
        .rc-arrow:hover{background:#111c18;color:#efd39a}
        .tab-btn{padding:10px 20px;font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;transition:.3s}
        .tab-btn.on{background:#d6ad69;color:#111c18;box-shadow:0 8px 20px rgba(214,173,105,.35)}
        .tab-btn:not(.on):hover{color:#a17b41}
        #royalMap{height:420px;z-index:1}
        @media (max-width:640px) { .hero-video { object-position:center; } .music-player-button { right:14px; bottom:14px; padding:12px 15px; font-size:10px; } }
        @media (prefers-reduced-motion: reduce) { .reveal { opacity:1; transform:none; } }
    </style>
</head>

<body class="overflow-x-hidden bg-cream font-sans text-dark">

@php
    $u = fn ($id) => "https://images.unsplash.com/{$id}?auto=format&fit=crop&w=700&q=80";
    $ambience = ['photo-1517248135467-4c7edcad34c4','photo-1555396273-367ea4eb4db5','photo-1559339352-11d035aa65de','photo-1414235077428-338989a2e8c0','photo-1550966871-3ed3cdb5ed0c'];
    $food = ['photo-1565557623262-b51c2513a641','photo-1544025162-d76694265947','photo-1547592180-85f173990554','photo-1476224203421-9ac39bcb3327','photo-1504674900247-0877df9cc836'];
    $locations = [
        ['city'=>'Rawalpindi','addr'=>'Murree Road, Saddar, Rawalpindi','tel'=>'+92 51 5130304','wa'=>'+92 325 0000669','hrs'=>'Mon-Thu: 11 AM - 11:30 PM','lat'=>33.5958,'lng'=>73.0479],
        ['city'=>'Murree','addr'=>'Park Ridge Apartment, Lower Topa, Murree','tel'=>'+92 325 0000662','wa'=>'+92 325 0000661','hrs'=>'Mon-Sun: 9 AM - 12 AM','lat'=>33.9100,'lng'=>73.4000],
        ['city'=>'Peshawar','addr'=>'Peshawar Cantonment, Peshawar','tel'=>'+92 91 7242224','wa'=>'+92 301 1189124','hrs'=>'Mon-Sat: 12 PM - 11:30 PM','lat'=>34.0049,'lng'=>71.5370],
        ['city'=>'Bhera (Fusion)','addr'=>'Motorway Service Area, Bhera (North)','tel'=>'+92 309 2108000','wa'=>'+92 308 2108000','hrs'=>'Mon-Sun: 7 AM - 2 AM','lat'=>32.4845,'lng'=>72.9186],
    ];
    $menuCities = ['Islamabad','Lahore','Rawalpindi','Murree','Peshawar','Bhera (Fusion)'];
    $mapPlaces = array_merge($locations, [
        ['city'=>'Islamabad','addr'=>'','lat'=>33.6844,'lng'=>73.0479],
        ['city'=>'Lahore','addr'=>'','lat'=>31.5204,'lng'=>74.3587],
    ]);
@endphp

    <header id="navbar" class="fixed inset-x-0 top-0 z-[1000] flex h-[75px] items-center border-b border-white/15 bg-[#0f1814]/30 backdrop-blur-md transition-all duration-300 md:h-[88px]">
        <div class="mx-auto flex w-[90%] max-w-[1200px] items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3 text-white">
                <div class="grid h-10 w-10 place-items-center border border-gold font-serif text-[28px] text-gold sm:h-[45px] sm:w-[45px]"> R</div>
                <div>
                    <div class="font-serif text-lg tracking-[2px] sm:text-[21px]">ROYAL</div>
                    <small class="mt-1 block text-[8px] uppercase tracking-[3px] text-gold">Restaurant</small>
                </div>
            </a>
            <button id="menuToggle" class="text-[23px] text-white md:hidden" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navLinks"><i class="fas fa-bars"></i></button>
            <nav id="navLinks" class="absolute left-0 top-[75px] hidden w-full flex-col items-start gap-6 border-t border-gold/20 bg-dark px-[5%] py-6 text-white md:static md:flex md:w-auto md:flex-row md:items-center md:gap-4 md:border-0 md:bg-transparent md:p-0 lg:gap-6">
                <a href="#home" class="nav-link">Home</a>
                <a href="#about" class="nav-link">Our Story</a>
                <a href="#menu" class="nav-link">Menu</a>
                <a href="#experience" class="nav-link">Experience</a>
                <a href="#gallery" class="nav-link">Gallery</a>
                <a href="#locations" class="nav-link">Locations</a>
                <a href="#contact" class="nav-link">Contact</a>
                <div class="flex w-full flex-col gap-3 md:ml-2 md:w-auto md:flex-row md:items-center md:gap-3">
                    @auth
                    <a href="{{ route('dashboard') }}" class="nav-link border border-white/50 px-4 py-3 text-center hover:border-gold"><i class="fas fa-gauge-high mr-1"></i> Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full md:w-auto">
                        @csrf
                        <button type="submit" class="logout-btn nav-link w-full border border-white/50 px-4 py-3 text-center uppercase hover:border-red-400 hover:text-red-400"><i class="fas fa-right-from-bracket mr-1"></i> Logout</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="nav-link border border-white/50 px-4 py-3 text-center hover:border-gold"><i class="fas fa-right-to-bracket mr-1"></i> Login</a>
                    @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="nav-link border border-white/50 px-4 py-3 text-center hover:border-gold"><i class="fas fa-user-plus mr-1"></i> Register</a>
                    @endif
                    @endauth
                    <a href="{{ route('reservation.create') }}" class="border border-gold bg-gold px-4 py-3 text-center text-[11px] font-bold uppercase tracking-[1px] text-dark transition hover:bg-transparent hover:text-gold">Reserve a Table</a>
                </div>
            </nav>
        </div>
    </header>

    {{-- HERO --}}
    <section id="home" class="relative isolate flex h-[100svh] min-h-[520px] items-start overflow-hidden pb-[80px] pt-[92px] md:items-center text-white md:pt-[104px]">
        <video id="royalHeroVideo" class="hero-video" autoplay muted loop playsinline preload="metadata" aria-label="Royal Restaurant cinematic background video">
            <source src="{{ asset('videos/royal-restaurant.mp4') }}" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
        <div class="relative z-10 mx-auto w-[90%] max-w-[1200px]">
            <div class="max-w-[790px]">
                <p class="mb-3 text-[9px] font-semibold uppercase tracking-[3px] text-gold-light sm:mb-6 sm:text-xs sm:tracking-[5px]"> Welcome to Royal Restaurant</p>
                <h1 class="mb-3 font-serif text-[34px] font-medium leading-[1.12] sm:mb-6 sm:text-[clamp(38px,min(5vw,9vh),68px)] sm:leading-[1.08]"> Taste the Art<span class="block italic text-gold-light transition-colors duration-300 hover:text-gold">of Fine Dining.</span></h1>
                <p class="mb-4 max-w-[560px] text-[12px] leading-[1.8] text-white/80 sm:mb-7 sm:text-[14px] sm:leading-[1.8] lg:text-[15px]"> Discover exceptional flavours, elegant surroundings and warm hospitality. Every visit to Royal Restaurant is an invitation to create a beautiful memory.</p>
                <div class="flex flex-col items-start gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:gap-4">
                    <a href="{{ route('reservation.create') }}" class="gold-btn">Reserve Your Table <i class="fas fa-arrow-right"></i></a>
                    <a href="#menu" class="outline-btn">Discover Our Menu<i class="fas fa-angle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="absolute inset-x-0 bottom-5 z-10 mx-auto flex w-[90%] max-w-[1200px] items-center justify-between text-[9px] uppercase tracking-[2px] text-white/70 sm:bottom-9 sm:text-[11px]">
            <div class="flex items-center gap-3"><a href="https://www.facebook.com/profile.php?id=61590594772205" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="grid h-9 w-9 place-items-center rounded-full border border-white/30 bg-white/10 text-sm text-white backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:border-[#1877F2] hover:bg-[#1877F2] sm:h-10 sm:w-10"><i class="fab fa-facebook-f"></i></a>
                <a href="https://wa.me/923005371521?text=Assalam%20o%20Alaikum%2C%20I%20would%20like%20to%20know%20more%20about%20Royal%20Restaurant." target="_blank" rel="noopener noreferrer" aria-label="WhatsApp" class="grid h-9 w-9 place-items-center rounded-full border border-white/30 bg-white/10 text-base text-white backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:border-[#25D366] hover:bg-[#25D366] sm:h-10 sm:w-10"><i class="fab fa-whatsapp"></i></a>
            </div>
            <div class="absolute left-1/2 hidden -translate-x-1/2 items-center gap-3 sm:flex"><span>Scroll to Discover</span><i class="fas fa-arrow-down text-gold"></i></div>
        </div>
    </section>

    <section id="about" class="overflow-hidden bg-cream py-[75px] sm:py-[110px]">
        <div class="mx-auto grid w-[90%] max-w-[1200px] items-center gap-12 md:grid-cols-2 lg:gap-[90px]">
            <div class="reveal relative min-h-[400px] sm:min-h-[490px] lg:min-h-[540px]">
                <img class="h-[360px] w-[82%] object-cover sm:h-[490px]" src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1000&q=85" alt="Beautiful restaurant dining experience" loading="lazy">
                <img class="absolute bottom-0 right-0 h-[190px] w-[47%] border-[7px] border-cream object-cover sm:h-[270px] sm:border-[10px]" src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=700&q=85" alt="Elegant restaurant interior" loading="lazy">
            </div>
            <div class="reveal">
                <p class="section-label">A Story of Flavour</p>
                <h2 class="section-title">A Tradition of <span class="text-gold">Great Taste.</span></h2>
                <p class="section-desc my-6">At Royal Restaurant, we believe that extraordinary dining begins with exceptional ingredients and thoughtful preparation.</p>
                <p class="section-desc my-6">Our passion is bringing people together through memorable food, welcoming hospitality and an atmosphere designed for special moments.</p>
                <p class="mt-8 border-t border-[#ded9cd] pt-6 font-serif text-[23px] italic text-[#8b6a38]">The Royal Way of Dining</p>
                <a href="#experience" class="menu-link">Discover Our Story <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <section class="bg-dark py-10 text-white">
        <div class="mx-auto grid w-[90%] max-w-[1200px] gap-7 sm:grid-cols-3 sm:gap-8">
            @foreach([['fa-utensils','Exceptional Cuisine','Thoughtfully prepared flavours'],['fa-concierge-bell','Warm Hospitality','Service with care and attention'],['fa-star','Memorable Moments','A setting for special occasions']] as [$icon, $title, $text])
            <div class="border-b border-white/15 pb-6 text-center last:border-0 last:pb-0 sm:border-b-0 sm:border-r sm:pb-0 sm:last:border-r-0">
                <i class="fas {{ $icon }} mb-3 text-2xl text-gold"></i>
                <h4 class="mb-2 font-serif text-xl font-medium">{{ $title }}</h4>
                <p class="text-xs text-[#aeb5ad]">{{ $text }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- MENU (dynamic from dashboard) + click popup with sizes & prices --}}
    @php
    $fallbackImg = fn ($id) => "https://images.unsplash.com/{$id}?auto=format&fit=crop&w=800&q=85";

    /*
      PRICE DATA: yahan apni asli dishes aur prices likhein.
      'Category Name' => [ ['name'=>'Dish', 'prices'=>['Size'=>Rs, ...]], ... ]
      Sirf ek price ho to: 'prices'=>['Full'=>1200]
    */
    $menuData = [
        'Royal Barbecue' => [
            ['name' => 'Chicken Tikka',   'prices' => ['Quarter' => 450, 'Half' => 850, 'Full' => 1600]],
            ['name' => 'Seekh Kabab',     'prices' => ['4 Pcs' => 600, '8 Pcs' => 1150]],
            ['name' => 'Mutton Chops',    'prices' => ['Half Kg' => 2200, '1 Kg' => 4200]],
        ],
        'Pakistani Cuisine' => [
            ['name' => 'Chicken Karahi',  'prices' => ['Half' => 1400, 'Full' => 2600]],
            ['name' => 'Mutton Handi',    'prices' => ['Half' => 1900, 'Full' => 3600]],
            ['name' => 'Daal Mash',       'prices' => ['Full' => 650]],
        ],
        'Special Platters' => [
            ['name' => 'Family Platter',  'prices' => ['Medium' => 3500, 'Large' => 5200]],
            ['name' => 'BBQ Mix Platter', 'prices' => ['Small' => 2400, 'Large' => 4600]],
        ],
    ];

    $cards = collect($categories ?? [])->map(function ($c) use ($fallbackImg, $menuData) {
        // Dashboard category ke "items" hon to wahan se, warna $menuData se
        $items = isset($c->items) && count($c->items)
            ? collect($c->items)->map(fn ($i) => ['name' => $i->name, 'prices' => (array) ($i->prices ?? ['Price' => $i->price])])->all()
            : ($menuData[$c->name] ?? []);
        return [
            'img'   => $c->image ? (\Illuminate\Support\Str::startsWith($c->image, ['http://', 'https://']) ? $c->image : asset('storage/' . $c->image)) : $fallbackImg('photo-1476224203421-9ac39bcb3327'),
            'alt'   => $c->name, 'tag' => $c->tag ?? 'Our Selection', 'name' => $c->name, 'desc' => $c->description,
            'items' => $items,
        ];
    });

    if ($cards->isEmpty()) {
        $cards = collect([
            ['img' => $fallbackImg('photo-1544025162-d76694265947'), 'alt' => 'Grilled barbecue food', 'tag' => 'From the Grill', 'name' => 'Royal Barbecue', 'desc' => 'Enjoy the rich character of grilled food prepared for a memorable dining experience.', 'items' => $menuData['Royal Barbecue']],
            ['img' => $fallbackImg('photo-1565557623262-b51c2513a641'), 'alt' => 'Traditional curry', 'tag' => 'Traditional Flavours', 'name' => 'Pakistani Cuisine', 'desc' => 'Celebrate familiar culinary traditions with flavourful dishes and inviting aromas.', 'items' => $menuData['Pakistani Cuisine']],
            ['img' => $fallbackImg('photo-1476224203421-9ac39bcb3327'), 'alt' => 'Plated restaurant food', 'tag' => "Chef's Selection", 'name' => 'Special Platters', 'desc' => 'Beautifully presented selections made for sharing with friends and family.', 'items' => $menuData['Special Platters']],
        ]);
    }
    @endphp
    <section id="menu" class="bg-sand py-[75px] sm:py-[110px]">
        <div class="mx-auto w-[90%] max-w-[1200px]">
            <div class="reveal mx-auto mb-14 max-w-[650px] text-center">
                <p class="section-label">A Taste of Royalty</p>
                <h2 class="section-title">Our Signature <span class="text-gold">Selections</span></h2>
                <p class="section-desc mt-5">Kisi bhi card par click karein aur items ki prices dekhein.</p>
            </div>
            <div id="menuGrid" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($cards as $idx => $card)
                <button type="button" data-open-menu="{{ $idx }}" class="menu-card reveal group block overflow-hidden bg-white text-left" aria-label="{{ $card['name'] }}">
                    <div class="h-[245px] overflow-hidden sm:h-[265px]"><img src="{{ $card['img'] }}" alt="{{ $card['alt'] }}" loading="lazy" class="h-full w-full object-cover"></div>
                    <div class="p-7">
                        <small class="text-[10px] font-bold uppercase tracking-[2px] text-[#a17b41]">{{ $card['tag'] }}</small>
                        <h3 class="my-2.5 font-serif text-[26px] font-medium">{{ $card['name'] }}</h3>
                        <p class="text-[13px] leading-[1.8] text-[#777a73]">{{ $card['desc'] }}</p>
                        <span class="menu-link">View Items &amp; Prices <i class="fas fa-arrow-right"></i></span>
                    </div>
                </button>
                @endforeach
            </div>
            <div class="reveal mt-11 text-center"><a href="{{ route('contact.create') }}" class="gold-btn">Ask About Our Full Menu <i class="fas fa-arrow-right"></i></a></div>
        </div>
    </section>

    {{-- CITY MENUS (tabs) --}}
    <section id="menus" class="bg-cream py-[75px] sm:py-[110px]">
        <div class="mx-auto w-[90%] max-w-[1200px] text-center">
            <div class="reveal mx-auto mb-10 max-w-[650px]">
                <p class="section-label">Browse</p>
                <h2 class="section-title">Royal <span class="text-gold">Menus</span></h2>
            </div>
            <div id="cityTabs" class="reveal mb-6 flex flex-wrap justify-center gap-2">
                @foreach($menuCities as $i => $c)
                <button type="button" data-city="{{ $c }}" class="tab-btn {{ $i === 0 ? 'on' : '' }}">{{ $c }}</button>
                @endforeach
            </div>
            <div id="typeTabs" class="reveal mx-auto mb-8 inline-flex rounded-full border border-gold/40 p-1.5">
                <button type="button" data-type="À la carte" class="tab-btn on !rounded-full">À la carte</button>
                <button type="button" data-type="Buffet Menu" class="tab-btn !rounded-full">Buffet Menu</button>
            </div>
            <h3 id="menuTitle" class="mb-6 font-serif text-2xl text-gold-dark"></h3>
            <div class="relative" data-carousel>
                <button class="rc-arrow left-0" data-dir="-1" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                <div id="menuPages" class="rc-track justify-start sm:justify-center"></div>
                <button class="rc-arrow right-0" data-dir="1" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </section>

    <section id="experience" class="bg-dark text-white">
        <div class="grid min-h-[650px] md:grid-cols-2">
            <div class="min-h-[330px] bg-cover bg-center sm:min-h-[440px] md:min-h-[500px]" style="background-image:url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1300&q=85')" role="img" aria-label="Elegant dining table and food"></div>
            <div class="reveal flex flex-col justify-center px-[5%] py-[70px] sm:px-[8%] md:py-[75px] lg:px-[10%] lg:py-[100px]">
                <p class="section-label">The Royal Experience</p>
                <h2 class="section-title">More Than a Meal. <span class="text-gold">A Memory.</span></h2>
                <p class="section-desc my-6 !text-[#adb7af]">Whether it is a family dinner, an intimate gathering or a celebration with friends, Royal Restaurant welcomes you with a thoughtful dining experience.</p>
                <div class="mb-9 mt-4 grid gap-6 sm:grid-cols-2">
                    @foreach(['Carefully selected flavours','Welcoming atmosphere','Family dining','Special occasions'] as $item)
                    <div class="flex items-center gap-3 text-[13px] text-[#d9dfd9]"><i class="fas fa-check-circle text-base text-gold"></i>{{ $item }}</div>
                    @endforeach
                </div>
                <div><a href="{{ route('reservation.create') }}" class="gold-btn">Plan Your Visit <i class="fas fa-arrow-right"></i></a></div>
            </div>
        </div>
    </section>

    <section id="gallery" class="bg-cream py-[75px] sm:py-[110px]">
        <div class="mx-auto w-[90%] max-w-[1200px]">
            <div class="reveal mx-auto mb-14 max-w-[650px] text-center">
                <p class="section-label">Our Gallery</p>
                <h2 class="section-title">Moments at <span class="text-gold">Royal</span></h2>
                <p class="section-desc mt-5">A glimpse of beautiful dining spaces, food and moments worth remembering.</p>
            </div>
            <div class="grid auto-rows-[240px] gap-4 sm:grid-cols-2 md:grid-cols-[1.35fr_1fr_1fr] md:grid-rows-2">
                @foreach([['photo-1517248135467-4c7edcad34c4','Royal restaurant ambience','Our Ambience'],['photo-1547592180-85f173990554','Fresh prepared food','Fresh Flavours'],['photo-1414235077428-338989a2e8c0','Fine dining presentation','Fine Dining'],['photo-1544025162-d76694265947','Barbecue selection','Signature Food'],['photo-1559339352-11d035aa65de','Restaurant dining','Dining Moments']] as $i => [$img, $alt, $label])
                <div class="reveal group relative overflow-hidden {{ $i === 0 ? 'sm:col-span-2 md:col-span-1 md:row-span-2' : '' }}">
                    <img src="https://images.unsplash.com/{{ $img }}?auto=format&fit=crop&w=1000&q=85" alt="{{ $alt }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.07]">
                    <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/75 to-transparent p-6 text-white"><span class="font-serif text-[23px]">{{ $label }}</span></div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- AMBIENCE CAROUSEL --}}
    <section id="ambience" class="bg-sand py-[75px] sm:py-[110px]">
        <div class="mx-auto w-[90%] max-w-[1200px]">
            <div class="reveal mx-auto mb-12 max-w-[650px] text-center">
                <p class="section-label">Our Space</p>
                <h2 class="section-title">The <span class="text-gold">Ambience</span></h2>
            </div>
            <div class="reveal relative" data-carousel>
                <button class="rc-arrow left-2" data-dir="-1" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                <div class="rc-track">
                    @foreach($ambience as $img)
                    <img src="{{ $u($img) }}" alt="Royal Restaurant ambience" loading="lazy" class="h-[300px] w-[260px] object-cover sm:w-[290px]">
                    @endforeach
                </div>
                <button class="rc-arrow right-2" data-dir="1" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </section>

    {{-- FOOD CAROUSEL --}}
    <section id="food" class="bg-cream py-[75px] sm:py-[110px]">
        <div class="mx-auto w-[90%] max-w-[1200px]">
            <div class="reveal mx-auto mb-12 max-w-[650px] text-center">
                <p class="section-label">Taste</p>
                <h2 class="section-title"><span class="text-gold">Food</span></h2>
            </div>
            <div class="reveal relative" data-carousel>
                <button class="rc-arrow left-2" data-dir="-1" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                <div class="rc-track">
                    @foreach($food as $img)
                    <img src="{{ $u($img) }}" alt="Royal Restaurant food" loading="lazy" class="h-[300px] w-[260px] object-cover sm:w-[290px]">
                    @endforeach
                </div>
                <button class="rc-arrow right-2" data-dir="1" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </section>

    <section class="px-0 py-20 text-center text-white sm:bg-fixed sm:py-[110px]" style="background:linear-gradient(rgba(12,24,17,.78),rgba(12,24,17,.78)),url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=2000&q=85') center/cover;">
        <div class="reveal mx-auto w-[90%] max-w-[1200px]">
            <p class="section-label !text-gold">Your Table Awaits</p>
            <h2 class="mb-5 font-serif text-[clamp(38px,5vw,60px)] font-medium">Make Every Occasion Special.</h2>
            <p class="mx-auto mb-8 max-w-[570px] text-sm leading-[1.8] text-white/80">Gather your favourite people and enjoy an experience created around good food, great company and genuine hospitality.</p>
            <a href="{{ route('reservation.create') }}" class="gold-btn">Book Your Table <i class="fas fa-arrow-right"></i></a>
        </div>
    </section>

    <section class="bg-sand py-[75px] sm:py-[110px]">
        <div class="mx-auto w-[90%] max-w-[1200px]">
            <div class="reveal mx-auto mb-14 max-w-[650px] text-center">
                <p class="section-label">Why Royal Restaurant</p>
                <h2 class="section-title">Hospitality <span class="text-gold">with Heart</span></h2>
                <p class="section-desc mt-5">The details that make every dining experience feel special.</p>
            </div>
            <div class="grid gap-6 md:grid-cols-3">
                @foreach([['fa-heart','Made with Passion','We believe good food starts with care, quality ingredients and attention to detail.'],['fa-users','For Every Gathering','A welcoming place for families, friends, celebrations and meaningful conversations.'],['fa-concierge-bell','Warm Service','Every guest deserves thoughtful attention and a comfortable dining experience.']] as [$icon, $title, $text])
                <div class="reveal border-b-[3px] border-transparent bg-white px-7 py-10 text-center transition duration-300 hover:-translate-y-1 hover:border-gold">
                    <div class="mx-auto mb-6 grid h-[65px] w-[65px] place-items-center border border-gold text-[23px] text-gold-dark"><i class="fas {{ $icon }}"></i></div>
                    <h3 class="mb-3 font-serif text-[23px] font-medium">{{ $title }}</h3>
                    <p class="text-[13px] leading-[1.9] text-[#777a73]">{{ $text }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- LOCATIONS + MAP --}}
    <section id="locations" class="bg-cream py-[75px] sm:py-[110px]">
        <div class="mx-auto w-[90%] max-w-[1200px]">
            <div class="reveal mx-auto mb-12 max-w-[650px] text-center">
                <p class="section-label">Find Us</p>
                <h2 class="section-title">Our <span class="text-gold">Locations</span></h2>
            </div>
            <div class="reveal relative" data-carousel>
                <button class="rc-arrow left-0" data-dir="-1" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                <div class="rc-track py-2">
                    @foreach($locations as $l)
                    <div class="w-[290px] border-b-[3px] border-transparent bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-gold sm:w-[320px]">
                        <h3 class="mb-5 text-center font-serif text-[24px] text-gold-dark">{{ $l['city'] }}</h3>
                        <ul class="space-y-3 text-[13px] text-[#555]">
                            <li class="flex gap-3"><i class="fas fa-location-dot mt-0.5 text-gold-dark"></i>{{ $l['addr'] }}</li>
                            <li class="flex gap-3"><i class="fas fa-phone text-gold-dark"></i><a href="tel:{{ preg_replace('/\s/', '', $l['tel']) }}">{{ $l['tel'] }}</a></li>
                            <li class="flex gap-3"><i class="fab fa-whatsapp text-gold-dark"></i><a target="_blank" rel="noopener" href="https://wa.me/{{ preg_replace('/\D/', '', $l['wa']) }}">{{ $l['wa'] }}</a></li>
                            <li class="flex gap-3"><i class="far fa-clock text-gold-dark"></i>{{ $l['hrs'] }}</li>
                        </ul>
                        <button type="button" data-fly="{{ $l['lat'] }},{{ $l['lng'] }}" class="menu-link">View on map <i class="fas fa-arrow-right"></i></button>
                    </div>
                    @endforeach
                </div>
                <button class="rc-arrow right-0" data-dir="1" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
            </div>
            <div class="reveal mt-10 overflow-hidden rounded-xl border border-gold/30"><div id="royalMap"></div></div>
        </div>
    </section>

    {{-- FEEDBACK --}}
    <section id="feedback" class="bg-sand py-[75px] sm:py-[110px]">
        <div class="mx-auto w-[90%] max-w-[600px]">
            <form method="POST" action="{{ Route::has('feedback.store') ? route('feedback.store') : '#' }}" class="reveal border border-gold/20 bg-white p-7 shadow-sm sm:p-10">
                @csrf
                <h2 class="mb-8 text-center font-serif text-[32px] font-medium text-gold-dark">Share Your Feedback</h2>
                @if(session('success'))<p class="mb-5 border border-gold/40 p-3 text-sm text-gold-dark">{{ session('success') }}</p>@endif
                @foreach([['name','Full Name','text','Enter your name'],['email','Email Address','email','Enter your email'],['phone','Phone Number','tel','Enter your phone number']] as [$n, $lb, $t, $ph])
                <label class="mb-5 block text-sm font-medium">{{ $lb }}
                    <input name="{{ $n }}" type="{{ $t }}" value="{{ old($n) }}" placeholder="{{ $ph }}" {{ $n !== 'phone' ? 'required' : '' }} class="mt-2 w-full border border-[#ded9cd] bg-white px-4 py-3.5 text-sm outline-none transition focus:border-gold">
                    @error($n)<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                </label>
                @endforeach
                <label class="mb-6 block text-sm font-medium">Your Feedback
                    <textarea name="message" rows="5" required placeholder="Tell us about your experience..." class="mt-2 w-full border border-[#ded9cd] bg-white px-4 py-3.5 text-sm outline-none transition focus:border-gold">{{ old('message') }}</textarea>
                    @error('message')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                </label>
                <button type="submit" class="gold-btn w-full">Submit Feedback <i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </section>

    {{-- CONTACT --}}
    <section id="contact" class="bg-dark py-[75px] text-white sm:py-[110px]">
        <div class="mx-auto grid w-[90%] max-w-[1200px] gap-12 md:grid-cols-2 lg:gap-20">
            <div class="reveal">
                <p class="section-label">Get in Touch</p>
                <h2 class="section-title">We Would Love <span class="text-gold">to Welcome You.</span></h2>
                <p class="section-desc my-5 !text-[#aeb5ad]">Planning a visit or have a question? Get in touch with our team for reservations and enquiries.</p>
                @foreach([['fas','fa-calendar-check','Table Reservations','Reserve your preferred table online.'],['fas','fa-envelope','General Enquiries','Send your questions through our contact form.'],['fas','fa-clock','Visiting Hours','Please contact our team for current opening hours.'],['fab','fa-whatsapp','WhatsApp','0300 5371521']] as [$style, $icon, $title, $text])
                <div class="mt-6 flex items-start gap-4">
                    <i class="{{ $style }} {{ $icon }} mt-1 text-xl text-gold"></i>
                    <div>
                        <h4 class="mb-1.5 text-[13px]">{{ $title }}</h4>
                        <p class="text-[13px] leading-[1.8] text-[#aeb5ad]">{{ $text }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="reveal border border-gold/20 bg-[#1a2a22] p-7 sm:p-10">
                <h3 class="mb-4 font-serif text-[30px] font-medium">Plan Your Visit</h3>
                <p class="mb-6 text-sm leading-[1.9] text-[#aeb5ad]">We look forward to welcoming you. Make a reservation or send us a message and our team will assist you.</p>
                <div class="grid gap-4">
                    <a href="{{ route('reservation.create') }}" class="gold-btn"><i class="fas fa-calendar-alt"></i> Reserve a Table</a>
                    <a href="https://wa.me/923005371521?text=Assalam%20o%20Alaikum%2C%20I%20would%20like%20to%20make%20a%20reservation." target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-3 border border-[#25D366] bg-[#25D366] px-6 py-4 text-xs font-bold uppercase tracking-[1.5px] text-white transition duration-300 hover:-translate-y-0.5 hover:bg-transparent hover:text-[#25D366]"><i class="fab fa-whatsapp text-base"></i> Chat on WhatsApp</a>
                    <a href="{{ route('contact.create') }}" class="outline-btn"><i class="fas fa-paper-plane"></i> Contact Our Team</a>
                    @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn outline-btn w-full hover:!border-red-400 hover:!bg-red-500 hover:!text-white"><i class="fas fa-right-from-bracket"></i> Logout</button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-[#0c1511] pb-6 pt-16 text-white">
        <div class="mx-auto w-[90%] max-w-[1200px]">
            <div class="grid gap-9 pb-12 sm:grid-cols-2 md:gap-12 lg:grid-cols-[1.5fr_1fr_1fr_1fr]">
                <div>
                    <a href="{{ route('home') }}" class="flex items-center gap-3 text-white">
                        <div class="grid h-[45px] w-[45px] place-items-center border border-gold font-serif text-[28px] text-gold"> R</div>
                        <div>
                            <div class="font-serif text-[21px] tracking-[2px]">ROYAL</div>
                            <small class="mt-1 block text-[8px] uppercase tracking-[3px] text-gold">Restaurant</small>
                        </div>
                    </a>
                    <p class="my-5 max-w-[300px] text-[13px] leading-[1.9] text-[#aeb5ad]">Bringing together beautiful flavours, warm hospitality and memorable dining moments.</p>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/profile.php?id=61590594772205" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="grid h-[42px] w-[42px] place-items-center rounded-full border border-gold/40 text-gold transition duration-300 hover:-translate-y-1 hover:border-[#1877F2] hover:bg-[#1877F2] hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://wa.me/923005371521" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp" class="grid h-[42px] w-[42px] place-items-center rounded-full border border-gold/40 text-lg text-gold transition duration-300 hover:-translate-y-1 hover:border-[#25D366] hover:bg-[#25D366] hover:text-white"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="mb-6 font-serif text-xl font-medium text-gold">Explore</h4>
                    <ul class="space-y-4">
                        <li><a class="footer-link" href="#home">Home</a></li>
                        <li><a class="footer-link" href="#about">Our Story</a></li>
                        <li><a class="footer-link" href="#menu">Our Menu</a></li>
                        <li><a class="footer-link" href="#experience">Experience</a></li>
                        <li><a class="footer-link" href="#gallery">Gallery</a></li>
                        <li><a class="footer-link" href="#locations">Locations</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-6 font-serif text-xl font-medium text-gold">Quick Links</h4>
                    <ul class="space-y-4">
                        <li><a class="footer-link" href="{{ route('reservation.create') }}">Reservations</a></li>
                        <li><a class="footer-link" href="{{ route('contact.create') }}">Contact Us</a></li>
                        <li><a class="footer-link" href="#feedback">Feedback</a></li>
                        @auth
                        <li><a class="footer-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="logout-btn footer-link hover:!text-red-400"><i class="fas fa-right-from-bracket mr-1"></i> Logout</button>
                            </form>
                        </li>
                        @else
                        <li><a class="footer-link" href="{{ route('login') }}">Login</a></li>
                        @if(Route::has('register'))
                        <li><a class="footer-link" href="{{ route('register') }}">Register</a></li>
                        @endif
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="mb-6 font-serif text-xl font-medium text-gold">Visit Us</h4>
                    <ul class="space-y-4">
                        <li><a class="footer-link" href="#contact">Reservations &amp; Enquiries</a></li>
                        <li><a class="footer-link" href="#contact">Opening Hours</a></li>
                        <li><a class="footer-link" href="{{ route('contact.create') }}">Send a Message</a></li>
                        <li><a class="footer-link" href="{{ route('reservation.create') }}">Book a Table</a></li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col gap-5 border-t border-white/10 pt-6 text-center text-xs text-[#89948c] sm:flex-row sm:justify-between sm:text-left">
                <p>&copy; <span id="year"></span> Royal Restaurant. All Rights Reserved.</p>
                <p>Crafted with care for a royal dining experience.</p>
            </div>
        </div>
    </footer>

    {{-- MENU POPUP --}}
    <div id="menuModal" class="fixed inset-0 z-[100000] hidden items-center justify-center bg-black/70 p-4" role="dialog" aria-modal="true" aria-labelledby="mmTitle">
        <div class="max-h-[88vh] w-full max-w-[560px] overflow-y-auto bg-white shadow-2xl">
            <div class="sticky top-0 flex items-center justify-between border-b border-gold/30 bg-dark px-6 py-4 text-white">
                <h3 id="mmTitle" class="font-serif text-2xl text-gold-light"></h3>
                <button type="button" id="mmClose" class="text-2xl leading-none hover:text-gold" aria-label="Close">&times;</button>
            </div>
            <div id="mmItems" class="divide-y divide-[#ece7da] px-6"></div>
            <div class="border-t border-gold/30 bg-sand px-6 py-5">
                <div class="mb-4 flex items-center justify-between text-sm font-bold">
                    <span>Total</span><span id="mmTotal" class="font-serif text-xl text-gold-dark">Rs. 0</span>
                </div>
                <a id="mmOrder" href="#" target="_blank" rel="noopener noreferrer" class="gold-btn w-full"><i class="fab fa-whatsapp"></i> Order on WhatsApp</a>
            </div>
        </div>
    </div>

    <a href="https://wa.me/923005371521?text=Assalam%20o%20Alaikum%2C%20I%20would%20like%20to%20make%20a%20reservation." target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp" class="fixed bottom-[78px] right-[14px] z-[99999] overflow-hidden rounded-full sm:bottom-[86px] sm:right-[22px]">
        <span class="absolute inset-0 animate-ping rounded-full bg-[#25D366] opacity-40"></span>
        <span class="relative grid h-12 w-12 place-items-center rounded-full bg-[#25D366] text-[26px] text-white shadow-[0_8px_25px_rgba(37,211,102,.45)] transition duration-300 hover:scale-110 sm:h-[54px] sm:w-[54px]"><i class="fab fa-whatsapp"></i></span>
    </a>
    <audio id="royalMusic" loop preload="auto"><source src="{{ asset('videos/royal-restaurant-music.mp3') }}" type="audio/mpeg"></audio>
    <button id="musicToggle" type="button" class="music-player-button" aria-label="Play background music" title="Play background music">
        <i id="musicIcon" class="fas fa-music"></i><span id="musicText">Play Music</span>
    </button>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
    const navbar = document.getElementById('navbar');
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');

    function onScroll() {
        const scrolled = window.scrollY > 40;
        navbar.classList.toggle('bg-[#0f1814]/90', scrolled);
        navbar.classList.toggle('bg-[#0f1814]/30', !scrolled);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    menuToggle.addEventListener('click', () => {
        const open = navLinks.classList.toggle('hidden') === false;
        navLinks.classList.toggle('flex', open);
        menuToggle.setAttribute('aria-expanded', open);
    });
    navLinks.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 768) {
                navLinks.classList.add('hidden');
                navLinks.classList.remove('flex');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        });
    });

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) { entry.target.classList.add('visible'); revealObserver.unobserve(entry.target); }
        });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

    const menuCards = document.querySelectorAll('#menuGrid .menu-card');
    if (menuCards.length) {
        let current = 0;
        setInterval(() => {
            menuCards.forEach(card => card.classList.remove('is-active'));
            menuCards[current].classList.add('is-active');
            current = (current + 1) % menuCards.length;
        }, 2500);
    }

    document.getElementById('year').textContent = new Date().getFullYear();

    const heroVideo = document.getElementById('royalHeroVideo');
    if (heroVideo) heroVideo.play().catch(() => {});

    const music = document.getElementById('royalMusic');
    const musicToggle = document.getElementById('musicToggle');
    const musicText = document.getElementById('musicText');
    const musicIcon = document.getElementById('musicIcon');
    musicToggle.addEventListener('click', () => {
        if (music.paused) {
            music.play().then(() => {
                musicToggle.classList.add('playing');
                musicText.textContent = 'Pause Music';
                musicIcon.className = 'fas fa-pause';
            }).catch(() => {});
        } else {
            music.pause();
            musicToggle.classList.remove('playing');
            musicText.textContent = 'Play Music';
            musicIcon.className = 'fas fa-music';
        }
    });

    // ---- Carousels ----
    document.querySelectorAll('[data-carousel]').forEach(box => {
        const track = box.querySelector('.rc-track');
        box.querySelectorAll('[data-dir]').forEach(b => b.addEventListener('click', () => {
            track.scrollBy({ left: b.dataset.dir * Math.max(track.clientWidth * 0.8, 280), behavior: 'smooth' });
        }));
    });

    // ---- City menus ----
    // Apni menu images yahan daalein: 'Islamabad|À la carte': ['/images/menus/isb-1.jpg', ...]
    const menuImages = {};
    const pageCount = 6;
    const cartePics = ['photo-1565557623262-b51c2513a641','photo-1544025162-d76694265947','photo-1555939594-58d7cb561ad1','photo-1476224203421-9ac39bcb3327','photo-1567620905732-2d1ec7ab7445','photo-1504674900247-0877df9cc836'];
    const buffetPics = ['photo-1555244162-803834f70033','photo-1547592180-85f173990554','photo-1414235077428-338989a2e8c0','photo-1540189549336-e6e99c3679fe','photo-1482049016688-2d3e1b311543','photo-1559339352-11d035aa65de'];
    let city = 'Islamabad', type = 'À la carte';
    const pagesEl = document.getElementById('menuPages');
    function renderMenu() {
        document.getElementById('menuTitle').textContent = type;
        const imgs = menuImages[city + '|' + type] || [];
        pagesEl.innerHTML = '';
        for (let i = 0; i < pageCount; i++) {
            const d = document.createElement('div');
            const pool = type === 'Buffet Menu' ? buffetPics : cartePics;
            const src = imgs[i] || `https://images.unsplash.com/${pool[(i + city.length) % pool.length]}?auto=format&fit=crop&w=600&h=860&q=80`;
            d.className = 'group relative h-[400px] w-[280px] overflow-hidden bg-[#e7e5e0] shadow-md sm:h-[430px] sm:w-[300px]';
            d.innerHTML = `<img src="${src}" alt="${city} ${type} page ${i + 1}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-110">
                <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/80 via-black/20 to-transparent p-5 text-left text-white">
                    <div class="font-serif text-2xl text-gold-light">${city}</div>
                    <div class="mt-1 text-[10px] uppercase tracking-[2px] text-white/80">${type} · Page ${i + 1}</div>
                </div>`;
            pagesEl.appendChild(d);
        }
        pagesEl.scrollTo({ left: 0 });
    }
    function bindTabs(id, attr, set) {
        const wrap = document.getElementById(id);
        wrap.querySelectorAll('button').forEach(b => b.addEventListener('click', () => {
            wrap.querySelectorAll('button').forEach(x => x.classList.remove('on'));
            b.classList.add('on'); set(b.dataset[attr]); renderMenu();
        }));
    }
    bindTabs('cityTabs', 'city', v => city = v);
    bindTabs('typeTabs', 'type', v => type = v);
    renderMenu();

    // ---- Map ----
    const places = {!! json_encode($mapPlaces) !!};
    const map = L.map('royalMap', { scrollWheelZoom: false }).setView([32.9, 73.0], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);
    places.forEach(p => L.marker([p.lat, p.lng]).addTo(map).bindPopup('<b>' + p.city + '</b><br>' + (p.addr || '')));
    document.querySelectorAll('[data-fly]').forEach(b => b.addEventListener('click', () => {
        const [la, ln] = b.dataset.fly.split(',').map(Number);
        map.flyTo([la, ln], 13);
        document.getElementById('royalMap').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }));

    // ---- Menu popup (items, size dropdown, price, WhatsApp order) ----
    const menuCardsData = {!! json_encode($cards->values()) !!};
    const WA_NUMBER = '923005371521';
    const modal = document.getElementById('menuModal');
    const mmItems = document.getElementById('mmItems');
    const mmTotal = document.getElementById('mmTotal');
    const mmOrder = document.getElementById('mmOrder');
    const rs = n => 'Rs. ' + Number(n).toLocaleString('en-PK');
    let currentCat = null;

    function recalc() {
        let total = 0, lines = [];
        mmItems.querySelectorAll('[data-row]').forEach(row => {
            const qty = parseInt(row.querySelector('.qty').value, 10) || 0;
            const sel = row.querySelector('select');
            const price = Number(sel.value);
            const size = sel.options[sel.selectedIndex].dataset.label;
            row.querySelector('.line-price').textContent = rs(price);
            if (qty > 0) {
                total += price * qty;
                lines.push(`${qty} x ${row.dataset.name} (${size}) = ${rs(price * qty)}`);
            }
        });
        mmTotal.textContent = rs(total);
        const msg = lines.length
            ? `Assalam o Alaikum, I would like to order from ${currentCat.name}:\n${lines.join('\n')}\nTotal: ${rs(total)}`
            : `Assalam o Alaikum, I would like to know about ${currentCat.name}.`;
        mmOrder.href = `https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(msg)}`;
    }

    function openMenu(i) {
        currentCat = menuCardsData[i];
        document.getElementById('mmTitle').textContent = currentCat.name;
        mmItems.innerHTML = '';
        if (!currentCat.items.length) {
            mmItems.innerHTML = '<p class="py-8 text-center text-sm text-[#777a73]">Is category ke items jald add kiye jayenge.</p>';
        }
        currentCat.items.forEach(item => {
            const row = document.createElement('div');
            row.dataset.row = '1';
            row.dataset.name = item.name;
            row.className = 'flex flex-wrap items-center gap-3 py-4';
            const opts = Object.entries(item.prices)
                .map(([label, p]) => `<option value="${p}" data-label="${label}">${label} - ${rs(p)}</option>`).join('');
            row.innerHTML = `
                <div class="min-w-[140px] flex-1">
                    <div class="font-serif text-lg">${item.name}</div>
                    <div class="line-price text-sm font-bold text-gold-dark"></div>
                </div>
                <select class="border border-[#ded9cd] bg-white px-3 py-2 text-sm outline-none focus:border-gold">${opts}</select>
                <input type="number" min="0" max="50" value="0" class="qty w-16 border border-[#ded9cd] px-2 py-2 text-center text-sm outline-none focus:border-gold" aria-label="Quantity">`;
            row.querySelector('select').addEventListener('change', recalc);
            row.querySelector('.qty').addEventListener('input', recalc);
            mmItems.appendChild(row);
        });
        recalc();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    function closeMenu() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
    document.querySelectorAll('[data-open-menu]').forEach(b => b.addEventListener('click', () => openMenu(+b.dataset.openMenu)));
    document.getElementById('mmClose').addEventListener('click', closeMenu);
    modal.addEventListener('click', e => { if (e.target === modal) closeMenu(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMenu(); });
</script>
</body>
</html>