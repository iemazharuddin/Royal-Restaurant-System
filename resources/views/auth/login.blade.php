<x-guest-layout>
    <div class="mb-2 text-center">
        <h1 class="text-sm font-bold text-white" style="font-family: 'Playfair Display', serif;"> Welcome Back </h1>
        <p class="mt-0.5 text-[9px] text-gray-400"> Sign in to your account</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-2">
            <label for="email" class="mb-0.5 block text-[9px] font-medium text-gray-200">Email Address </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"required autofocus autocomplete="username"  class="w-full rounded-lg border border-white/15 bg-[#252525] px-2 py-1.5 text-[10px] text-white outline-none focus:border-[#d4af37]" >
            @error('email')
                <p class="mt-0.5 text-[8px] text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-2">
            <label for="password" class="mb-0.5 block text-[9px] font-medium text-gray-200"> Password  </label>
            <input id="password"type="password" name="password" placeholder="Enter your password" required autocomplete="current-password"  class="w-full rounded-lg border border-white/15 bg-[#252525] px-2 py-1.5 text-[10px] text-white outline-none focus:border-[#d4af37]" >

            @error('password')
                <p class="mt-0.5 text-[8px] text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-2 flex items-center justify-between gap-2">
            <label for="remember_me" class="flex items-center gap-1 text-[8px] text-gray-300">
                <input id="remember_me" type="checkbox" name="remember" class="h-2.5 w-2.5 rounded border-gray-500" >  Remember me </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-[8px]"> Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="flex min-h-[28px] w-full items-center justify-center rounded-full bg-[#d4af37] px-3 py-1.5 text-[10px] font-bold text-black transition hover:bg-[#f0cf65]" >
            <i class="fas fa-sign-in-alt mr-1"></i> Sign In </button>
    </form>

    <div class="my-2 flex items-center gap-2">
        <div class="h-px flex-1 bg-white/15"></div>
        <span class="text-[8px] text-gray-400">OR</span>
        <div class="h-px flex-1 bg-white/15"></div>
    </div>

    <button type="button" id="firebase-google-login"  class="google-button flex min-h-[28px] w-full items-center justify-center gap-1 rounded-full border border-white/20 bg-[#252525] text-[9px] font-semibold text-white transition hover:border-[#d4af37] hover:bg-[#353535]" >
        <svg width="12" height="12" viewBox="0 0 48 48" aria-hidden="true">
            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.76 7.18l7.73 6C44.42 38.07 46.98 31.91 46.98 24.55z"/>
            <path fill="#FBBC05" d="M10.53 28.59A14.4 14.4 0 0 1 9.75 24c0-1.59.27-3.13.76-4.59l-7.98-6.19A23.9 23.9 0 0 0 0 24c0 3.87.93 7.54 2.56 10.78l7.97-6.19z"/>
            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.91-5.8l-7.73-6c-2.14 1.45-4.89 2.3-8.18 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 43.62 14.62 48 24 48z"/>
        </svg>

        <span>Continue with Google</span>
    </button>

    <div class="mt-2 border-t border-white/10 pt-2 text-center">
        <p class="text-[8px] text-gray-400"> Don't have an account?
            <a href="{{ route('register') }}" class="ml-1 font-semibold text-[#e3c253] hover:text-[#ffe3a3]">  Create Account </a>
        </p>
    </div>
</x-guest-layout>