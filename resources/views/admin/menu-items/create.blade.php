
@extends('layouts.admin')

@php
    $labelClass = 'muted mb-1 block text-[10px] font-semibold uppercase tracking-wide';
    $controlClass = 'w-full rounded-xl border px-3 py-2.5 text-[13px] outline-none transition focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/10';
    $controlStyle = 'background:var(--bg);border-color:var(--border);color:var(--text);';

    $isAvailable = session()->hasOldInput() ? (bool) old('is_available') : true;
@endphp

@section('title', 'Add Menu Item')
@section('page-heading', 'Add Menu Item')

@push('styles')
<style>
    select.admin-select option {
        font-size: 13px;
        padding: 8px 10px;
        background: var(--surface);
        color: var(--text);
    }

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
            box-shadow: 0 14px 34px rgba(9,18,14,.18);
        }

        select.admin-select:not([multiple])::picker-icon {
            color: var(--muted);
            transition: rotate .2s ease;
        }

        select.admin-select:not([multiple]):open::picker-icon {
            rotate: 180deg;
        }

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
            background: rgba(214,173,105,.18);
        }

        select.admin-select:not([multiple]) option:checked {
            background: rgba(214,173,105,.28);
            font-weight: 700;
        }

        select.admin-select:not([multiple]) option::checkmark {
            display: none;
        }
    }

    /* Gold Button */
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

    /* White Button */
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
        .form-actions a,
        .form-actions button {
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
                Add Menu Item
            </h1>

            <p class="muted mt-1 text-xs">
                Add a delicious item to your restaurant menu.
            </p>
        </div>

        <a href="{{ route('admin.menu-items.index') }}"
           class="white-gold-btn">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="rounded-xl bg-green-500/10 p-3 text-xs text-green-600">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-300 bg-red-50 p-3 text-xs text-red-700">
            <strong>
                <i class="fas fa-exclamation-circle mr-1"></i>
                Please fix the following errors:
            </strong>

            <ul class="mt-1.5 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM CARD --}}
    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        {{-- CARD HEADER --}}
        <div class="mb-5 flex items-center gap-3 border-b pb-4"
             style="border-color:var(--border);">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm"
                 style="color:#e6c27e;background:linear-gradient(145deg,#2c4236,#111c18);">
                <i class="fas fa-utensils"></i>
            </div>

            <div class="min-w-0">
                <h2 class="text-base font-bold leading-tight">
                    Menu Item Information
                </h2>

                <p class="muted mt-1 text-[11px]">
                    Enter the details for your new menu item.
                </p>
            </div>
        </div>

        <form action="{{ route('admin.menu-items.store') }}"
              method="POST"
              enctype="multipart/form-data"
              id="menuItemForm">

            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                {{-- NAME --}}
                <div class="sm:col-span-2">
                    <label for="name" class="{{ $labelClass }}">
                        Menu Item Name <span class="text-red-500">*</span>
                    </label>

                    <input id="name"
                           type="text"
                           name="name"
                           required
                           value="{{ old('name') }}"
                           placeholder="e.g. Chicken Biryani"
                           class="{{ $controlClass }}"
                           style="{{ $controlStyle }}">

                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CATEGORY --}}
                <div>
                    <label for="category_id" class="{{ $labelClass }}">
                        Category <span class="text-red-500">*</span>
                    </label>

                    <select id="category_id"
                            name="category_id"
                            required
                            class="{{ $controlClass }} admin-select"
                            style="{{ $controlStyle }}">

                        <option value="">Select Category</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('category_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PRICE --}}
                <div>
                    <label for="price" class="{{ $labelClass }}">
                        Price (Rs.) <span class="text-red-500">*</span>
                    </label>

                    <input id="price"
                           type="number"
                           name="price"
                           min="0"
                           step="0.01"
                           required
                           value="{{ old('price') }}"
                           placeholder="e.g. 850"
                           class="{{ $controlClass }}"
                           style="{{ $controlStyle }}">

                    @error('price')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DESCRIPTION --}}
                <div class="sm:col-span-2">
                    <label for="description" class="{{ $labelClass }}">
                        Description
                    </label>

                    <textarea id="description"
                              name="description"
                              rows="4"
                              placeholder="Describe this menu item..."
                              class="{{ $controlClass }} leading-6"
                              style="{{ $controlStyle }}">{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- IMAGE --}}
                <div>
                    <label for="image" class="{{ $labelClass }}">
                        Menu Item Image
                    </label>

                    <input id="image"
                           type="file"
                           name="image"
                           accept="image/*"
                           class="{{ $controlClass }} min-w-0"
                           style="{{ $controlStyle }}">

                    <p class="muted mt-1 text-[11px]">
                        Upload JPG, PNG or WEBP image.
                    </p>

                    @error('image')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SORT ORDER --}}
                <div>
                    <label for="sort_order" class="{{ $labelClass }}">
                        Sort Order
                    </label>

                    <input id="sort_order"
                           type="number"
                           name="sort_order"
                           min="0"
                           value="{{ old('sort_order', 0) }}"
                           placeholder="0"
                           class="{{ $controlClass }}"
                           style="{{ $controlStyle }}">

                    @error('sort_order')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TOGGLES --}}
                <div class="admin-info-item !rounded-xl !px-3 !py-3 flex flex-wrap gap-x-6 gap-y-3 sm:col-span-2">

                    <label class="flex cursor-pointer items-center gap-2.5">
                        <input type="checkbox"
                               name="is_available"
                               value="1"
                               {{ $isAvailable ? 'checked' : '' }}
                               class="h-4 w-4 accent-yellow-500">

                        <span class="text-[13px] font-semibold">
                            Available
                        </span>
                    </label>

                    <label class="flex cursor-pointer items-center gap-2.5">
                        <input type="checkbox"
                               name="is_featured"
                               value="1"
                               {{ old('is_featured') ? 'checked' : '' }}
                               class="h-4 w-4 accent-yellow-500">

                        <span class="text-[13px] font-semibold">
                            Featured Item
                        </span>
                    </label>

                </div>

            </div>

            {{-- FORM BUTTONS --}}
            <div class="form-actions mt-6 flex flex-wrap items-center gap-2.5 border-t pt-5"
                 style="border-color:var(--border);">

                <button type="submit"
                        id="saveButton"
                        class="gold-btn">

                    <i class="fas fa-save"></i>
                    <span>Save Menu Item</span>
                </button>

                <a href="{{ route('admin.menu-items.index') }}"
                   class="white-gold-btn">

                    <i class="fas fa-times"></i>
                    Cancel
                </a>

            </div>

        </form>

    </section>

</div>

@endsection

@push('scripts')
<script>
    document.getElementById('menuItemForm')?.addEventListener('submit', function () {
        const button = document.getElementById('saveButton');

        if (button) {
            button.disabled = true;
            button.style.opacity = '0.75';
            button.style.cursor = 'wait';

            button.innerHTML = `
                <i class="fas fa-spinner fa-spin"></i>
                <span>Saving...</span>
            `;
        }
    });
</script>
@endpush
