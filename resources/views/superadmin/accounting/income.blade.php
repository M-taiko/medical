@extends('layouts.superadmin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Platform Income</h1>
        <a href="{{ route('superadmin.accounting.index') }}" class="text-sm text-primary hover:underline flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Accounting Overview
        </a>
    </div>

    <div class="glass rounded-2xl shadow-sm dark:bg-gray-800/50 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Clinic</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Plan</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($incomeRecords as $income)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $income->received_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800 dark:text-white">{{ $income->clinic->name }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $income->subscription?->plan?->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $income->description }}</td>
                    <td class="px-6 py-4 text-right font-bold text-green-600">${{ number_format($income->amount, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No income records.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $incomeRecords->links() }}</div>
    </div>
</div>
@endsection
