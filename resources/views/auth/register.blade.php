<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-bold uppercase tracking-[0.25em] text-blue-600">Create account</p>
        <h1 class="mt-2 text-3xl font-black text-slate-950">Join DiploNxt</h1>
        <p class="mt-2 text-sm text-slate-500">
            Register with your Brainster Next College email address.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Full name')" class="font-bold text-slate-700" />
            <x-text-input
                id="name"
                class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="Enter your name"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('College email')" class="font-bold text-slate-700" />
            <x-text-input
                id="email"
                class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
                placeholder="example@next.edu.mk"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="role" :value="__('Register as')" class="font-bold text-slate-700" />

            <select
                id="role"
                name="role"
                required
                class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="" disabled {{ old('role') ? '' : 'selected' }}>
                    Select role
                </option>
                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>
                    Student
                </option>
                <option value="professor" {{ old('role') === 'professor' ? 'selected' : '' }}>
                    Professor / Mentor
                </option>
            </select>

            <x-input-error :messages="$errors->get('role')" class="mt-2" />

            <div class="mt-3 rounded-2xl border border-blue-100 bg-blue-50 p-3 text-xs leading-5 text-slate-600">
                Student accounts are approved immediately. Professor accounts require administrator approval.
            </div>
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="font-bold text-slate-700" />
            <x-text-input
                id="password"
                class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Enter your password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm password')" class="font-bold text-slate-700" />
            <x-text-input
                id="password_confirmation"
                class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Repeat your password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button
            type="submit"
            class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-violet-600 px-5 py-3 font-black text-white shadow-lg shadow-blue-500/25 transition hover:scale-[1.01]"
        >
            {{ __('Register') }}
        </button>

        <p class="text-center text-sm text-slate-500">
            Already registered?
            <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-violet-600">
                Sign in
            </a>
        </p>
    </form>
</x-guest-layout>