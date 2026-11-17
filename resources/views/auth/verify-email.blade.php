<x-guest-layout>
    <div class="mb-5 text-center">
        <p class="mb-2 text-xs font-semibold uppercase tracking-[3px] text-gold">One last step</p>
        <h1>Verify your email</h1>
        <p class="mt-2 text-sm">
            {{ __('Click the link we emailed you to verify your address. If you did not receive it, we can send another.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">{{ __('Resend verification email') }}</button>
    </form>

    <div class="mt-4 text-center text-xs">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="auth-logout">
                {{ __('Log out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
