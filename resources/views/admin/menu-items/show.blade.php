
@extends('layouts.admin')

@section('title', 'Menu Item Details')
@section('page-heading', 'Menu Item Details')

@push('styles')
<style>
    /* Gold + White Buttons */
    .menu-gold-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 17px;
        border: 1px solid #c69b55;
        border-radius: 11px;
        color: #17251d;
        background: linear-gradient(135deg,#f4dfb5 0%,#d6ad69 55%,#b68b4c 100%);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .2px;
        box-shadow: 0 5px 13px rgba(166,126,61,.20);
        transition: all .25s ease;
    }

    .menu-gold-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 9px 19px rgba(166,126,61,.30);
        filter: brightness(1.04);
    }

    .menu-gold-btn:active {
        transform: translateY(0) scale(.98);
    }

    .menu-white-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 17px;
        border: 1px solid var(--border);
        border-radius: 11px;
        color: var(--text);
        background: var(--surface);
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 3px 9px rgba(0,0,0,.04);
        transition: all .25s ease;
    }

    .menu-white-btn:hover {
        transform: translateY(-2px);
        border-color: #d6ad69;
        color: #b68b4c;
        box-shadow: 0 7px 15px rgba(166,126,61,.12);
    }

    .menu-white-btn:active {
        transform: translateY(0) scale(.98);
    }

    .menu-danger-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 17px;
        border: 1px solid rgba(220,38,38,.25);
        border-radius: 11px;
        color: #dc2626;
        background: rgba(239,68,68,.07);
        font-size: 12px;
        font-weight: 800;
        box-shadow: 0 3px 9px rgba(220,38,38,.06);
        transition: all .25s ease;
        cursor: pointer;
    }

    .menu-danger-btn:hover {
        transform: translateY(-2px);
        color: #fff;
        background: linear-gradient(135deg,#ef7777,#dc2626);
        border-color: #dc2626;
        box-shadow: 0 8px 17px rgba(220,38,38,.20);
    }

    .menu-danger-btn:active {
        transform: translateY(0) scale(.98);
    }

    .menu-gold-btn:focus-visible,
    .menu-white-btn:focus-visible,
    .menu-danger-btn:focus-visible {
        outline: 3px solid rgba(214,173,105,.35);
        outline-offset: 3px;
    }

    .menu-image {
        transition: transform .3s ease;
    }

    .menu-image:hover {
        transform: scale(1.01);
    }

    @media(max-width:480px) {
        .menu-show-actions {
            width: 100%;
        }

        .menu-show-actions a {
            flex: 1;
        }

        .menu-delete-form,
        .menu-danger-btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')

<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    {{-- PAGE HEADING --}}
    <div class="flex flex-wrap items-center justify-between gap-3">

        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">
                Management
            </p>

            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl"
                style="font-family:'Playfair Display',serif;">
                Menu Item Details
            </h1>

            <p class="muted mt-1 text-xs">
                View restaurant menu item information.
            </p>
        </div>

        {{-- TOP ACTION BUTTONS --}}
        <div class="menu-show-actions flex flex-wrap gap-2">

            <a href="{{ route('admin.menu-items.edit', $menuItem) }}"
               class="menu-gold-btn">
                <i class="fas fa-pen-to-square"></i>
                Edit Menu Item
            </a>

            <a href="{{ route('admin.menu-items.index') }}"
               class="menu-white-btn">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="rounded-xl bg-green-500/10 p-3 text-xs text-green-600">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- DETAILS CARD --}}
    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        {{-- CARD HEADER --}}
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b pb-4"
             style="border-color:var(--border);">

            <div class="flex min-w-0 items-center gap-2.5">

                @if($menuItem->image)
                    <img src="{{ asset('storage/'.$menuItem->image) }}"
                         alt="{{ $menuItem->name }}"
                         class="h-11 w-11 shrink-0 rounded-lg object-cover">
                @else
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg text-sm text-gold"
                         style="background:linear-gradient(145deg,#2c4236,#111c18);">
                        <i class="fas fa-utensils"></i>
                    </div>
                @endif

                <div class="min-w-0">
                    <h2 class="break-words text-base font-bold leading-tight">
                        {{ $menuItem->name }}
                    </h2>

                    <p class="muted text-[11px]">
                        {{ $menuItem->category->name ?? 'Uncategorized' }}
                        &middot; Menu Item #{{ $menuItem->id }}
                    </p>
                </div>

            </div>

            {{-- STATUS BADGES --}}
            <div class="flex flex-wrap items-center gap-2">

                @if($menuItem->is_available)
                    <span class="rounded-full bg-green-500/10 px-2.5 py-1 text-[10px] font-bold text-green-600">
                        <i class="fas fa-check-circle mr-1"></i>
                        Available
                    </span>
                @else
                    <span class="rounded-full bg-red-500/10 px-2.5 py-1 text-[10px] font-bold text-red-500">
                        <i class="fas fa-ban mr-1"></i>
                        Unavailable
                    </span>
                @endif

                @if($menuItem->is_featured)
                    <span class="rounded-full bg-yellow-500/10 px-2.5 py-1 text-[10px] font-bold text-yellow-600">
                        <i class="fas fa-star mr-1"></i>
                        Featured
                    </span>
                @endif

            </div>
        </div>

        {{-- MENU IMAGE --}}
        @if($menuItem->image)
            <img src="{{ asset('storage/'.$menuItem->image) }}"
                 alt="{{ $menuItem->name }}"
                 class="menu-image mb-4 h-44 w-full rounded-xl object-cover sm:h-52">
        @endif

        {{-- INFORMATION GRID --}}
        <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Menu Item Name
                </p>
                <p class="text-[13px] font-semibold">
                    {{ $menuItem->name }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Category
                </p>
                <p class="text-[13px] font-semibold">
                    {{ $menuItem->category->name ?? 'Uncategorized' }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Price
                </p>
                <p class="gold text-[13px] font-bold">
                    Rs. {{ number_format($menuItem->price, 2) }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Sort Order
                </p>
                <p class="text-[13px] font-semibold">
                    {{ $menuItem->sort_order ?? 0 }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Featured Item
                </p>
                <p class="text-[13px] font-semibold">
                    {{ $menuItem->is_featured ? 'Yes' : 'No' }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Menu Item ID
                </p>
                <p class="text-[13px] font-semibold">
                    #{{ $menuItem->id }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Created At
                </p>
                <p class="text-[13px] font-semibold">
                    {{ $menuItem->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Last Updated
                </p>
                <p class="text-[13px] font-semibold">
                    {{ $menuItem->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}
                </p>
            </div>

            <div class="admin-info-item !rounded-xl !px-3 !py-2.5 sm:col-span-2">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">
                    Description
                </p>
                <p class="whitespace-pre-line text-[13px] leading-6">
                    {{ $menuItem->description ?: 'No description available.' }}
                </p>
            </div>

        </div>

        {{-- DELETE BUTTON --}}
        <div class="mt-5 flex justify-end border-t pt-4"
             style="border-color:var(--border);">

            <form action="{{ route('admin.menu-items.destroy', $menuItem) }}"
                  method="POST"
                  class="menu-delete-form"
                  data-confirm-delete="{{ $menuItem->name }}">

                @csrf
                @method('DELETE')

                <button type="submit" class="menu-danger-btn">
                    <i class="fas fa-trash"></i>
                    Delete Menu Item
                </button>

            </form>

        </div>

    </section>
</div>

@endsection
