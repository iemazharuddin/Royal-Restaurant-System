@extends('layouts.admin')

@php
    // Shared compact styling (same as the Reservation form)
    $labelClass   = 'muted mb-1 block text-[10px] font-medium uppercase tracking-wide';
    $controlClass = 'w-full rounded-xl border px-3 py-2.5 text-[13px] outline-none focus:border-yellow-500';
    $controlStyle = 'background:var(--bg);border-color:var(--border);color:var(--text);';
@endphp

@section('title', 'Edit Menu Item')
@section('page-heading', 'Edit Menu Item')

@push('styles')
<style>
    /* Fallback (Firefox / Safari / older browsers) */
    select.admin-select option {
        font-size: 13px;
        padding: 8px 10px;
        background: var(--surface);
        color: var(--text);
    }

    /* Fully styled dropdown (Chrome / Edge) */
    @supports (appearance: base-select) {
        select.admin-select:not([multiple]),
        select.admin-select:not([multiple])::picker(select) {
            appearance: base-select;
        }

        select.admin-select:not([multiple])::picker(select) {
            margin-top: 4px;
            padding: 4px;
            max-height: 240px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--surface);
            box-shadow: 0 14px 34px rgba(9, 18, 14, .18);
        }

        select.admin-select:not([multiple])::picker-icon {
            color: var(--muted);
            transition: rotate .2s ease;
        }
        select.admin-select:not([multiple]):open::picker-icon { rotate: 180deg; }

        select.admin-select:not([multiple]) option {
            padding: 8px 10px;
            border-radius: 8px;
            font-size: 12.5px;
            background: transparent;
            color: var(--text);
            cursor: pointer;
        }
        select.admin-select:not([multiple]) option:hover,
        select.admin-select:not([multiple]) option:focus {
            background: rgba(214, 173, 105, .18);
        }
        select.admin-select:not([multiple]) option:checked {
            background: rgba(214, 173, 105, .28);
            font-weight: 700;
        }
        select.admin-select:not([multiple]) option::checkmark { display: none; }
    }
</style>
@endpush

@section('content')

<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">Management</p>
            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl" style="font-family:'Playfair Display',serif;">Edit Menu Item</h1>
            <p class="muted mt-1 text-xs">Update the existing record information.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.menu-items.show', $menuItem) }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs border"
               style="border-color:var(--border);">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('admin.menu-items.index') }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs border"
               style="border-color:var(--border);">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-green-500/10 p-3 text-xs text-green-600">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-300 bg-red-50 p-3 text-xs text-red-700">
            <strong><i class="fas fa-exclamation-circle mr-1"></i> Please fix the following errors:</strong>
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
                <h2 class="text-base font-bold leading-tight">Update Information</h2>
                <p class="muted truncate text-[11px]">Editing: {{ $menuItem->name }}</p>
            </div>
        </div>

        <form action="{{ route('admin.menu-items.update', $menuItem) }}"
              method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                {{-- Name --}}
                <div class="sm:col-span-2">
                    <label for="name" class="{{ $labelClass }}">Menu Item Name <span class="text-red-500">*</span></label>
                    <input id="name" type="text" name="name" required
                           value="{{ old('name', $menuItem->name) }}"
                           placeholder="Enter menu item name"
                           class="{{ $controlClass }}" style="{{ $controlStyle }}">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $errors->first('name') }}</p> @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id" class="{{ $labelClass }}">Category <span class="text-red-500">*</span></label>
                    <select id="category_id" name="category_id" required
                            class="{{ $controlClass }} admin-select" style="{{ $controlStyle }}">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $menuItem->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-xs text-red-500">{{ $errors->first('category_id') }}</p> @enderror
                </div>

                {{-- Price --}}
                <div>
                    <label for="price" class="{{ $labelClass }}">Price (Rs.) <span class="text-red-500">*</span></label>
                    <input id="price" type="number" name="price" min="0" step="0.01" required
                           value="{{ old('price', $menuItem->price) }}"
                           placeholder="Enter price"
                           class="{{ $controlClass }}" style="{{ $controlStyle }}">
                    @error('price') <p class="mt-1 text-xs text-red-500">{{ $errors->first('price') }}</p> @enderror
                </div>

                {{-- Description --}}
                <div class="sm:col-span-2">
                    <label for="description" class="{{ $labelClass }}">Description</label>
                    <textarea id="description" name="description" rows="4"
                              placeholder="Enter description"
                              class="{{ $controlClass }} leading-6"
                              style="{{ $controlStyle }}">{{ old('description', $menuItem->description) }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-500">{{ $errors->first('description') }}</p> @enderror
                </div>

                {{-- Image --}}
                <div>
                    <label for="image" class="{{ $labelClass }}">Change Menu Item Image</label>
                    <div class="flex items-center gap-3">
                        @if($menuItem->image)
                            <img src="{{ asset('storage/'.$menuItem->image) }}"
                                 alt="{{ $menuItem->name }}"
                                 class="h-11 w-11 shrink-0 rounded-lg object-cover">
                        @endif
                        <input id="image" type="file" name="image" accept="image/*"
                               class="{{ $controlClass }} min-w-0" style="{{ $controlStyle }}">
                    </div>
                    @unless($menuItem->image)
                        <p class="muted mt-1 text-[11px]">No image uploaded.</p>
                    @endunless
                    @error('image') <p class="mt-1 text-xs text-red-500">{{ $errors->first('image') }}</p> @enderror
                </div>

                {{-- Sort Order --}}
                <div>
                    <label for="sort_order" class="{{ $labelClass }}">Sort Order</label>
                    <input id="sort_order" type="number" name="sort_order" min="0"
                           value="{{ old('sort_order', $menuItem->sort_order ?? 0) }}"
                           placeholder="0"
                           class="{{ $controlClass }}" style="{{ $controlStyle }}">
                    @error('sort_order') <p class="mt-1 text-xs text-red-500">{{ $errors->first('sort_order') }}</p> @enderror
                </div>

                {{-- Toggles --}}
                <div class="admin-info-item !rounded-xl !px-3 !py-2.5 flex flex-wrap gap-x-6 gap-y-2 sm:col-span-2">
                    <label class="flex cursor-pointer items-center gap-2.5">
                        <input type="checkbox" name="is_available" value="1"
                               {{ old('is_available', $menuItem->is_available) ? 'checked' : '' }}
                               class="h-4 w-4 accent-yellow-500">
                        <span class="text-[13px] font-semibold">Available</span>
                    </label>

                    <label class="flex cursor-pointer items-center gap-2.5">
                        <input type="checkbox" name="is_featured" value="1"
                               {{ old('is_featured', $menuItem->is_featured) ? 'checked' : '' }}
                               class="h-4 w-4 accent-yellow-500">
                        <span class="text-[13px] font-semibold">Featured Item</span>
                    </label>
                </div>

            </div>

            {{-- Form Buttons --}}
            <div class="mt-5 flex flex-wrap gap-2 border-t pt-4" style="border-color:var(--border);">

                <button type="submit"
                        class="admin-detail-action !px-3.5 !py-2 !text-xs"
                        style="color:#111c18;background:linear-gradient(135deg,#efd39a,#d6ad69 58%,#a17b41);">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>

                <a href="{{ route('admin.menu-items.index') }}"
                   class="admin-detail-action !px-3.5 !py-2 !text-xs border"
                   style="border-color:var(--border);">
                    Cancel
                </a>

            </div>

        </form>

    </section>

</div>

@endsection