@extends('layouts.superadmin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">All Subscriptions</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $subscriptions->total() }} records</p>
    </div>

    <div class="glass rounded-2xl shadow-sm dark:bg-gray-800/50 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Clinic</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Plan</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Start</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">End</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Paid</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($subscriptions as $sub)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                    <td class="px-6 py-4 font-semibold text-gray-800 dark:text-white">
                        <a href="{{ route('superadmin.clinics.show', $sub->clinic) }}" class="hover:text-primary">{{ $sub->clinic->name }}</a>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $sub->plan->name }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $sub->subscription_start_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $sub->subscription_end_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $sub->subscription_status === 'active' ? 'bg-green-100 text-green-700' :
                               ($sub->subscription_status === 'expired' ? 'bg-red-100 text-red-700' :
                               ($sub->subscription_status === 'suspended' ? 'bg-gray-200 text-gray-600' : 'bg-blue-100 text-blue-700')) }}">
                            {{ ucfirst($sub->subscription_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-700 dark:text-gray-300">${{ number_format($sub->price_paid, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No subscriptions found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $subscriptions->links() }}</div>
    </div>
</div>
@endsection
