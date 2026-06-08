<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DiploNxt') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-950 text-white">
    <main class="min-h-screen bg-[radial-gradient(circle_at_top_left,#1d4ed8_0,transparent_35%),radial-gradient(circle_at_bottom_right,#7c3aed_0,transparent_30%),#020617]">
        <div class="min-h-screen grid lg:grid-cols-2">
            <section class="hidden lg:flex flex-col justify-between p-12">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="grid size-12 place-items-center rounded-2xl bg-gradient-to-br from-blue-500 to-violet-600 font-black">
                        D
                    </div>
                    <div>
                        <h1 class="text-2xl font-black">DiploNxt</h1>
                        <p class="text-sm text-slate-300">Thesis Management Portal</p>
                    </div>
                </a>

                <div class="max-w-xl">
                    <span class="inline-flex rounded-full border border-blue-400/30 bg-blue-500/10 px-4 py-2 text-sm font-bold text-blue-200">
                        Brainster Next College
                    </span>

                    <h2 class="mt-8 text-6xl font-black leading-tight">
                        Your thesis.<br>
                        <span class="bg-gradient-to-r from-blue-400 to-violet-400 bg-clip-text text-transparent">
                            Moving forward.
                        </span>
                    </h2>

                    <p class="mt-6 text-lg leading-8 text-slate-300">
                        One workspace for submissions, mentor feedback, version history,
                        communication, and defense planning.
                    </p>

                    <div class="mt-8 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                            <p class="text-3xl font-black">64%</p>
                            <p class="mt-1 text-sm text-slate-300">Average thesis progress visibility</p>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                            <p class="text-3xl font-black">3 roles</p>
                            <p class="mt-1 text-sm text-slate-300">Student, Professor, Admin</p>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-slate-400">
                    © {{ date('Y') }} DiploNxt. Built for smarter thesis workflows.
                </p>
            </section>

            <section class="flex min-h-screen items-center justify-center px-6 py-10">
                <div class="w-full max-w-md">
                    <div class="mb-8 flex items-center justify-center gap-3 lg:hidden">
                        <div class="grid size-12 place-items-center rounded-2xl bg-gradient-to-br from-blue-500 to-violet-600 font-black">
                            D
                        </div>
                        <div>
                            <h1 class="text-2xl font-black">DiploNxt</h1>
                            <p class="text-sm text-slate-300">Thesis workspace</p>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/95 p-8 text-slate-900 shadow-2xl backdrop-blur">
                        {{ $slot }}
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>