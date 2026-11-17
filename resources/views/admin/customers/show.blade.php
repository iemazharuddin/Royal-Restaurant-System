@extends('layouts.admin')

@section('title', 'Customer Details')
@section('page-heading', 'Customer Details')

@section('content')
<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">Management</p>
            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl" style="font-family:'Playfair Display',serif;">Customer Details</h1>
            <p class="muted mt-1 text-xs">View complete customer information.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.customers.edit', $customer) }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs"
               style="color:#111c18;background:linear-gradient(135deg,#efd39a,#d6ad69 58%,#a17b41);">
                <i class="fas fa-pen"></i> Edit Customer
            </a>
            <a href="{{ route('admin.customers.index') }}"
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

    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b pb-4" style="border-color:var(--border);">
            <div class="flex min-w-0 items-center gap-2.5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-base font-bold text-gold"
                     style="background:linear-gradient(145deg,#2c4236,#111c18);">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <h2 class="break-words text-base font-bold leading-tight">{{ $customer->name }}</h2>
                    <p class="muted text-[11px]">Customer #{{ $customer->id }}</p>
                </div>
            </div>

            <span class="rounded-full bg-green-500/10 px-2.5 py-1 text-[10px] font-bold text-green-600">
                <i class="fas fa-user-check mr-1"></i> Customer
            </span>
        </div>

        <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Customer Name</p>
                <p class="text-[13px] font-semibold">{{ $customer->name ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Email Address</p>
                <p class="break-all text-[13px] font-semibold">{{ $customer->email ?: 'Not provided' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Phone Number</p>
                <p class="text-[13px] font-semibold">{{ $customer->phone ?: 'Not provided' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Customer ID</p>
                <p class="text-[13px] font-semibold">#{{ $customer->id }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Customer Added</p>
                <p class="text-[13px] font-semibold">{{ $customer->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Last Updated</p>
                <p class="text-[13px] font-semibold">{{ $customer->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5 sm:col-span-2">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Address</p>
                <p class="whitespace-pre-line text-[13px] leading-6">{{ $customer->address ?: 'No address provided.' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5 sm:col-span-2">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Additional Notes</p>
                <p class="whitespace-pre-line text-[13px] leading-6">{{ $customer->notes ?: 'No additional notes.' }}</p>
            </div>
        </div>

        <div class="mt-5 flex justify-end border-t pt-4" style="border-color:var(--border);">
            <form action="{{ route('admin.customers.destroy', $customer) }}"
                  method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this customer?')">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="admin-danger-btn rounded-xl !px-3.5 !py-2 text-xs font-semibold">
                    <i class="fas fa-trash mr-1.5"></i> Delete Customer
                </button>
            </form>
        </div>

    </section>
</div>
@endsection