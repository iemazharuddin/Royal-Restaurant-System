
@extends('layouts.admin')

@section('title', 'Settings')
@section('page-heading', 'Settings')

@section('content')

@php
    $getSetting = function ($key, $default = '') use ($settings) {
        $setting = $settings->firstWhere('key', $key);
        return old($key, $setting->value ?? $default);
    };
@endphp

<style>
    .settings-page {
        --gold: #eab85c;
    }

    .settings-hero {
        background: linear-gradient(120deg, #101d32, #1b2d47);
        border: 1px solid rgba(234,184,92,.22);
    }

    .settings-card {
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .settings-input {
        width: 100%;
        border: 1px solid var(--border);
        border-radius: 9px;
        padding: 9px 12px;
        min-height: 38px;
        background: transparent;
        color: var(--text);
        font-size: 12px;
        outline: none;
        transition: .2s;
    }

    .settings-input:focus {
        border-color: #eab85c;
        box-shadow: 0 0 0 3px rgba(234,184,92,.12);
    }

    .settings-input::placeholder {
        font-size: 11px;
        opacity: .6;
    }

    .settings-label {
        display: block;
        margin-bottom: 6px;
        font-size: 11px;
        font-weight: 700;
    }

    .settings-section-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 9px;
        background: rgba(234,184,92,.12);
        color: #d9a747;
        font-size: 14px;
    }

    .settings-save {
        background: #eab85c;
        color: #172338;
        transition: .2s;
    }

    .settings-save:hover {
        background: #d9a747;
        transform: translateY(-1px);
    }

    .settings-muted {
        color: var(--muted, #94a3b8);
    }

    .settings-page textarea.settings-input {
        min-height: 75px;
        resize: vertical;
    }

    @media(max-width:640px) {
        .settings-hero {
            padding: 16px !important;
            border-radius: 12px !important;
        }

        .settings-page .settings-card > div:first-child {
            padding: 13px;
        }

        .settings-page .settings-card > div:last-child {
            padding: 14px;
        }

        .settings-save {
            width: 100%;
            justify-content: center;
        }
    }

    @media print {
        aside, nav, header, button {
            display: none !important;
        }

        .settings-card {
            break-inside: avoid;
        }
    }
</style>

<div class="settings-page space-y-5">

    {{-- PAGE HEADER --}}
    <div class="settings-hero relative overflow-hidden rounded-2xl p-5 sm:p-6">

        <div class="pointer-events-none absolute -right-10 -top-12 h-40 w-40 rounded-full"
             style="background:rgba(234,184,92,.08);"></div>

        <div class="relative flex items-center gap-3">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-xl"
                 style="background:rgba(234,184,92,.15);color:#eab85c;">
                <i class="fas fa-cog"></i>
            </div>

            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-[.2em]"
                   style="color:#eab85c;">
                    Management
                </p>

                <h1 class="mt-1 text-xl font-extrabold text-white sm:text-2xl">
                    Restaurant Settings
                </h1>

                <p class="mt-1 text-xs" style="color:#b7c3d5;">
                    Manage restaurant information and website preferences.
                </p>
            </div>

        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="flex items-center gap-2 rounded-xl bg-green-500/10 p-3 text-xs text-green-600">
            <i class="fas fa-check-circle text-base"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="rounded-xl bg-red-500/10 p-3 text-xs text-red-600">
            <p class="mb-2 font-bold">Please fix the following errors:</p>

            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.settings.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-4">

        @csrf

        {{-- RESTAURANT INFORMATION --}}
        <div class="surface settings-card">

            <div class="flex items-center gap-3 border-b p-4"
                 style="border-color:var(--border);">

                <div class="settings-section-icon">
                    <i class="fas fa-store"></i>
                </div>

                <div>
                    <h2 class="text-sm font-extrabold">Restaurant Information</h2>
                    <p class="settings-muted mt-1 text-[10px]">
                        Basic details about your restaurant.
                    </p>
                </div>

            </div>

            <div class="grid gap-4 p-4 md:grid-cols-2">

                <div>
                    <label class="settings-label">Restaurant Name *</label>

                    <input type="text"
                           name="restaurant_name"
                           value="{{ $getSetting('restaurant_name', 'Royal Restaurant') }}"
                           class="settings-input"
                           placeholder="Royal Restaurant"
                           required>
                </div>

                <div>
                    <label class="settings-label">Tagline</label>

                    <input type="text"
                           name="tagline"
                           value="{{ $getSetting('tagline') }}"
                           class="settings-input"
                           placeholder="Delicious Food, Royal Experience">
                </div>

                <div class="md:col-span-2">
                    <label class="settings-label">Restaurant Address</label>

                    <textarea name="address"
                              rows="2"
                              class="settings-input"
                              placeholder="Enter restaurant address">{{ $getSetting('address') }}</textarea>
                </div>

            </div>
        </div>

        {{-- CONTACT INFORMATION --}}
        <div class="surface settings-card">

            <div class="flex items-center gap-3 border-b p-4"
                 style="border-color:var(--border);">

                <div class="settings-section-icon">
                    <i class="fas fa-address-book"></i>
                </div>

                <div>
                    <h2 class="text-sm font-extrabold">Contact Information</h2>
                    <p class="settings-muted mt-1 text-[10px]">
                        Phone number and email address.
                    </p>
                </div>

            </div>

            <div class="grid gap-4 p-4 md:grid-cols-2">

                <div>
                    <label class="settings-label">Phone Number</label>

                    <input type="text"
                           name="phone"
                           value="{{ $getSetting('phone') }}"
                           class="settings-input"
                           placeholder="+92 300 1234567">
                </div>

                <div>
                    <label class="settings-label">Email Address</label>

                    <input type="email"
                           name="email"
                           value="{{ $getSetting('email') }}"
                           class="settings-input"
                           placeholder="restaurant@example.com">
                </div>

            </div>
        </div>

        {{-- OPENING HOURS --}}
        <div class="surface settings-card">

            <div class="flex items-center gap-3 border-b p-4"
                 style="border-color:var(--border);">

                <div class="settings-section-icon">
                    <i class="fas fa-clock"></i>
                </div>

                <div>
                    <h2 class="text-sm font-extrabold">Opening Hours</h2>
                    <p class="settings-muted mt-1 text-[10px]">
                        Set your restaurant operating hours.
                    </p>
                </div>

            </div>

            <div class="p-4">

                <label class="settings-label">Opening Hours Details</label>

                <textarea name="opening_hours"
                          rows="3"
                          class="settings-input"
                          placeholder="Monday - Friday: 10:00 AM - 11:00 PM&#10;Saturday - Sunday: 12:00 PM - 12:00 AM">{{ $getSetting('opening_hours') }}</textarea>

                <p class="settings-muted mt-2 text-[10px]">
                    Example: Monday - Sunday: 10:00 AM - 11:00 PM
                </p>

            </div>
        </div>

        {{-- SOCIAL MEDIA --}}
        <div class="surface settings-card">

            <div class="flex items-center gap-3 border-b p-4"
                 style="border-color:var(--border);">

                <div class="settings-section-icon">
                    <i class="fas fa-share-alt"></i>
                </div>

                <div>
                    <h2 class="text-sm font-extrabold">Social Media Links</h2>
                    <p class="settings-muted mt-1 text-[10px]">
                        Connect your restaurant social accounts.
                    </p>
                </div>

            </div>

            <div class="grid gap-4 p-4 md:grid-cols-2">

                <div>
                    <label class="settings-label">
                        <i class="fab fa-facebook text-blue-500 mr-1"></i>
                        Facebook URL
                    </label>

                    <input type="url"
                           name="facebook"
                           value="{{ $getSetting('facebook') }}"
                           class="settings-input"
                           placeholder="https://facebook.com/yourpage">
                </div>

                <div>
                    <label class="settings-label">
                        <i class="fab fa-instagram text-pink-500 mr-1"></i>
                        Instagram URL
                    </label>

                    <input type="url"
                           name="instagram"
                           value="{{ $getSetting('instagram') }}"
                           class="settings-input"
                           placeholder="https://instagram.com/yourpage">
                </div>

                <div class="md:col-span-2">
                    <label class="settings-label">
                        <i class="fab fa-twitter text-sky-500 mr-1"></i>
                        Twitter / X URL
                    </label>

                    <input type="url"
                           name="twitter"
                           value="{{ $getSetting('twitter') }}"
                           class="settings-input"
                           placeholder="https://x.com/yourpage">
                </div>

            </div>
        </div>

        {{-- RESTAURANT LOGO --}}
        <div class="surface settings-card">

            <div class="flex items-center gap-3 border-b p-4"
                 style="border-color:var(--border);">

                <div class="settings-section-icon">
                    <i class="fas fa-image"></i>
                </div>

                <div>
                    <h2 class="text-sm font-extrabold">Restaurant Logo</h2>
                    <p class="settings-muted mt-1 text-[10px]">
                        Upload your restaurant logo.
                    </p>
                </div>

            </div>

            <div class="space-y-3 p-4">

                @if($getSetting('logo'))

                    <div class="flex items-center gap-3">

                        <img src="{{ asset('storage/' . $getSetting('logo')) }}"
                             alt="Restaurant Logo"
                             class="h-16 w-16 rounded-lg border object-contain p-2"
                             style="border-color:var(--border);">

                        <div>
                            <p class="text-xs font-bold">Current Logo</p>
                            <p class="settings-muted mt-1 text-[10px]">
                                Upload a new image to replace it.
                            </p>
                        </div>

                    </div>

                @endif

                <input type="file"
                       name="logo"
                       accept="image/*"
                       class="settings-input">

                <p class="settings-muted text-[10px]">
                    Recommended: PNG, JPG or WEBP image.
                </p>

            </div>
        </div>

        {{-- SAVE BUTTON --}}
        <div class="surface flex flex-col justify-between gap-3 rounded-xl border p-4 sm:flex-row sm:items-center"
             style="border-color:var(--border);">

            <div>
                <h3 class="text-sm font-extrabold">Save Restaurant Settings</h3>
                <p class="settings-muted mt-1 text-[10px]">
                    Save your changes to update restaurant information.
                </p>
            </div>

            <button type="submit"
                    class="settings-save inline-flex items-center justify-center gap-2 rounded-lg px-5 py-2.5 text-xs font-extrabold shadow-md">

                <i class="fas fa-save"></i>
                Save Settings

            </button>

        </div>

    </form>

</div>

@endsection
