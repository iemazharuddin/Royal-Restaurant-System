<x-guest-layout>
    <div class="mb-5 text-center">
        <p class="mb-2 text-xs font-semibold uppercase tracking-[3px] text-gold">Account recovery</p>
        <h1>Forgot your password?</h1>
        <p class="mt-2 text-sm">Enter your email and we’ll send you a link to reset your password.</p>
    </div>

    <x-auth-session-status class="mb-4 text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Email address" class="text-sm font-semibold" />
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   placeholder="you@example.com" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
        </div>

        <button type="submit">Send reset link</button>
    </form>

    <div class="mt-5 border-t border-gray-200 pt-4 text-center text-xs">
        <a class="font-semibold" href="{{ route('login') }}">Back to sign in</a>
    </div>
</x-guest-layout>
