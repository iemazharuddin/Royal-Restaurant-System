@extends('layouts.admin')

@section('title', 'View Contact Message')
@section('page-heading', 'Contact Message Details')

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
    background: linear-gradient(135deg, #f4dfb5 0%, #d6ad69 55%, #b68b4c 100%);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .2px;
    box-shadow: 0 5px 13px rgba(166, 126, 61, .20);
    transition: all .25s ease;
    cursor: pointer;
}

.gold-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 9px 19px rgba(166, 126, 61, .30);
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
    box-shadow: 0 3px 9px rgba(0, 0, 0, .04);
    transition: all .25s ease;
    cursor: pointer;
}

.white-gold-btn:hover {
    transform: translateY(-2px);
    border-color: #d6ad69;
    color: #b68b4c;
    box-shadow: 0 7px 15px rgba(166, 126, 61, .12);
}

.white-gold-btn:active {
    transform: translateY(0) scale(.98);
}

.gold-btn:focus-visible,
.white-gold-btn:focus-visible {
    outline: 3px solid rgba(214, 173, 105, .35);
    outline-offset: 3px;
}

.contact-info-card {
    min-width: 0;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 12px;
    background: var(--bg);
    transition: border-color .2s ease, transform .2s ease;
}

.contact-info-card:hover {
    border-color: #d6ad69;
    transform: translateY(-1px);
}

@media(max-width:480px) {
    .contact-top-actions {
        width: 100%;
    }

    .contact-top-actions a {
        flex: 1;
    }

    .contact-bottom-actions {
        width: 100%;
    }

    .contact-bottom-actions a,
    .contact-bottom-actions form,
    .contact-bottom-actions button {
        flex: 1;
    }

    .contact-bottom-actions form button {
        width: 100%;
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

            <h1 class="mt-1 text-xl font-extrabold sm:text-2xl" style="font-family:'Playfair Display',serif;">
                Contact Message Details
            </h1>

            <p class="muted mt-1 text-xs">
                View complete information submitted by the visitor.
            </p>
        </div>

        <div class="contact-top-actions flex flex-wrap gap-2">

            <a href="{{ route('admin.contacts.edit', $message->id) }}" class="gold-btn">
                <i class="fas fa-pen"></i>
                Edit Message
            </a>

            <a href="{{ route('admin.contacts.index') }}" class="white-gold-btn">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

        </div>
    </div>

    {{-- DETAILS CARD --}}
    <section class="surface admin-detail-card !rounded-2xl p-4 sm:p-5">

        {{-- CARD HEADER --}}
        <div class="mb-5 flex min-w-0 items-center gap-3 border-b pb-4" style="border-color:var(--border);">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm"
                style="color:#e6c27e;background:linear-gradient(145deg,#2c4236,#111c18);">
                <i class="fas fa-envelope"></i>
            </div>

            <div class="min-w-0">
                <h2 class="break-words text-base font-bold leading-tight">
                    {{ $message->subject ?: 'No Subject' }}
                </h2>

                <p class="muted mt-1 text-[11px]">
                    Message #{{ $message->id }}
                </p>
            </div>

        </div>

        {{-- MESSAGE INFORMATION --}}
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

            {{-- SENDER NAME --}}
            <div class="contact-info-card">
                <p class="muted mb-1 text-[10px] font-semibold uppercase tracking-wide">
                    <i class="fas fa-user mr-1 gold"></i>
                    Sender Name
                </p>

                <p class="break-words text-[13px] font-semibold">
                    {{ $message->name ?? 'N/A' }}
                </p>
            </div>

            {{-- EMAIL --}}
            <div class="contact-info-card">
                <p class="muted mb-1 text-[10px] font-semibold uppercase tracking-wide">
                    <i class="fas fa-at mr-1 gold"></i>
                    Email Address
                </p>

                @if($message->email)
                <a href="mailto:{{ $message->email }}" class="gold break-all text-[13px] font-semibold hover:underline">
                    {{ $message->email }}
                </a>
                @else
                <p class="text-[13px] font-semibold">N/A</p>
                @endif
            </div>

            {{-- SUBJECT --}}
            <div class="contact-info-card">
                <p class="muted mb-1 text-[10px] font-semibold uppercase tracking-wide">
                    <i class="fas fa-heading mr-1 gold"></i>
                    Subject
                </p>

                <p class="break-words text-[13px] font-semibold">
                    {{ $message->subject ?: 'No Subject' }}
                </p>
            </div>

            {{-- RECEIVED DATE --}}
            <div class="contact-info-card">
                <p class="muted mb-1 text-[10px] font-semibold uppercase tracking-wide">
                    <i class="fas fa-calendar-alt mr-1 gold"></i>
                    Received Date
                </p>

                <p class="text-[13px] font-semibold">
                    {{ $message->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                </p>
            </div>

            {{-- MESSAGE BODY --}}
            <div class="contact-info-card sm:col-span-2">

                <p class="muted mb-2 text-[10px] font-semibold uppercase tracking-wide">
                    <i class="fas fa-comment-alt mr-1 gold"></i>
                    Message
                </p>

                <div class="whitespace-pre-line break-words text-[13px] leading-7" style="color:var(--text);">
                    {{ $message->message }}
                </div>

            </div>

        </div>

        {{-- ACTION BUTTONS --}}
        <div class="contact-bottom-actions mt-6 flex flex-wrap items-center justify-between gap-3 border-t pt-5"
            style="border-color:var(--border);">

            <div class="flex flex-wrap gap-2">

                @if($message->email)
                <a href="mailto:{{ $message->email }}" class="gold-btn">
                    <i class="fas fa-reply"></i>
                    Reply by Email
                </a>
                @endif

            </div>

            {{-- DELETE FORM --}}
            <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST"
                data-confirm-delete="{{ $message->subject ?: 'message from '.$message->name }}"
                data-confirm-delete-label="message">

                @csrf
                @method('DELETE')

                <button type="submit" class="admin-danger-btn rounded-xl !px-4 !py-2.5 text-xs font-semibold"
                    aria-label="Delete message">

                    <i class="fas fa-trash mr-1.5"></i>
                    Delete Message

                </button>

            </form>
        </div>
    </section>
</div>
@endsection