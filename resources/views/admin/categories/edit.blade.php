@extends('layouts.admin')

@section('title', 'Edit Category')
@section('page-heading', 'Edit Category')

@section('content')
@php
    $fieldClass = 'w-full rounded-xl border px-3 py-2.5 text-[13px] outline-none focus:border-yellow-500';
    $fieldStyle = 'background:var(--bg);border-color:var(--border);color:var(--text);';
@endphp

<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">Management</p>
            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl" style="font-family:'Playfair Display',serif;">Edit Category</h1>
            <p class="muted mt-1 text-xs">Update your restaurant category information.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.categories.show', $category) }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs border"
               style="border-color:var(--border);">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs border"
               style="border-color:var(--border);">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-300 bg-red-50 p-3 text-xs text-red-700">
            <strong>Please fix the following errors:</strong>
            <ul class="mt-1.5 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        <div class="mb-4 flex items-center gap-2.5 border-b pb-4" style="border-color:var(--border);">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-sm text-gold"
                 style="background:linear-gradient(145deg,#2c4236,#111c18);">
                <i class="fas fa-pen-to-square"></i>
            </div>
            <div class="min-w-0">
                <h2 class="text-base font-bold leading-tight">Update Category</h2>
                <p class="muted truncate text-[11px]">Editing: {{ $category->name }}</p>
            </div>
        </div>

        <form action="{{ route('admin.categories.update', $category) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                <div>
                    <label class="muted mb-1 block text-[10px] font-medium uppercase tracking-wide">Category Name *</label>
                    <input type="text" name="name"
                           value="{{ old('name', $category->name) }}" required
                           class="{{ $fieldClass }}" style="{{ $fieldStyle }}">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="muted mb-1 block text-[10px] font-medium uppercase tracking-wide">Slug</label>
                    <input type="text" name="slug"
                           value="{{ old('slug', $category->slug) }}"
                           class="{{ $fieldClass }}" style="{{ $fieldStyle }}">
                    @error('slug') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="muted mb-1 block text-[10px] font-medium uppercase tracking-wide">Replace Category Image</label>
                    <div class="flex items-center gap-3">
                        @if($category->image)
                            <img src="{{ asset('storage/'.$category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="h-11 w-11 shrink-0 rounded-lg object-cover">
                        @endif
                        <input type="file" name="image" accept="image/*"
                               class="{{ $fieldClass }} min-w-0" style="{{ $fieldStyle }}">
                    </div>
                    @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="muted mb-1 block text-[10px] font-medium uppercase tracking-wide">Sort Order</label>
                    <input type="number" name="sort_order" min="0"
                           value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                           class="{{ $fieldClass }}" style="{{ $fieldStyle }}">
                    @error('sort_order') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="muted mb-1 block text-[10px] font-medium uppercase tracking-wide">Description</label>
                    <textarea name="description" rows="4"
                              class="{{ $fieldClass }} leading-6"
                              style="{{ $fieldStyle }}">{{ old('description', $category->description) }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="admin-info-item !rounded-xl !px-3 !py-2.5 sm:col-span-2">
                    <label class="flex cursor-pointer items-center gap-2.5">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 accent-yellow-500">
                        <span class="text-[13px] font-semibold">Active Category</span>
                    </label>
                </div>

            </div>

            <div class="mt-5 flex flex-wrap gap-2 border-t pt-4" style="border-color:var(--border);">
                <button type="submit"
                        class="admin-detail-action !px-3.5 !py-2 !text-xs"
                        style="color:#111c18;background:linear-gradient(135deg,#efd39a,#d6ad69 58%,#a17b41);">
                    <i class="fas fa-save"></i> Update Category
                </button>

                <a href="{{ route('admin.categories.index') }}"
                   class="admin-detail-action !px-3.5 !py-2 !text-xs border"
                   style="border-color:var(--border);">
                    Cancel
                </a>
            </div>

        </form>
    </section>
</div>
@endsection