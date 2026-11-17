
@extends('layouts.admin')

@section('title', 'Category Details')
@section('page-heading', 'Category Details')

@push('styles')
<style>
    .gold-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border: 1px solid #c69b55;
        border-radius: 11px;
        color: #17251d;
        background: linear-gradient(135deg,#f4dfb5 0%,#d6ad69 55%,#b68b4c 100%);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .2px;
        box-shadow: 0 5px 13px rgba(166,126,61,.20);
        transition: all .25s ease;
        cursor: pointer;
    }

    .gold-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 9px 19px rgba(166,126,61,.30);
        filter: brightness(1.04);
    }

    .gold-btn:active {
        transform: translateY(0) scale(.98);
    }

    .white-gold-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border: 1px solid var(--border);
        border-radius: 11px;
        color: var(--text);
        background: var(--surface);
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 3px 9px rgba(0,0,0,.04);
        transition: all .25s ease;
        cursor: pointer;
    }

    .white-gold-btn:hover {
        transform: translateY(-2px);
        border-color: #d6ad69;
        color: #b68b4c;
        box-shadow: 0 7px 15px rgba(166,126,61,.12);
    }

    .white-gold-btn:active {
        transform: translateY(0) scale(.98);
    }

    .gold-btn:focus-visible,
    .white-gold-btn:focus-visible {
        outline: 3px solid rgba(214,173,105,.35);
        outline-offset: 3px;
    }

    @media(max-width:480px) {
        .category-detail-actions {
            width: 100%;
        }

        .category-detail-actions a {
            flex: 1;
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
                Category Details
            </h1>

            <p class="muted mt-1 text-xs">
                Complete category information.
            </p>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="category-detail-actions flex flex-wrap gap-2">

            <a href="{{ route('admin.categories.edit', $category) }}"
               class="gold-btn">
                <i class="fas fa-pen"></i>
                Edit Category
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="white-gold-btn">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

        </div>

    </div>

    {{-- CATEGORY DETAILS CARD --}}
    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        {{-- CARD HEADER --}}
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b pb-4"
             style="border-color:var(--border);">

            <div class="flex min-w-0 items-center gap-3">

                @if($category->image)
                    <img src="{{ asset('storage/'.$category->image) }}"
                         alt="{{ $category->name }}"
                         class="h-11 w-11 shrink-0 rounded-xl object-cover">
                @else
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-sm"
                         style="color:#e6c27e;background:linear-gradient(145deg,#2c4236,#111c18);">
                        <i class="fas fa-utensils"></i>
                    </div>
                @endif

                <div class="min-w-0">
                    <h2 class="text-base font-bold leading-tight">
                        Category Information
                    </h2>

                    <p class="muted text-[11px]">
                        Category #{{ $category->id }}
                    </p>
                </div>

            </div>

            {{-- STATUS --}}
            @if($category->is_active)
                <span class="shrink-0 rounded-full bg-green-500/10 px-3 py-1.5 text-[10px] font-bold text-green-600">
                    <i class="fas fa-circle mr-1 text-[5px]"></i>
                    Active
                </span>
            @else
                <span class="shrink-0 rounded-full bg-red-500/10 px-3 py-1.5 text-[10px] font-bold text-red-500">
                    <i class="fas fa-circle mr-1 text-[5px]"></i>
                    Inactive
                </span>
            @endif

        </div>

        {{-- INFORMATION GRID --}}
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

            {{-- CATEGORY NAME --}}
            <div class="admin-info-item !rounded-xl !px-3 !py-3">
                <p class="muted mb-1 text-[10px] font-semibold uppercase tracking-wide">
                    Category Name
                </p>

                <p class="text-[13px] font-semibold">
                    {{ $category->name ?? 'N/A' }}
                </p>
            </div>

            {{-- SLUG --}}
            <div class="admin-info-item !rounded-xl !px-3 !py-3">
                <p class="muted mb-1 text-[10px] font-semibold uppercase tracking-wide">
                    Slug
                </p>

                <p class="break-all text-[13px] font-semibold">
                    {{ $category->slug ?: 'No slug' }}
                </p>
            </div>

            {{-- STATUS --}}
            <div class="admin-info-item !rounded-xl !px-3 !py-3">
                <p class="muted mb-1 text-[10px] font-semibold uppercase tracking-wide">
                    Status
                </p>

                <p class="text-[13px] font-semibold">
                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                </p>
            </div>

            {{-- CREATED AT --}}
            <div class="admin-info-item !rounded-xl !px-3 !py-3">
                <p class="muted mb-1 text-[10px] font-semibold uppercase tracking-wide">
                    Created At
                </p>

                <p class="text-[13px] font-semibold">
                    {{ $category->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                </p>
            </div>

            {{-- LAST UPDATED --}}
            <div class="admin-info-item !rounded-xl !px-3 !py-3 sm:col-span-2">
                <p class="muted mb-1 text-[10px] font-semibold uppercase tracking-wide">
                    Last Updated
                </p>

                <p class="text-[13px] font-semibold">
                    {{ $category->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}
                </p>
            </div>

            {{-- DESCRIPTION --}}
            <div class="admin-info-item !rounded-xl !px-3 !py-3 sm:col-span-2">
                <p class="muted mb-1 text-[10px] font-semibold uppercase tracking-wide">
                    Description
                </p>

                <p class="whitespace-pre-line text-[13px] leading-6">
                    {{ $category->description ?: 'No description available.' }}
                </p>
            </div>

        </div>

        {{-- BOTTOM ACTION --}}
        <div class="mt-5 flex flex-wrap gap-2 border-t pt-4"
             style="border-color:var(--border);">

            <a href="{{ route('admin.categories.edit', $category) }}"
               class="gold-btn">
                <i class="fas fa-pen"></i>
                Edit Category
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="white-gold-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Categories
            </a>

        </div>

    </section>

</div>

@endsection
