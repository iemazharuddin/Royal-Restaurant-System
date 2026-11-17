<x-guest-layout>
    <div class="mb-5 text-center">
        <p class="mb-2 text-xs font-semibold uppercase tracking-[3px] text-gold">Security check</p>
        <h1>Confirm your password</h1>
        <p class="mt-2 text-sm">{{ __('Please confirm your password before continuing.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-input-label for="password" value="Password" class="text-sm font-semibold" />
            <input id="password" type="password" name="password" placeholder="Enter your password"
                   required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
        </div>

        <button type="submit">{{ __('Confirm') }}</button>
    </form>
</x-guest-layout>
