@extends('layouts.superadmin')

@section('content')
<div class="space-y-6">
    <!-- Header + Month Filter -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Platform Accounting</h1>
        <form method="GET" class="flex items-center gap-2">
            <select name="month" class="px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-sm focus:ring-2 focus:ring-primary outline-none">
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m)->format('F') }}</option>
                @endfor
            </select>
            <input type="number" name="year" value="{{ $year }}" min="2020" max="{{ now()->year + 1 }}"
                   class="w-24 px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-sm focus:ring-2 focus:ring-primary outline-none">
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary/90 transition">Filter</button>
        </form>
    </div>

    <!-- KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
            <p class="text-sm text-gray-400 mb-1">Monthly Revenue</p>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">${{ number_format($revenue, 2) }}</p>
        </div>
        <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
            <p class="text-sm text-gray-400 mb-1">Monthly Expenses</p>
            <p class="text-3xl font-bold text-red-500 dark:text-red-400">${{ number_format($expenses, 2) }}</p>
        </div>
        <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
            <p class="text-sm text-gray-400 mb-1">Net Profit</p>
            <p class="text-3xl font-bold {{ $profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500' }}">${{ number_format($profit, 2) }}</p>
        </div>
    </div>

    <!-- Yearly Summary Table -->
    <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
        <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">{{ $year }} Monthly Breakdown</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="pb-3 text-left font-semibold text-gray-500">Month</th>
                        <th class="pb-3 text-right font-semibold text-green-600">Revenue</th>
                        <th class="pb-3 text-right font-semibold text-red-500">Expenses</th>
                        <th class="pb-3 text-right font-semibold text-gray-600 dark:text-gray-300">Profit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($yearlyReport as $row)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition">
                        <td class="py-3 font-medium text-gray-700 dark:text-gray-300">{{ $row['month'] }}</td>
                        <td class="py-3 text-right text-green-600">${{ number_format($row['revenue'], 2) }}</td>
                        <td class="py-3 text-right text-red-500">${{ number_format($row['expenses'], 2) }}</td>
                        <td class="py-3 text-right font-semibold {{ $row['profit'] >= 0 ? 'text-gray-800 dark:text-white' : 'text-red-500' }}">${{ number_format($row['profit'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Expenses Breakdown -->
    @if(!empty($expensesBreakdown))
    <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
        <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Expenses by Category</h2>
        <div class="space-y-3">
            @foreach($expensesBreakdown as $category => $total)
            <div class="flex items-center justify-between text-sm">
                <span class="font-medium text-gray-700 dark:text-gray-300 capitalize">{{ str_replace('_', ' ', $category) }}</span>
                <span class="font-bold text-red-500">${{ number_format($total, 2) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Quick Links -->
    <div class="flex gap-3">
        <a href="{{ route('superadmin.accounting.income') }}" class="px-4 py-2.5 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition flex items-center gap-2">
            <i data-lucide="trending-up" class="w-4 h-4"></i> View Income
        </a>
        <a href="{{ route('superadmin.accounting.expenses') }}" class="px-4 py-2.5 bg-red-500 text-white rounded-xl text-sm font-semibold hover:bg-red-600 transition flex items-center gap-2">
            <i data-lucide="trending-down" class="w-4 h-4"></i> Log Expense
        </a>
        <a href="{{ route('superadmin.accounting.reports') }}" class="px-4 py-2.5 bg-gray-700 text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition flex items-center gap-2">
            <i data-lucide="file-bar-chart" class="w-4 h-4"></i> Full Reports
        </a>
    </div>
</div>
@endsection
