@extends('layouts.admin')

@php
    // Shared compact styling (same as the Reservation form)
    $labelClass   = 'muted mb-1 block text-[10px] font-medium uppercase tracking-wide';
    $controlClass = 'w-full rounded-xl border px-3 py-2.5 text-[13px] outline-none focus:border-yellow-500';
    $controlStyle = 'background:var(--bg);border-color:var(--border);color:var(--text);';
@endphp

@section('title', 'Edit Contact Message')
@section('page-heading', 'Edit Contact Message')

@section('content')

<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">Management</p>
            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl" style="font-family:'Playfair Display',serif;">Edit Contact Message</h1>
            <p class="muted mt-1 text-xs">Update the existing record information.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.contacts.show', $message->id) }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs border"
               style="border-color:var(--border);">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('admin.contacts.index') }}"
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
                <p class="muted truncate text-[11px]">Message from {{ $message->name }}</p>
            </div>
        </div>

        <form action="{{ route('admin.contacts.update', $message->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                {{-- Name --}}
                <div>
                    <label for="name" class="{{ $labelClass }}">Name <span class="text-red-500">*</span></label>
                    <input id="name" type="text" name="name" required
                           value="{{ old('name', $message->name) }}"
                           placeholder="Enter name"
                           class="{{ $controlClass }}" style="{{ $controlStyle }}">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $errors->first('name') }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="{{ $labelClass }}">Email Address <span class="text-red-500">*</span></label>
                    <input id="email" type="email" name="email" required
                           value="{{ old('email', $message->email) }}"
                           placeholder="Enter email address"
                           class="{{ $controlClass }}" style="{{ $controlStyle }}">
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $errors->first('email') }}</p> @enderror
                </div>

                {{-- Subject --}}
                <div class="sm:col-span-2">
                    <label for="subject" class="{{ $labelClass }}">Subject</label>
                    <input id="subject" type="text" name="subject"
                           value="{{ old('subject', $message->subject) }}"
                           placeholder="Enter subject"
                           class="{{ $controlClass }}" style="{{ $controlStyle }}">
                    @error('subject') <p class="mt-1 text-xs text-red-500">{{ $errors->first('subject') }}</p> @enderror
                </div>

                {{-- Message --}}
                <div class="sm:col-span-2">
                    <label for="message" class="{{ $labelClass }}">Message <span class="text-red-500">*</span></label>
                    <textarea id="message" name="message" rows="6" required
                              placeholder="Enter message"
                              class="{{ $controlClass }} leading-6"
                              style="{{ $controlStyle }}">{{ old('message', $message->message) }}</textarea>
                    @error('message') <p class="mt-1 text-xs text-red-500">{{ $errors->first('message') }}</p> @enderror
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