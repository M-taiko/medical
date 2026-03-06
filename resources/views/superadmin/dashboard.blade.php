@extends('layouts.superadmin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Platform Overview</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ now()->format('l, F j, Y') }}</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        @php
            $cards = [
                ['label' => 'Total Clinics',       'value' => $totalClinics,   'icon' => 'building-2',    'color' => 'from-blue-500 to-blue-600'],
                ['label' => 'Active Subscriptions','value' => $activeSubs,     'icon' => 'check-circle',  'color' => 'from-green-500 to-green-600'],
                ['label' => 'Expired',             'value' => $expiredSubs,    'icon' => 'alert-circle',  'color' => 'from-red-500 to-red-600'],
                ['label' => 'Upcoming Renewals',   'value' => $upcomingRenewals->count(), 'icon' => 'clock', 'color' => 'from-amber-500 to-amber-600'],
            ];
        @endphp
        @foreach($cards as $card)
        <div class="glass rounded-2xl p-5 shadow-sm dark:bg-gray-800/50">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $card['label'] }}</p>
                <div class="p-2.5 rounded-xl bg-gradient-to-br {{ $card['color'] }} text-white">
                    <i data-lucide="{{ $card['icon'] }}" class="w-5 h-5"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $card['value'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- Revenue Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="glass rounded-2xl p-5 shadow-sm dark:bg-gray-800/50">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Monthly Revenue</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">${{ number_format($monthlyRevenue, 2) }}</p>
        </div>
        <div class="glass rounded-2xl p-5 shadow-sm dark:bg-gray-800/50">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Monthly Expenses</p>
            <p class="text-2xl font-bold text-red-500 dark:text-red-400">${{ number_format($monthlyExpenses, 2) }}</p>
        </div>
        <div class="glass rounded-2xl p-5 shadow-sm dark:bg-gray-800/50">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Net Profit</p>
            <p class="text-2xl font-bold {{ $monthlyProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">${{ number_format($monthlyProfit, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upcoming Renewals -->
        <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="clock" class="w-5 h-5 text-amber-500"></i> Expiring in 7 Days
            </h2>
            @forelse($upcomingRenewals as $sub)
            <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white text-sm">{{ $sub->clinic->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Expires {{ $sub->subscription_end_date->format('M d, Y') }}</p>
                </div>
                <a href="{{ route('superadmin.subscriptions.renew', $sub->clinic) }}" class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold hover:bg-amber-200 transition">Renew</a>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No upcoming renewals</p>
            @endforelse
        </div>

        <!-- Recent Clinics -->
        <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i data-lucide="building-2" class="w-5 h-5 text-primary"></i> Recent Clinics
                </h2>
                <a href="{{ route('superadmin.clinics.index') }}" class="text-xs text-primary hover:underline">View all</a>
            </div>
            @foreach($recentClinics as $clinic)
            <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white text-sm">{{ $clinic->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $clinic->latestSubscription ? $clinic->latestSubscription->plan->name : 'No plan' }}
                    </p>
                </div>
                @php $status = $clinic->subscription_status; @endphp
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                    {{ $status === 'active' ? 'bg-green-100 text-green-700' :
                       ($status === 'expired' ? 'bg-red-100 text-red-700' :
                       ($status === 'suspended' ? 'bg-gray-100 text-gray-600' : 'bg-blue-100 text-blue-700')) }}">
                    {{ ucfirst($status) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
        <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Quick Actions</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('superadmin.clinics.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary/90 transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Clinic
            </a>
            <a href="{{ route('superadmin.plans.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-secondary text-white rounded-xl text-sm font-semibold hover:bg-secondary/90 transition">
                <i data-lucide="package" class="w-4 h-4"></i> Create Plan
            </a>
            <a href="{{ route('superadmin.accounting.expenses') }}" class="flex items-center gap-2 px-4 py-2.5 bg-red-500 text-white rounded-xl text-sm font-semibold hover:bg-red-600 transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Log Expense
            </a>
            <a href="{{ route('superadmin.accounting.reports') }}" class="flex items-center gap-2 px-4 py-2.5 bg-gray-700 text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition">
                <i data-lucide="file-bar-chart" class="w-4 h-4"></i> Reports
            </a>
        </div>
    </div>
</div>
@endsection
