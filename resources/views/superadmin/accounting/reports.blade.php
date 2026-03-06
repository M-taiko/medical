@extends('layouts.superadmin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Annual Report — {{ $year }}</h1>
        <form method="GET" class="flex items-center gap-2">
            <input type="number" name="year" value="{{ $year }}" min="2020" max="{{ now()->year + 1 }}"
                   class="w-24 px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-sm focus:ring-2 focus:ring-primary outline-none">
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary/90 transition">Go</button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
            <p class="text-sm text-gray-400 mb-1">Total Revenue {{ $year }}</p>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">${{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
            <p class="text-sm text-gray-400 mb-1">Total Expenses {{ $year }}</p>
            <p class="text-3xl font-bold text-red-500">${{ number_format($totalExpenses, 2) }}</p>
        </div>
        <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
            <p class="text-sm text-gray-400 mb-1">Net Profit {{ $year }}</p>
            <p class="text-3xl font-bold {{ $totalProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500' }}">${{ number_format($totalProfit, 2) }}</p>
        </div>
    </div>

    <!-- Monthly Table -->
    <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50">
        <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Monthly Breakdown</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="pb-3 text-left font-semibold text-gray-500">Month</th>
                        <th class="pb-3 text-right font-semibold text-green-600">Revenue</th>
                        <th class="pb-3 text-right font-semibold text-red-500">Expenses</th>
                        <th class="pb-3 text-right font-semibold text-gray-600">Profit</th>
                        <th class="pb-3 text-right font-semibold text-gray-400">Margin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($yearlyReport as $row)
                    @php $margin = $row['revenue'] > 0 ? round(($row['profit'] / $row['revenue']) * 100, 1) : 0; @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition">
                        <td class="py-3 font-semibold text-gray-800 dark:text-white">{{ $row['month'] }}</td>
                        <td class="py-3 text-right text-green-600">${{ number_format($row['revenue'], 2) }}</td>
                        <td class="py-3 text-right text-red-500">${{ number_format($row['expenses'], 2) }}</td>
                        <td class="py-3 text-right font-bold {{ $row['profit'] >= 0 ? 'text-gray-800 dark:text-white' : 'text-red-500' }}">${{ number_format($row['profit'], 2) }}</td>
                        <td class="py-3 text-right text-gray-400 text-xs">{{ $margin }}%</td>
                    </tr>
                    @endforeach
                    <tr class="border-t-2 border-gray-300 dark:border-gray-600 font-bold">
                        <td class="py-3 text-gray-800 dark:text-white">TOTAL</td>
                        <td class="py-3 text-right text-green-600">${{ number_format($totalRevenue, 2) }}</td>
                        <td class="py-3 text-right text-red-500">${{ number_format($totalExpenses, 2) }}</td>
                        <td class="py-3 text-right {{ $totalProfit >= 0 ? 'text-gray-800 dark:text-white' : 'text-red-500' }}">${{ number_format($totalProfit, 2) }}</td>
                        <td class="py-3"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
