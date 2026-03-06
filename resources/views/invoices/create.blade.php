@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('appointments.index') }}" class="text-sm text-primary hover:underline flex items-center gap-1 mb-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Create Invoice</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Patient: {{ $visit->patient->first_name }} {{ $visit->patient->last_name }}</p>
    </div>
</div>

<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-8 max-w-3xl">
    <form action="{{ route('invoices.store', $visit) }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Line Items</h3>
            <div class="space-y-2 mb-4">
                @forelse($lineItems as $item)
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item['description'] }}</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">${{ number_format($item['amount'], 2) }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">No treatments recorded for this visit yet.</p>
                @endforelse
            </div>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-800 pt-4 space-y-3">
            <div class="flex justify-between">
                <span class="font-semibold text-gray-900 dark:text-white">Subtotal</span>
                <span class="font-bold text-gray-900 dark:text-white">${{ number_format($suggestedTotal, 2) }}</span>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Discount ($)</label>
                <input type="number" name="discount" step="0.01" min="0" value="{{ old('discount', 0) }}"
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>

            <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-800">
                <span class="font-bold text-gray-900 dark:text-white">Total Amount</span>
                <input type="number" name="total_amount" step="0.01" required min="0.01" value="{{ old('total_amount', $suggestedTotal) }}"
                       class="w-32 text-right font-bold bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Amount Paid ($)</label>
                <input type="number" name="paid_amount" step="0.01" min="0" value="{{ old('paid_amount', 0) }}"
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
            <textarea name="notes" rows="2"
                      class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white"></textarea>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i> Create Invoice
            </button>
            <a href="{{ route('appointments.index') }}" class="px-8 py-3 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
