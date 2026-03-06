@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between flex-wrap gap-4">
    <div>
        <a href="{{ route('financials.index') }}" class="text-sm text-primary hover:underline flex items-center gap-1 mb-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Financial Reports</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Detailed analytics and P&L breakdowns</p>
    </div>

    <form method="GET" class="flex gap-2 flex-wrap">
        <select name="year" onchange="this.form.submit()" class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-4 py-2 text-sm dark:text-white">
            @for($y = now()->year - 2; $y <= now()->year; $y++)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <select name="month" onchange="this.form.submit()" class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-4 py-2 text-sm dark:text-white">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(2000, $m, 1)->format('F') }}</option>
            @endfor
        </select>
    </form>
</div>

{{-- MONTHLY P&L --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Revenue</h3>
        <p class="text-4xl font-black text-emerald-600">${{ number_format($monthlyRevenue, 2) }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</p>
    </div>

    <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Expenses</h3>
        <p class="text-4xl font-black text-red-600">${{ number_format($monthlyExpenses, 2) }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</p>
    </div>

    <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Net Profit</h3>
        <p class="text-4xl font-black {{ $monthlyProfit >= 0 ? 'text-primary' : 'text-red-600' }}">
            {{ $monthlyProfit >= 0 ? '+' : '' }}${{ number_format($monthlyProfit, 2) }}
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ number_format($monthlyRevenue > 0 ? ($monthlyProfit / $monthlyRevenue * 100) : 0, 1) }}% margin</p>
    </div>
</div>

{{-- YEARLY P&L TABLE --}}
<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
        <h3 class="font-bold text-gray-900 dark:text-white">{{ $year }} Monthly Breakdown</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/80 dark:bg-gray-800/50 uppercase font-bold text-gray-700 dark:text-gray-300 text-xs">
                <tr>
                    <th class="px-6 py-4 text-left">Month</th>
                    <th class="px-6 py-4 text-right">Revenue</th>
                    <th class="px-6 py-4 text-right">Expenses</th>
                    <th class="px-6 py-4 text-right">Profit</th>
                    <th class="px-6 py-4 text-right">Margin %</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($revenueTrend as $month_data)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $month_data['month'] }}</td>
                    <td class="px-6 py-4 text-right font-semibold text-emerald-600">${{ number_format($month_data['revenue'], 2) }}</td>
                    <td class="px-6 py-4 text-right font-semibold text-red-600">${{ number_format($month_data['expenses'], 2) }}</td>
                    <td class="px-6 py-4 text-right font-semibold {{ ($month_data['revenue'] - $month_data['expenses']) >= 0 ? 'text-primary' : 'text-red-600' }}">
                        ${{ number_format($month_data['revenue'] - $month_data['expenses'], 2) }}
                    </td>
                    <td class="px-6 py-4 text-right text-sm text-gray-500 dark:text-gray-400">
                        {{ number_format($month_data['revenue'] > 0 ? (($month_data['revenue'] - $month_data['expenses']) / $month_data['revenue'] * 100) : 0, 1) }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- REVENUE BY DOCTOR --}}
@if($revenueByDoctor->count())
<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
        <h3 class="font-bold text-gray-900 dark:text-white">Revenue by Doctor</h3>
    </div>
    <div class="p-6 space-y-3">
        @foreach($revenueByDoctor as $doctor => $revenue)
        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
            <p class="font-semibold text-gray-900 dark:text-white">{{ $doctor }}</p>
            <p class="font-bold text-emerald-600">${{ number_format($revenue, 2) }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- OUTSTANDING BALANCE AGING --}}
<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
        <h3 class="font-bold text-gray-900 dark:text-white">Outstanding Balance Aging</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6">
        <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-200 dark:border-emerald-900">
            <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase mb-1">0-30 Days</p>
            <p class="text-2xl font-black text-emerald-700 dark:text-emerald-300">${{ number_format($aging['0-30'], 2) }}</p>
        </div>
        <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-900">
            <p class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase mb-1">31-60 Days</p>
            <p class="text-2xl font-black text-amber-700 dark:text-amber-300">${{ number_format($aging['31-60'], 2) }}</p>
        </div>
        <div class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-900">
            <p class="text-xs font-bold text-orange-600 dark:text-orange-400 uppercase mb-1">61-90 Days</p>
            <p class="text-2xl font-black text-orange-700 dark:text-orange-300">${{ number_format($aging['61-90'], 2) }}</p>
        </div>
        <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-900">
            <p class="text-xs font-bold text-red-600 dark:text-red-400 uppercase mb-1">90+ Days</p>
            <p class="text-2xl font-black text-red-700 dark:text-red-300">${{ number_format($aging['90+'], 2) }}</p>
        </div>
    </div>
</div>

{{-- EXPENSE BREAKDOWN --}}
@if($expenseByCategory)
<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
        <h3 class="font-bold text-gray-900 dark:text-white">Expense Breakdown by Category</h3>
    </div>
    <div class="p-6 space-y-3">
        @php $totalExpense = array_sum($expenseByCategory) @endphp
        @foreach($expenseByCategory as $category => $amount)
        <div>
            <div class="flex justify-between items-center mb-1">
                <p class="font-semibold text-gray-900 dark:text-white capitalize">{{ str_replace('_', ' ', $category) }}</p>
                <p class="font-bold text-gray-900 dark:text-white">${{ number_format($amount, 2) }}</p>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div class="bg-primary rounded-full h-2" style="width: {{ ($amount / max($totalExpense, 1) * 100) }}%"></div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ number_format($amount / max($totalExpense, 1) * 100, 1) }}% of total</p>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
