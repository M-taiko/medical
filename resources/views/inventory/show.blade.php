@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('inventory.index') }}" class="text-sm text-primary hover:underline flex items-center gap-1 mb-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">SKU: {{ $product->sku }}</p>
    </div>
    <a href="{{ route('inventory.edit', $product) }}" class="px-5 py-2.5 bg-amber-600 text-white rounded-xl font-bold hover:bg-amber-700 transition flex items-center gap-2">
        <i data-lucide="edit" class="w-4 h-4"></i> Edit
    </a>
</div>

<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase mb-1">Unit</p>
            <p class="text-lg font-bold">{{ $product->unit }}</p>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase mb-1">Stock</p>
            <p class="text-lg font-bold text-emerald-600">{{ $product->stock_quantity }}</p>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase mb-1">Cost</p>
            <p class="text-lg font-bold">${{ number_format($product->cost_price, 2) }}</p>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase mb-1">Value</p>
            <p class="text-lg font-bold text-primary">${{ number_format($product->stock_quantity * $product->cost_price, 2) }}</p>
        </div>
    </div>
</div>

<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6 mb-6">
    <h3 class="text-lg font-bold mb-4">Record Movement</h3>
    <form action="{{ route('inventory.record-movement', $product) }}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-bold mb-2">Type</label>
                <select name="type" required class="w-full bg-gray-50 dark:bg-gray-900 border rounded-lg px-4 py-2 text-sm dark:text-white">
                    <option value="purchase-in">In</option>
                    <option value="manual-out">Out</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold mb-2">Qty</label>
                <input type="number" name="quantity" required min="1" step="1" class="w-full bg-gray-50 dark:bg-gray-900 border rounded-lg px-4 py-2 text-sm dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-bold mb-2">Notes</label>
                <input type="text" name="notes" class="w-full bg-gray-50 dark:bg-gray-900 border rounded-lg px-4 py-2 text-sm dark:text-white">
            </div>
        </div>
        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg font-bold text-sm">Record</button>
    </form>
</div>

<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
    <div class="p-5 border-b bg-gray-50/50">
        <h3 class="font-bold">History</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50/80 uppercase font-bold text-xs">
                <tr>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Qty</th>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($movements as $m)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $m->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs font-bold {{ $m->type === 'purchase-in' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $m->type === 'purchase-in' ? 'In' : 'Out' }}</span></td>
                    <td class="px-6 py-4 font-bold">{{ $m->quantity }}</td>
                    <td class="px-6 py-4">{{ $m->user->name }}</td>
                    <td class="px-6 py-4 text-xs">{{ $m->notes ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No movements</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($movements->hasPages())
    <div class="p-4 border-t bg-gray-50/50">{{ $movements->links() }}</div>
    @endif
</div>
@endsection
