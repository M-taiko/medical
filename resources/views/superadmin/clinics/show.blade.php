@extends('layouts.superadmin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('superadmin.clinics.index') }}" class="text-sm text-primary hover:underline flex items-center gap-1 mb-1">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $clinic->name }}</h1>
            <p class="text-sm text-gray-500">{{ $clinic->email }} · {{ $clinic->phone }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('superadmin.clinics.edit', $clinic) }}" class="px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition">Edit</a>
            @if($activeSubscription)
                <a href="{{ route('superadmin.subscriptions.renew', $clinic) }}" class="px-4 py-2 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition">Renew</a>
            @else
                <a href="{{ route('superadmin.subscriptions.assign', $clinic) }}" class="px-4 py-2 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary/90 transition">Assign Plan</a>
            @endif
            @if($clinic->subscription_status !== 'suspended')
            <form method="POST" action="{{ route('superadmin.subscriptions.suspend', $clinic) }}" onsubmit="return confirm('Suspend this clinic?')">
                @csrf
                <button class="px-4 py-2 bg-red-500 text-white rounded-xl text-sm font-semibold hover:bg-red-600 transition">Suspend</button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Subscription Card -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Active Subscription -->
            <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-5 h-5 text-primary"></i> Current Subscription
                </h2>
                @if($activeSubscription)
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-400">Plan</span><p class="font-semibold text-gray-800 dark:text-white mt-0.5">{{ $activeSubscription->plan->name }}</p></div>
                    <div><span class="text-gray-400">Status</span>
                        <p class="font-semibold text-green-600 mt-0.5">Active</p>
                    </div>
                    <div><span class="text-gray-400">Started</span><p class="font-semibold text-gray-800 dark:text-white mt-0.5">{{ $activeSubscription->subscription_start_date->format('M d, Y') }}</p></div>
                    <div><span class="text-gray-400">Expires</span><p class="font-semibold text-gray-800 dark:text-white mt-0.5">{{ $activeSubscription->subscription_end_date->format('M d, Y') }}</p></div>
                    <div><span class="text-gray-400">Days Remaining</span><p class="font-bold text-2xl text-primary mt-0.5">{{ $activeSubscription->daysRemaining() }}</p></div>
                    <div><span class="text-gray-400">Price Paid</span><p class="font-semibold text-gray-800 dark:text-white mt-0.5">${{ number_format($activeSubscription->price_paid, 2) }}</p></div>
                </div>
                @else
                <div class="text-center py-6 text-gray-400">
                    <i data-lucide="alert-circle" class="w-10 h-10 mx-auto mb-2 text-red-400"></i>
                    <p>No active subscription</p>
                    <a href="{{ route('superadmin.subscriptions.assign', $clinic) }}" class="text-primary text-sm hover:underline">Assign a plan →</a>
                </div>
                @endif
            </div>

            <!-- Subscription History -->
            <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Subscription History</h2>
                <div class="space-y-3">
                    @forelse($clinic->subscriptions->sortByDesc('subscription_start_date') as $sub)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0 text-sm">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white">{{ $sub->plan->name }}</p>
                            <p class="text-xs text-gray-400">{{ $sub->subscription_start_date->format('M d, Y') }} → {{ $sub->subscription_end_date->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $sub->subscription_status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($sub->subscription_status) }}
                            </span>
                            <p class="text-xs text-gray-400 mt-0.5">${{ number_format($sub->price_paid, 2) }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">No subscription history.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Stats -->
        <div class="space-y-4">
            <div class="glass rounded-2xl p-5 shadow-sm dark:bg-gray-800/50">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Total Patients</p>
                <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $clinic->patients->count() }}</p>
            </div>
            <div class="glass rounded-2xl p-5 shadow-sm dark:bg-gray-800/50">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Total Staff</p>
                <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $clinic->users->count() }}</p>
            </div>
            <div class="glass rounded-2xl p-5 shadow-sm dark:bg-gray-800/50">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Staff Members</h3>
                @foreach($clinic->users->take(6) as $user)
                <div class="flex items-center gap-2 py-1.5 text-sm">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-primary to-secondary text-white flex items-center justify-center text-xs font-bold shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white leading-none">{{ $user->name }}</p>
                        <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
