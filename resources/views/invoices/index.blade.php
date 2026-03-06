@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">All Invoices</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Track all patient invoices and payments</p>
    </div>
    <a href="{{ route('appointments.index') }}" class="px-4 py-2 bg-primary text-white rounded-xl font-semibold hover:bg-primary/90 transition flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> New Invoice
    </a>
</div>

<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50/80 dark:bg-gray-800/50 uppercase font-bold text-gray-700 dark:text-gray-300 text-xs">
                <tr>
                    <th scope="col" class="px-6 py-4">Invoice #</th>
                    <th scope="col" class="px-6 py-4">Patient</th>
                    <th scope="col" class="px-6 py-4">Date</th>
                    <th scope="col" class="px-6 py-4">Amount</th>
                    <th scope="col" class="px-6 py-4">Paid</th>
                    <th scope="col" class="px-6 py-4">Balance</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($invoices as $inv)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">#{{ $inv->id }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('patients.show', $inv->patient) }}" class="font-semibold text-gray-900 dark:text-white hover:text-primary transition">
                            {{ $inv->patient->first_name }} {{ $inv->patient->last_name }}
                        </a>
                    </td>
                    <td class="px-6 py-4">{{ $inv->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">${{ number_format($inv->total_amount, 2) }}</td>
                    <td class="px-6 py-4 font-semibold text-emerald-600">${{ number_format($inv->paid_amount, 2) }}</td>
                    <td class="px-6 py-4 font-semibold {{ $inv->remaining_balance > 0 ? 'text-orange-600' : 'text-emerald-600' }}">
                        ${{ number_format($inv->remaining_balance, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        @if($inv->status === 'paid')
                            <span class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 px-2.5 py-1 rounded-full text-xs font-bold">Paid</span>
                        @elseif($inv->status === 'partial')
                            <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 px-2.5 py-1 rounded-full text-xs font-bold">Partial</span>
                        @else
                            <span class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-2.5 py-1 rounded-full text-xs font-bold">Unpaid</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('invoices.show', $inv) }}" class="text-primary hover:underline text-xs font-semibold">View</a>
                        <a href="{{ route('invoices.pdf', $inv) }}" class="text-red-600 hover:underline text-xs font-semibold">PDF</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <p class="text-gray-400">No invoices yet. Create one from a completed visit.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($invoices->hasPages())
    <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
        {{ $invoices->links() }}
    </div>
    @endif
</div>
@endsection
