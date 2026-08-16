<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notifications</h2>
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Mark all
                    read</button>
            </form>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="rounded-2xl border border-gray-200 bg-white divide-y divide-gray-100">
            @forelse ($notifications as $notification)
                <div class="flex items-start gap-4 px-5 py-4 {{ $notification->read_at ? '' : 'bg-indigo-50/50' }}">
                    <span
                        class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid {{ $notification->data['icon'] ?? 'fa-bell' }}"></i>
                    </span>

                    <div class="flex-1 min-w-0">
                        <a href="{{ $notification->data['url'] ?? '#' }}"
                            class="font-medium text-gray-800 hover:text-indigo-600">
                            {{ $notification->data['title'] ?? 'Notification' }}
                        </a>
                        <p class="text-sm text-gray-500">{{ $notification->data['body'] ?? '' }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>

                    @unless ($notification->read_at)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 whitespace-nowrap">
                                Mark read
                            </button>
                        </form>
                    @endunless
                </div>
            @empty
                <div class="px-5 py-16 text-center text-gray-400">No notifications yet.</div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</x-app-layout>
