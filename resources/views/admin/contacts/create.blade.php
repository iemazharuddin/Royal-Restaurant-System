
@extends('layouts.admin')

@section('title', 'Create Contact Message')
@section('page-heading', 'Create Contact Message')

@php
    $label = 'muted mb-1 block text-[10px] font-semibold uppercase tracking-wide';
    $fieldClass = 'w-full rounded-xl border px-3 py-2.5 text-[13px] outline-none transition focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/10';
    $fieldStyle = 'background:var(--bg);border-color:var(--border);color:var(--text);';
@endphp

@push('styles')
<style>
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

    /* White + Gold Button */
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
        .contact-top-actions,
        .contact-form-actions {
            width: 100%;
        }

        .contact-top-actions a,
        .contact-form-actions a,
        .contact-form-actions button {
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
                Create Contact Message
            </h1>

            <p class="muted mt-1 text-xs">
                Add a new contact message.
            </p>
        </div>

        <div class="contact-top-actions flex flex-wrap gap-2">
            <a href="{{ route('admin.contacts.index') }}"
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
                <i class="fas fa-envelope"></i>
            </div>

            <div>
                <h2 class="text-base font-bold leading-tight">
                    Message Information
                </h2>

                <p class="muted text-[11px]">
                    Enter the contact message details below.
                </p>
            </div>

        </div>

        <form action="{{ route('admin.contacts.store') }}"
              method="POST"
              id="createContactForm">

            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                {{-- NAME --}}
                <div>
                    <label for="name" class="{{ $label }}">
                        Name <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="Enter full name"
                           class="{{ $fieldClass }}"
                           style="{{ $fieldStyle }}">

                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div>
                    <label for="email" class="{{ $label }}">
                        Email <span class="text-red-500">*</span>
                    </label>

                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           placeholder="Enter email address"
                           class="{{ $fieldClass }}"
                           style="{{ $fieldStyle }}">

                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SUBJECT --}}
                <div class="sm:col-span-2">
                    <label for="subject" class="{{ $label }}">
                        Subject
                    </label>

                    <input type="text"
                           id="subject"
                           name="subject"
                           value="{{ old('subject') }}"
                           placeholder="Enter subject"
                           class="{{ $fieldClass }}"
                           style="{{ $fieldStyle }}">

                    @error('subject')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- MESSAGE --}}
                <div class="sm:col-span-2">
                    <label for="message" class="{{ $label }}">
                        Message <span class="text-red-500">*</span>
                    </label>

                    <textarea id="message"
                              name="message"
                              rows="6"
                              required
                              placeholder="Write contact message..."
                              class="{{ $fieldClass }} leading-6"
                              style="{{ $fieldStyle }}">{{ old('message') }}</textarea>

                    @error('message')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- FORM BUTTONS --}}
            <div class="contact-form-actions mt-6 flex flex-wrap items-center gap-2.5 border-t pt-5"
                 style="border-color:var(--border);">

                <button type="submit"
                        id="createContactButton"
                        class="gold-btn">

                    <i class="fas fa-plus"></i>
                    <span>Create Message</span>

                </button>

                <a href="{{ route('admin.contacts.index') }}"
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
    document.getElementById('createContactForm')?.addEventListener('submit', function () {
        const button = document.getElementById('createContactButton');

        if (button) {
            button.disabled = true;
            button.style.opacity = '0.75';
            button.style.cursor = 'wait';

            button.innerHTML = `
                <i class="fas fa-spinner fa-spin"></i>
                <span>Creating...</span>
            `;
        }
    });
</script>
@endpush
