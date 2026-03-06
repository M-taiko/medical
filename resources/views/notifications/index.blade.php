@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
        @if($notifications->where('read_at', null)->count() > 0)
        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            <button class="text-sm text-primary hover:underline">Mark all as read</button>
        </form>
        @endif
    </div>

    <div class="space-y-3">
        @forelse($notifications as $notification)
        @php $data = $notification->data; $isRead = !is_null($notification->read_at); @endphp
        <div class="glass rounded-2xl p-5 shadow-sm dark:bg-gray-800/50 flex items-start gap-4 {{ !$isRead ? 'border-l-4 border-primary' : '' }}">
            <div class="p-2.5 rounded-full {{ $data['type'] === 'subscription_expired' ? 'bg-red-100 dark:bg-red-900/30' : 'bg-amber-100 dark:bg-amber-900/30' }} shrink-0">
                <i data-lucide="{{ $data['type'] === 'subscription_expired' ? 'alert-octagon' : 'clock' }}" class="w-5 h-5 {{ $data['type'] === 'subscription_expired' ? 'text-red-600' : 'text-amber-600' }}"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-gray-800 dark:text-white text-sm">{{ $data['clinic_name'] ?? 'Subscription' }}</p>
                <p class="text-gray-600 dark:text-gray-300 text-sm mt-0.5">{{ $data['message'] }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
            @if(!$isRead)
            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                @csrf
                <button class="text-xs text-gray-400 hover:text-primary transition">Mark read</button>
            </form>
            @endif
        </div>
        @empty
        <div class="text-center py-16 text-gray-400">
            <i data-lucide="bell-off" class="w-12 h-12 mx-auto mb-3 opacity-40"></i>
            <p>No notifications</p>
        </div>
        @endforelse
    </div>

    <div>{{ $notifications->links() }}</div>
</div>
@endsection
