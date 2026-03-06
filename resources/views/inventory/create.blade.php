@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('inventory.index') }}" class="text-sm text-primary hover:underline flex items-center gap-1 mb-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Add Product</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Create a new inventory item</p>
    </div>
</div>

@if($errors->any())
    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-sm">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-8 max-w-2xl">
    <form action="{{ route('inventory.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Product Name *</label>
                <input type="text" name="name" required value="{{ old('name') }}"
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">SKU *</label>
                <input type="text" name="sku" required value="{{ old('sku') }}"
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Stock keeping unit (unique identifier)</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Unit *</label>
                <input type="text" name="unit" required value="{{ old('unit') }}" placeholder="e.g. Box, Pack, Unit"
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Stock Quantity *</label>
                <input type="number" name="stock_quantity" required min="0" step="1" value="{{ old('stock_quantity', 0) }}"
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Cost Price ($) *</label>
                <input type="number" name="cost_price" required min="0" step="0.01" value="{{ old('cost_price') }}"
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Low Stock Threshold</label>
                <input type="number" name="low_stock_threshold" min="0" step="1" value="{{ old('low_stock_threshold') }}"
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Alert when stock falls below this</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Expiry Date</label>
            <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                   class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i> Create Product
            </button>
            <a href="{{ route('inventory.index') }}" class="px-8 py-3 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
