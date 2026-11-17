@extends('layouts.admin')

@section('title', 'Category Details')
@section('page-heading', 'Category Details')

@section('content')
<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">Management</p>
            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl" style="font-family:'Playfair Display',serif;">Category Details</h1>
            <p class="muted mt-1 text-xs">Complete category information.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.categories.edit', $category) }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs"
               style="color:#111c18;background:linear-gradient(135deg,#efd39a,#d6ad69 58%,#a17b41);">
                <i class="fas fa-pen"></i> Edit Category
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs border"
               style="border-color:var(--border);">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b pb-4" style="border-color:var(--border);">
            <div class="flex items-center gap-2.5">
                @if($category->image)
                    <img src="{{ asset('storage/'.$category->image) }}"
                         alt="{{ $category->name }}"
                         class="h-9 w-9 shrink-0 rounded-lg object-cover">
                @else
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-sm text-gold"
                         style="background:linear-gradient(145deg,#2c4236,#111c18);">
                        <i class="fas fa-utensils"></i>
                    </div>
                @endif
                <div>
                    <h2 class="text-base font-bold leading-tight">Category Information</h2>
                    <p class="muted text-[11px]">Category #{{ $category->id }}</p>
                </div>
            </div>

            @if($category->is_active)
                <span class="rounded-full bg-green-500/10 px-2.5 py-1 text-[10px] font-bold text-green-600">
                    <i class="fas fa-circle mr-1 text-[5px]"></i> Active
                </span>
            @else
                <span class="rounded-full bg-red-500/10 px-2.5 py-1 text-[10px] font-bold text-red-500">
                    <i class="fas fa-circle mr-1 text-[5px]"></i> Inactive
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Category Name</p>
                <p class="text-[13px] font-semibold">{{ $category->name ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Slug</p>
                <p class="break-all text-[13px] font-semibold">{{ $category->slug ?: 'No slug' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Status</p>
                <p class="text-[13px] font-semibold">{{ $category->is_active ? 'Active' : 'Inactive' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Created At</p>
                <p class="text-[13px] font-semibold">{{ $category->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5 sm:col-span-2">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Last Updated</p>
                <p class="text-[13px] font-semibold">{{ $category->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5 sm:col-span-2">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Description</p>
                <p class="whitespace-pre-line text-[13px] leading-6">{{ $category->description ?: 'No description available.' }}</p>
            </div>
        </div>

    </section>
</div>
@endsection