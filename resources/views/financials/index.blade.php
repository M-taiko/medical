@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __('financials.title') }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ __('financials.subtitle') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('financials.reports') }}" class="bg-indigo-100 hover:bg-indigo-200 text-indigo-800 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 dark:text-indigo-300 px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-sm transition-all">
            <i data-lucide="chart-bar" class="w-4 h-4"></i> Reports
        </a>
        <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-sm transition-all" onclick="document.getElementById('expense-form').classList.toggle('hidden')">
            <i data-lucide="receipt" class="w-4 h-4"></i> {{ __('financials.record_expense') }}
        </button>
        <a href="{{ route('appointments.index') }}" class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg hover:shadow-emerald-500/30 transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i> {{ __('financials.new_invoice') }}
        </a>
    </div>
</div>

<!-- Financial Summary grid -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="glass p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('financials.total_revenue') }}</p>
        <h3 class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($totalRevenue, 2) }}</h3>
    </div>
    <div class="glass p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('financials.total_expenses') }}</p>
        <h3 class="text-3xl font-bold text-red-500">${{ number_format($totalExpenses, 2) }}</h3>
    </div>
    <div class="glass p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('financials.net_profit') }}</p>
        <h3 class="text-3xl font-bold {{ $netProfit >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
            ${{ number_format($netProfit, 2) }}
        </h3>
    </div>
    <div class="glass p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('financials.outstanding') }}</p>
        <h3 class="text-3xl font-bold text-orange-500">${{ number_format($outstanding, 2) }}</h3>
    </div>
</div>

<!-- Expense Recording Form -->
<div id="expense-form" class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden mb-6 hidden">
    <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-amber-50/30 dark:bg-amber-900/10">
        <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <i data-lucide="receipt" class="w-4 h-4 text-amber-600"></i> Record Clinic Expense
        </h3>
    </div>
    <form action="{{ route('expenses.store') }}" method="POST" class="p-6 space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Category *</label>
                <select name="category" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary outline-none dark:text-white text-sm">
                    <option value="">Select</option>
                    <option value="rent">Rent</option>
                    <option value="salaries">Salaries</option>
                    <option value="supplies">Supplies</option>
                    <option value="utilities">Utilities</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Amount ($) *</label>
                <input type="number" name="amount" step="0.01" min="0.01" required
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary outline-none dark:text-white text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Date *</label>
                <input type="date" name="expense_date" value="{{ today()->format('Y-m-d') }}" required
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary outline-none dark:text-white text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Description</label>
                <input type="text" name="description" placeholder="Optional notes"
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary outline-none dark:text-white text-sm">
            </div>
        </div>
        <div class="flex gap-2 justify-end">
            <button type="button" class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition" onclick="document.getElementById('expense-form').classList.add('hidden')">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm font-semibold hover:bg-amber-700 transition">Record Expense</button>
        </div>
    </form>
</div>

<!-- Recent Expenses -->
@if($expenses->count())
<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
        <h3 class="font-bold text-gray-900 dark:text-white">Recent Expenses</h3>
    </div>
    <div class="divide-y divide-gray-100 dark:divide-gray-800">
        @foreach($expenses as $exp)
        <div class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
            <div class="flex-1">
                <p class="font-semibold text-gray-900 dark:text-white text-sm capitalize">{{ str_replace('_', ' ', $exp->category) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $exp->expense_date->format('M d, Y') }}{{ $exp->description ? ' • ' . $exp->description : '' }}</p>
            </div>
            <p class="font-bold text-red-600">${{ number_format($exp->amount, 2) }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Invoices Display -->
<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
        <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ __('financials.recent_invoices') }}</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50/80 dark:bg-gray-800/50 uppercase font-bold text-gray-700 dark:text-gray-300">
                <tr>
                    <th scope="col" class="px-6 py-4">{{ __('financials.invoice_id') }}</th>
                    <th scope="col" class="px-6 py-4">{{ __('financials.date') }}</th>
                    <th scope="col" class="px-6 py-4">{{ __('financials.patient') }}</th>
                    <th scope="col" class="px-6 py-4">{{ __('financials.total') }}</th>
                    <th scope="col" class="px-6 py-4">{{ __('financials.remaining') }}</th>
                    <th scope="col" class="px-6 py-4">{{ __('financials.status') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($invoices as $inv)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">#INV-{{ str_pad($inv->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4">{{ $inv->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">{{ $inv->patient->first_name }} {{ $inv->patient->last_name }}</td>
                    <td class="px-6 py-4 font-medium">${{ number_format($inv->total_amount, 2) }}</td>
                    <td class="px-6 py-4 text-orange-500">${{ number_format($inv->remaining_balance, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $inv->status == 'paid' ? 'bg-emerald-100 text-emerald-800' : ($inv->status == 'partial' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($inv->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">{{ __('financials.no_invoices') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
