
@extends('layouts.admin')

@section('title', 'Edit Category')
@section('page-heading', 'Edit Category')

@php
    $fieldClass = 'w-full rounded-xl border px-3 py-2.5 text-[13px] outline-none transition focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/10';
    $fieldStyle = 'background:var(--bg);border-color:var(--border);color:var(--text);';
@endphp

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
        .category-top-actions,
        .category-form-actions {
            width: 100%;
        }

        .category-top-actions a,
        .category-form-actions a,
        .category-form-actions button {
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
                Edit Category
            </h1>

            <p class="muted mt-1 text-xs">
                Update your restaurant category information.
            </p>
        </div>

        {{-- TOP BUTTONS --}}
        <div class="category-top-actions flex flex-wrap gap-2">

            <a href="{{ route('admin.categories.show', $category) }}"
               class="white-gold-btn">
                <i class="fas fa-eye"></i>
                View
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="white-gold-btn">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

        </div>
    </div>

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
                <i class="fas fa-pen-to-square"></i>
            </div>

            <div class="min-w-0">
                <h2 class="text-base font-bold leading-tight">
                    Update Category
                </h2>

                <p class="muted truncate text-[11px]">
                    Editing: {{ $category->name }}
                </p>
            </div>

        </div>

        <form action="{{ route('admin.categories.update', $category) }}"
              method="POST"
              enctype="multipart/form-data"
              id="editCategoryForm">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                {{-- CATEGORY NAME --}}
                <div>
                    <label for="name" class="muted mb-1 block text-[10px] font-semibold uppercase tracking-wide">
                        Category Name <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $category->name) }}"
                           required
                           placeholder="Enter category name"
                           class="{{ $fieldClass }}"
                           style="{{ $fieldStyle }}">

                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SLUG --}}
                <div>
                    <label for="slug" class="muted mb-1 block text-[10px] font-semibold uppercase tracking-wide">
                        Slug
                    </label>

                    <input type="text"
                           id="slug"
                           name="slug"
                           value="{{ old('slug', $category->slug) }}"
                           placeholder="category-slug"
                           class="{{ $fieldClass }}"
                           style="{{ $fieldStyle }}">

                    @error('slug')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CATEGORY IMAGE --}}
                <div>
                    <label for="image" class="muted mb-1 block text-[10px] font-semibold uppercase tracking-wide">
                        Replace Category Image
                    </label>

                    <div class="flex min-w-0 items-center gap-3">

                        @if($category->image)
                            <img src="{{ asset('storage/'.$category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="h-12 w-12 shrink-0 rounded-lg object-cover">
                        @endif

                        <input type="file"
                               id="image"
                               name="image"
                               accept="image/*"
                               class="{{ $fieldClass }} min-w-0"
                               style="{{ $fieldStyle }}">

                    </div>

                    @unless($category->image)
                        <p class="muted mt-1 text-[11px]">
                            No image uploaded.
                        </p>
                    @endunless

                    @error('image')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SORT ORDER --}}
                <div>
                    <label for="sort_order" class="muted mb-1 block text-[10px] font-semibold uppercase tracking-wide">
                        Sort Order
                    </label>

                    <input type="number"
                           id="sort_order"
                           name="sort_order"
                           min="0"
                           value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                           placeholder="0"
                           class="{{ $fieldClass }}"
                           style="{{ $fieldStyle }}">

                    @error('sort_order')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DESCRIPTION --}}
                <div class="sm:col-span-2">
                    <label for="description" class="muted mb-1 block text-[10px] font-semibold uppercase tracking-wide">
                        Description
                    </label>

                    <textarea id="description"
                              name="description"
                              rows="4"
                              placeholder="Write category description..."
                              class="{{ $fieldClass }} leading-6"
                              style="{{ $fieldStyle }}">{{ old('description', $category->description) }}</textarea>

                    @error('description')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ACTIVE CATEGORY --}}
                <div class="admin-info-item !rounded-xl !px-3 !py-3 sm:col-span-2">

                    <label class="flex cursor-pointer items-center gap-2.5">

                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 accent-yellow-500">

                        <span class="text-[13px] font-semibold">
                            Active Category
                        </span>

                    </label>

                </div>

            </div>

            {{-- FORM BUTTONS --}}
            <div class="category-form-actions mt-6 flex flex-wrap items-center gap-2.5 border-t pt-5"
                 style="border-color:var(--border);">

                <button type="submit"
                        id="updateCategoryButton"
                        class="gold-btn">

                    <i class="fas fa-save"></i>
                    <span>Update Category</span>

                </button>

                <a href="{{ route('admin.categories.index') }}"
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
    document.getElementById('editCategoryForm')?.addEventListener('submit', function () {
        const button = document.getElementById('updateCategoryButton');

        if (button) {
            button.disabled = true;
            button.style.opacity = '0.75';
            button.style.cursor = 'wait';

            button.innerHTML = `
                <i class="fas fa-spinner fa-spin"></i>
                <span>Updating...</span>
            `;
        }
    });
</script>
@endpush
