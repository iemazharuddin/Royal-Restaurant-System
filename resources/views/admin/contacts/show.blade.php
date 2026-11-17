@extends('layouts.admin')

@section('title', 'View Contact Message')
@section('page-heading', 'Contact Message Details')

@section('content')
<div class="mx-auto max-w-4xl min-w-0 space-y-4">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest gold">Management</p>
            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl" style="font-family:'Playfair Display',serif;">Contact Message Details</h1>
            <p class="muted mt-1 text-xs">View complete information submitted by the visitor.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.contacts.edit', $message->id) }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs"
               style="color:#111c18;background:linear-gradient(135deg,#efd39a,#d6ad69 58%,#a17b41);">
                <i class="fas fa-pen"></i> Edit Message
            </a>
            <a href="{{ route('admin.contacts.index') }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs border"
               style="border-color:var(--border);">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        <div class="mb-4 flex items-center gap-2.5 border-b pb-4" style="border-color:var(--border);">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-sm text-gold"
                 style="background:linear-gradient(145deg,#2c4236,#111c18);">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="min-w-0">
                <h2 class="break-words text-base font-bold leading-tight">{{ $message->subject ?: 'No Subject' }}</h2>
                <p class="muted text-[11px]">Message #{{ $message->id }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Sender Name</p>
                <p class="text-[13px] font-semibold">{{ $message->name ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Email Address</p>
                <a href="mailto:{{ $message->email }}"
                   class="gold break-all text-[13px] font-semibold hover:underline">
                    {{ $message->email ?? 'N/A' }}
                </a>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Subject</p>
                <p class="text-[13px] font-semibold">{{ $message->subject ?: 'No Subject' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Received Date</p>
                <p class="text-[13px] font-semibold">{{ $message->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</p>
            </div>
            <div class="admin-info-item !rounded-xl !px-3 !py-2.5 sm:col-span-2">
                <p class="muted mb-0.5 text-[10px] font-medium uppercase tracking-wide">Message</p>
                <p class="whitespace-pre-line text-[13px] leading-6">{{ $message->message }}</p>
            </div>
        </div>

        <div class="mt-5 flex flex-wrap items-center justify-between gap-2 border-t pt-4" style="border-color:var(--border);">

            <a href="mailto:{{ $message->email }}"
               class="admin-detail-action !px-3.5 !py-2 !text-xs"
               style="color:#111c18;background:linear-gradient(135deg,#efd39a,#d6ad69 58%,#a17b41);">
                <i class="fas fa-reply"></i> Reply by Email
            </a>

            <form action="{{ route('admin.contacts.destroy', $message->id) }}"
                  method="POST"
                  data-confirm-delete="{{ $message->subject ?: 'message from '.$message->name }}"
                  data-confirm-delete-label="message">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="admin-danger-btn rounded-xl !px-3.5 !py-2 text-xs font-semibold"
                        aria-label="Delete message">
                    <i class="fas fa-trash mr-1.5"></i> Delete Message
                </button>
            </form>

        </div>

    </section>
</div>
@endsection
