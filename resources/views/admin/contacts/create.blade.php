@extends('layouts.admin')

@section('title', 'Create Contact Message')
@section('page-heading', 'Create Contact Message')

@section('content')
@php
    $label = 'muted mb-1 block text-[10px] font-medium uppercase tracking-wide';
    $fieldClass = 'w-full rounded-xl border px-3 py-2.5 text-[13px] outline-none focus:border-yellow-500';
    $fieldStyle = 'background:var(--bg);border-color:var(--border);color:var(--text);';
@endphp

<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">Management</p>
            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl" style="font-family:'Playfair Display',serif;">Create Contact Message</h1>
            <p class="muted mt-1 text-xs">Add a new contact message.</p>
        </div>

        <a href="{{ route('admin.contacts.index') }}"
           class="admin-detail-action !px-3.5 !py-2 !text-xs border"
           style="border-color:var(--border);">
            <i class="fas fa-arrow-left"></i> Back
        </a>
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
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <h2 class="text-base font-bold leading-tight">Message Information</h2>
                <p class="muted text-[11px]">Enter the contact message details below.</p>
            </div>
        </div>

        <form action="{{ route('admin.contacts.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                <div>
                    <label class="{{ $label }}">Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           required placeholder="Enter full name"
                           class="{{ $fieldClass }}" style="{{ $fieldStyle }}">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $label }}">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           required placeholder="Enter email address"
                           class="{{ $fieldClass }}" style="{{ $fieldStyle }}">
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="{{ $label }}">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                           placeholder="Enter subject"
                           class="{{ $fieldClass }}" style="{{ $fieldStyle }}">
                    @error('subject') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="{{ $label }}">Message *</label>
                    <textarea name="message" rows="6" required
                              placeholder="Write contact message..."
                              class="{{ $fieldClass }} leading-6"
                              style="{{ $fieldStyle }}">{{ old('message') }}</textarea>
                    @error('message') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="mt-5 flex flex-wrap gap-2 border-t pt-4" style="border-color:var(--border);">
                <button type="submit"
                        class="admin-detail-action !px-3.5 !py-2 !text-xs"
                        style="color:#111c18;background:linear-gradient(135deg,#efd39a,#d6ad69 58%,#a17b41);">
                    <i class="fas fa-plus"></i> Create Message
                </button>

                <a href="{{ route('admin.contacts.index') }}"
                   class="admin-detail-action !px-3.5 !py-2 !text-xs border"
                   style="border-color:var(--border);">
                    Cancel
                </a>
            </div>

        </form>
    </section>
</div>
@endsection