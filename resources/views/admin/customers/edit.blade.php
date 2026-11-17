@extends('layouts.admin')

@section('title', 'Add Customer')
@section('page-heading', 'Add Customer')

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
            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl" style="font-family:'Playfair Display',serif;">Add New Customer</h1>
            <p class="muted mt-1 text-xs">Enter the customer's information below.</p>
        </div>

        <a href="{{ route('admin.customers.index') }}"
           class="admin-detail-action !px-3.5 !py-2 !text-xs border"
           style="border-color:var(--border);">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-300 bg-red-50 p-3 text-xs text-red-700">
            <strong><i class="fas fa-exclamation-circle mr-1"></i> Please correct the following errors:</strong>
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
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <h2 class="text-base font-bold leading-tight">Customer Information</h2>
                <p class="muted text-[11px]">Fields marked with * are required.</p>
            </div>
        </div>

        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                <div>
                    <label for="name" class="{{ $label }}">Customer Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           required maxlength="255" placeholder="Enter customer name"
                           class="{{ $fieldClass }} @error('name') !border-red-500 @enderror"
                           style="{{ $fieldStyle }}">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="{{ $label }}">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           maxlength="255" placeholder="example@email.com"
                           class="{{ $fieldClass }} @error('email') !border-red-500 @enderror"
                           style="{{ $fieldStyle }}">
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="phone" class="{{ $label }}">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                           maxlength="30" placeholder="e.g. 03001234567"
                           class="{{ $fieldClass }} @error('phone') !border-red-500 @enderror"
                           style="{{ $fieldStyle }}">
                    @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="address" class="{{ $label }}">Customer Address</label>
                    <textarea id="address" name="address" rows="3" maxlength="1000"
                              placeholder="Enter customer address"
                              class="{{ $fieldClass }} leading-6 @error('address') !border-red-500 @enderror"
                              style="{{ $fieldStyle }}">{{ old('address') }}</textarea>
                    @error('address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="{{ $label }}">Additional Notes</label>
                    <textarea id="notes" name="notes" rows="4" maxlength="2000"
                              placeholder="Enter any special notes about this customer..."
                              class="{{ $fieldClass }} leading-6 @error('notes') !border-red-500 @enderror"
                              style="{{ $fieldStyle }}">{{ old('notes') }}</textarea>
                    @error('notes') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="mt-5 flex flex-wrap gap-2 border-t pt-4" style="border-color:var(--border);">
                <button type="submit"
                        class="admin-detail-action !px-3.5 !py-2 !text-xs"
                        style="color:#111c18;background:linear-gradient(135deg,#efd39a,#d6ad69 58%,#a17b41);">
                    <i class="fas fa-save"></i> Save Customer
                </button>

                <a href="{{ route('admin.customers.index') }}"
                   class="admin-detail-action !px-3.5 !py-2 !text-xs border"
                   style="border-color:var(--border);">
                    Cancel
                </a>
            </div>

        </form>
    </section>
</div>
@endsection