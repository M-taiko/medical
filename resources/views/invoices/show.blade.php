@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between flex-wrap gap-4">
    <div>
        <a href="{{ route('financials.index') }}" class="text-sm text-primary hover:underline flex items-center gap-1 mb-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Financials</a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Invoice #{{ $invoice->id }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</p>
    </div>
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('invoices.pdf', $invoice) }}" class="px-4 py-2 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition flex items-center gap-2">
            <i data-lucide="file-pdf" class="w-4 h-4"></i> Download PDF
        </a>
        <a href="{{ route('financials.index') }}" class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition">Back</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- MAIN INVOICE --}}
    <div class="lg:col-span-2">
        <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-8 bg-gradient-to-r from-primary/5 to-secondary/5 dark:from-primary/10 dark:to-secondary/10">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">INVOICE</p>
                        <p class="text-4xl font-black text-gray-900 dark:text-white">#{{ $invoice->id }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Issue Date</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $invoice->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Patient</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $invoice->patient->phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Visit Date</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $invoice->visit->appointment_time->format('M d, Y') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Dr. {{ $invoice->visit->doctor->name }}</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4">Items</h3>
                <div class="space-y-2 mb-6">
                    @php $total = 0 @endphp
                    @foreach($invoice->visit->dentalCharts as $record)
                        @if($record->treatment && $record->price > 0)
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Tooth #{{ $record->tooth_number }} — {{ $record->treatment }}</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">${{ number_format($record->price, 2) }}</p>
                        </div>
                        @php $total += $record->price @endphp
                        @endif
                    @endforeach
                    @foreach($invoice->visit->treatmentRecords as $tr)
                        @if($tr->cost > 0)
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $tr->treatment_type }}</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">${{ number_format($tr->cost, 2) }}</p>
                        </div>
                        @php $total += $tr->cost @endphp
                        @endif
                    @endforeach
                </div>

                <div class="border-t border-gray-200 dark:border-gray-800 pt-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-900 dark:text-white">Total Amount</span>
                        <span class="text-lg font-black text-gray-900 dark:text-white">${{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-900 dark:text-white">Amount Paid</span>
                        <span class="text-lg font-black text-emerald-600">+ ${{ number_format($invoice->paid_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-800">
                        <span class="font-bold text-gray-900 dark:text-white">Outstanding Balance</span>
                        <span class="text-xl font-black {{ $invoice->remaining_balance > 0 ? 'text-orange-600' : 'text-emerald-600' }}">
                            ${{ number_format($invoice->remaining_balance, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SIDEBAR: STATUS & PAYMENTS --}}
    <div class="space-y-6">
        {{-- Status --}}
        <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-primary"></i> Status
            </h3>
            <div class="inline-block">
                @if($invoice->status === 'paid')
                    <span class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 px-3 py-1.5 rounded-full text-sm font-bold">Fully Paid</span>
                @elseif($invoice->status === 'partial')
                    <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 px-3 py-1.5 rounded-full text-sm font-bold">Partially Paid</span>
                @else
                    <span class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-3 py-1.5 rounded-full text-sm font-bold">Unpaid</span>
                @endif
            </div>
        </div>

        {{-- Record Payment --}}
        @if($invoice->remaining_balance > 0)
        <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Record Payment</h3>
            <form action="{{ route('payments.store', $invoice) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase block mb-1">Amount (up to ${{ number_format($invoice->remaining_balance, 2) }})</label>
                    <input type="number" name="amount" step="0.01" max="{{ $invoice->remaining_balance }}" required
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary outline-none transition dark:text-white text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase block mb-1">Method</label>
                    <select name="payment_method" required
                            class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary outline-none transition dark:text-white text-sm">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <textarea name="notes" placeholder="Optional notes..." rows="2"
                          class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary outline-none transition dark:text-white text-sm"></textarea>
                <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 transition text-sm">Record Payment</button>
            </form>
        </div>
        @endif

        {{-- Payment History --}}
        @if($invoice->payments->count())
        <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Payments</h3>
            <div class="space-y-2">
                @foreach($invoice->payments as $p)
                <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg text-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">${{ number_format($p->amount, 2) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $p->payment_method)) }} • {{ $p->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
