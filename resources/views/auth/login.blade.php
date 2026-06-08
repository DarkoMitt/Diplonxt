<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-bold uppercase tracking-[0.25em] text-blue-600">Welcome back</p>
        <h1 class="mt-2 text-3xl font-black text-slate-950">Sign in to DiploNxt</h1>
        <p class="mt-2 text-sm text-slate-500">
            Continue managing thesis submissions, feedback, and progress.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="font-bold text-slate-700" />
            <x-text-input
                id="email"
                class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="example@next.edu.mk"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="font-bold text-slate-700" />

                @if (Route::has('password.request'))
                    <a class="text-sm font-bold text-blue-600 hover:text-violet-600" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif
            </div>

            <x-text-input
                id="password"
                class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="flex items-center gap-2 text-sm text-slate-600">
            <input
                id="remember_me"
                type="checkbox"
                class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500"
                name="remember"
            >
            <span>{{ __('Remember me') }}</span>
        </label>

        <button
            type="submit"
            class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-violet-600 px-5 py-3 font-black text-white shadow-lg shadow-blue-500/25 transition hover:scale-[1.01]"
        >
            {{ __('Log in') }}
        </button>

        <p class="text-center text-sm text-slate-500">
            New to DiploNxt?
            <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-violet-600">
                Create account
            </a>
        </p>
    </form>
</x-guest-layout>