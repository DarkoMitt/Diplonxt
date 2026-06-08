<x-app-layout>
    <div class="mb-8">
        <p class="text-sm font-bold text-blue-600">ADMINISTRATION</p>
        <h1 class="text-3xl font-black">College overview</h1>
        <p class="mt-2 text-slate-500">
            A real-time view of the complete thesis lifecycle.
        </p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <x-metric-card label="Total students" :value="$totalStudents" />
        <x-metric-card label="Professors" :value="$totalProfessors" color="violet" />
        <x-metric-card label="Active theses" :value="$activeTheses" color="emerald" />
        <x-metric-card label="Pending approvals" :value="$pendingApprovals" color="amber" />
        <x-metric-card label="Completed" :value="$completedTheses" color="emerald" />
        <x-metric-card label="Upcoming defenses" :value="$upcomingDefenses" color="violet" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <section class="card xl:col-span-2">
            <div class="flex justify-between">
                <h2 class="section-title">Recent thesis activity</h2>
                <a class="font-bold text-blue-600" href="{{ route('admin.theses.index') }}">
                    Manage all
                </a>
            </div>

            <div class="mt-4 divide-y">
                @forelse($recentTheses as $thesis)
                    <a href="{{ route('theses.show', $thesis) }}" class="flex items-center gap-4 py-4">
                        <span class="grid size-10 place-items-center rounded-xl bg-blue-50 font-bold text-blue-700">
                            {{ str($thesis->student->name)->substr(0, 1) }}
                        </span>

                        <div class="min-w-0 flex-1">
                            <b class="block truncate">{{ $thesis->title }}</b>
                            <small class="text-slate-500">
                                {{ $thesis->student->name }}
                                ·
                                {{ $thesis->professor?->name ?? 'Unassigned' }}
                            </small>
                        </div>

                        <x-status-badge :status="$thesis->status" />
                    </a>
                @empty
                    <p class="py-6 text-sm text-slate-500">
                        No recent thesis activity.
                    </p>
                @endforelse
            </div>
        </section>

        <section class="card">
            <h2 class="section-title">Quick actions</h2>

            <div class="mt-4 grid gap-3">
                <a class="btn-secondary justify-start" href="{{ route('admin.users.index') }}">
                    ♙ Manage users
                </a>

                <a class="btn-secondary justify-start" href="{{ route('admin.approvals.index') }}">
                    ✓ Professor approvals
                </a>

                <a class="btn-secondary justify-start" href="{{ route('admin.theses.index') }}">
                    ▤ Assign mentors
                </a>

                <a class="btn-secondary justify-start" href="{{ route('admin.defenses.index') }}">
                    ◫ Schedule defense
                </a>
            </div>

            <h3 class="mt-8 font-bold">Status distribution</h3>

            <div class="mt-3 space-y-3">
                @forelse($statusBreakdown->take(5) as $item)
                    @php
                        $statusText = $item->status instanceof \App\Enums\ThesisStatus
                            ? $item->status->label()
                            : str((string) $item->status)->replace('_', ' ')->title();
                    @endphp

                    <div class="flex justify-between text-sm">
                        <span>{{ $statusText }}</span>
                        <b>{{ $item->total }}</b>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">
                        No status data available.
                    </p>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>