<x-app-layout>
    <div class="max-w-6xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">Pending Professor Approvals</h1>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-xl p-6">
            @forelse($users as $user)
                <div class="flex items-center justify-between border-b py-4">
                    <div>
                        <p class="font-bold">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>

                    <div class="flex gap-3">
                        <form method="POST" action="{{ route('admin.approvals.approve', $user) }}">
                            @csrf
                            @method('PATCH')
                            <button class="px-4 py-2 bg-green-600 text-white rounded">
                                Approve
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.approvals.reject', $user) }}">
                            @csrf
                            @method('PATCH')
                            <button class="px-4 py-2 bg-red-600 text-white rounded">
                                Reject
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p>No pending professor approvals.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>