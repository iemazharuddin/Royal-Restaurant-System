<x-guest-layout>
    <div class="mb-5 text-center">
        <p class="mb-2 text-xs font-semibold uppercase tracking-[3px] text-gold">Account recovery</p>
        <h1>Choose a new password</h1>
        <p class="mt-2 text-sm">Create a new password for your account.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" value="Email address" class="text-sm font-semibold" />
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                   placeholder="you@example.com" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="New password" class="text-sm font-semibold" />
            <input id="password" type="password" name="password" placeholder="Enter a new password"
                   required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirm new password" class="text-sm font-semibold" />
            <input id="password_confirmation" type="password" name="password_confirmation"
                   placeholder="Re-enter your new password" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
        </div>

        <button type="submit">Reset password</button>
    </form>
</x-guest-layout>
