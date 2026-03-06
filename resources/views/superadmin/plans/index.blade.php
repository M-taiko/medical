@extends('layouts.superadmin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Subscription Plans</h1>
        <a href="{{ route('superadmin.plans.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary/90 transition">
            <i data-lucide="plus" class="w-4 h-4"></i> Create Plan
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($plans as $plan)
        <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50 relative">
            @if(!$plan->is_active)
            <span class="absolute top-4 right-4 px-2 py-0.5 bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-300 rounded-full text-xs">Inactive</span>
            @endif
            <div class="mb-4">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">{{ $plan->slug }}</p>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $plan->name }}</h2>
                <p class="text-3xl font-extrabold text-primary mt-2">${{ number_format($plan->price, 2) }}</p>
                <p class="text-xs text-gray-400">per {{ $plan->duration_months }} month(s)</p>
            </div>
            <div class="border-t border-gray-100 dark:border-gray-700 pt-4 mt-4 flex items-center justify-between text-sm">
                <span class="text-gray-500">{{ $plan->subscriptions_count }} subscriptions</span>
                <div class="flex gap-2">
                    <a href="{{ route('superadmin.plans.edit', $plan) }}" class="p-1.5 text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </a>
                    <form method="POST" action="{{ route('superadmin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?')">
                        @csrf @method('DELETE')
                        <button class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-gray-400">No plans yet. Create your first plan.</div>
        @endforelse
    </div>
</div>
@endsection
