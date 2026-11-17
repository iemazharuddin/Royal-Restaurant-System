@extends('layouts.admin')

@section('title', 'Menu Item Details')
@section('page-heading', 'Menu Item Details')

@section('content')
<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">Management</p>
            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl" style="font-family:'Playfair Display',serif;">Menu Item Details</h1>
            <p class="muted mt-1 text-xs">View restaurant menu item information.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.menu-items.edit', $menuItem) }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs"
               style="color:#111c18;background:linear-gradient(135deg,#efd39a,#d6ad69 58%,#a17b41);">
                <i class="fas fa-pen-to-square"></i> Edit Menu Item
            </a>
            <a href="{{ route('admin.menu-items.index') }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs border"
               style="border-color:var(--border);">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b pb-4" style="border-color:var(--border);">
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
                    <h2 class="break-words text-base font-bold leading-tight">{{ $menuItem->name }}</h2>
                    <p class="muted text-[11px]">
                        {{ $menuItem->category->name ?? 'Uncategorized' }} &middot; Menu Item #{{ $menuItem->id }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if($menuItem->is_available)
                    <span class="rounded-full bg-green-500/10 px-2.5 py-1 text-[10px] font-bold text-green-600">
                        <i class="fas fa-check-circle mr-1"></i> Available
                    </span>
                @else
                    <span class="rounded-full bg-red-500/10 px-2.5 py-1 text-[10px] font-bold text-red-500">
                        <i class="fas fa-ban mr-1"></i> Unavailable
                    </span>
                @endif

                @if($menuItem->is_featured)
                    <span class="rounded-full bg-yellow-500/10 px-2.5 py-1 text-[10px] font-bold text-yellow-600">
                        <i class="fas fa-star mr-1"></i> Featured
                    </span>
                @endif
            </div>
        </div>

        @if($menuItem->image)
            <img src="{{ asset('storage/'.$menuItem->image) }}"
                 alt="{{ $menuItem->name }}"
                 class="mb-3 h-44 w-full rounded-xl object-cover sm:h-52">
        @endif

        <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Menu Item Name</p>
                <p class="text-[13px] font-semibold">{{ $menuItem->name }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Category</p>
                <p class="text-[13px] font-semibold">{{ $menuItem->category->name ?? 'Uncategorized' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Price</p>
                <p class="gold text-[13px] font-bold">Rs. {{ number_format($menuItem->price, 2) }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Sort Order</p>
                <p class="text-[13px] font-semibold">{{ $menuItem->sort_order ?? 0 }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Featured Item</p>
                <p class="text-[13px] font-semibold">{{ $menuItem->is_featured ? 'Yes' : 'No' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Menu Item ID</p>
                <p class="text-[13px] font-semibold">#{{ $menuItem->id }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Created At</p>
                <p class="text-[13px] font-semibold">{{ $menuItem->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Last Updated</p>
                <p class="text-[13px] font-semibold">{{ $menuItem->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5 sm:col-span-2">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Description</p>
                <p class="whitespace-pre-line text-[13px] leading-6">{{ $menuItem->description ?: 'No description available.' }}</p>
            </div>
        </div>

        <div class="mt-5 flex justify-end border-t pt-4" style="border-color:var(--border);">
            <form action="{{ route('admin.menu-items.destroy', $menuItem) }}"
                  method="POST"
                  data-confirm-delete="{{ $menuItem->name }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-danger-btn rounded-xl !px-3.5 !py-2 text-xs font-semibold">
                    <i class="fas fa-trash mr-1.5"></i> Delete Menu Item
                </button>
            </form>
        </div>

    </section>
</div>
@endsection