@extends('layouts.superadmin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Platform Expenses</h1>
    </div>

    <!-- Add Expense Form -->
    <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
        <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Log New Expense</h2>
        <form action="{{ route('superadmin.accounting.expenses.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase">Category</label>
                <select name="category" required class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-sm focus:ring-2 focus:ring-primary outline-none">
                    @foreach($categories as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase">Amount ($)</label>
                <input type="number" step="0.01" name="amount" required min="0.01"
                       class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-sm focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase">Date</label>
                <input type="date" name="expense_date" value="{{ today()->toDateString() }}" required
                       class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-sm focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase">Description</label>
                <div class="flex gap-2">
                    <input type="text" name="description" placeholder="e.g. AWS bill for March"
                           class="flex-1 px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-sm focus:ring-2 focus:ring-primary outline-none">
                    <button type="submit" class="px-5 py-2.5 bg-red-500 text-white rounded-xl text-sm font-semibold hover:bg-red-600 transition whitespace-nowrap">
                        <i data-lucide="plus" class="w-4 h-4 inline"></i> Log
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Expense Records -->
    <div class="glass rounded-2xl shadow-sm dark:bg-gray-800/50 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Logged By</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($expenseRecords as $expense)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $expense->expense_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg text-xs font-semibold capitalize">{{ str_replace('_', ' ', $expense->category) }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $expense->description ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $expense->creator->name }}</td>
                    <td class="px-6 py-4 text-right font-bold text-red-500">${{ number_format($expense->amount, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No expenses logged.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $expenseRecords->links() }}</div>
    </div>
</div>
@endsection
