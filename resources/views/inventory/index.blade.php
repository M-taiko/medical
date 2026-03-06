@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between flex-wrap gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Inventory Management</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Track and manage clinic supplies</p>
    </div>
    <a href="{{ route('inventory.create') }}" class="px-5 py-2.5 bg-primary text-white rounded-xl font-bold hover:bg-primary/90 transition flex items-center gap-2 shadow-lg">
        <i data-lucide="plus" class="w-4 h-4"></i> Add Product
    </a>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="glass p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Total Products</p>
        <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalItems }}</h3>
    </div>
    <div class="glass p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Low Stock</p>
        <h3 class="text-3xl font-bold {{ $lowStockCount > 0 ? 'text-red-500' : 'text-emerald-500' }}">{{ $lowStockCount }}</h3>
        @if($lowStockCount > 0)
        <a href="?filter=low-stock" class="text-xs text-red-600 hover:underline mt-2">View low stock items</a>
        @endif
    </div>
    <div class="glass p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Inventory Value</p>
        <h3 class="text-3xl font-bold text-primary">${{ number_format($totalValue, 2) }}</h3>
    </div>
</div>

<!-- Search & Filter -->
<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-4 mb-6">
    <form method="GET" class="flex gap-3 flex-wrap">
        <input type="text" name="search" placeholder="Search by name or SKU..." value="{{ $search }}"
               class="flex-1 min-w-[200px] bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-4 py-2 text-sm dark:text-white focus:ring-2 focus:ring-primary outline-none">
        <select name="filter" onchange="this.form.submit()" class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-4 py-2 text-sm dark:text-white">
            <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Products</option>
            <option value="low-stock" {{ $filter === 'low-stock' ? 'selected' : '' }}>Low Stock</option>
            <option value="expired" {{ $filter === 'expired' ? 'selected' : '' }}>Expired</option>
            <option value="expiring-soon" {{ $filter === 'expiring-soon' ? 'selected' : '' }}>Expiring Soon</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition text-sm">Search</button>
        @if($search || $filter !== 'all')
        <a href="{{ route('inventory.index') }}" class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition text-sm">Clear</a>
        @endif
    </form>
</div>

<!-- Products Table -->
<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50/80 dark:bg-gray-800/50 uppercase font-bold text-gray-700 dark:text-gray-300 text-xs">
                <tr>
                    <th scope="col" class="px-6 py-4">Product</th>
                    <th scope="col" class="px-6 py-4">SKU</th>
                    <th scope="col" class="px-6 py-4">Stock</th>
                    <th scope="col" class="px-6 py-4">Cost Price</th>
                    <th scope="col" class="px-6 py-4">Expiry</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-4">
                        <a href="{{ route('inventory.show', $product) }}" class="font-semibold text-gray-900 dark:text-white hover:text-primary transition">
                            {{ $product->name }}
                        </a>
                        <p class="text-xs text-gray-400">{{ $product->unit }}</p>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs text-gray-700 dark:text-gray-300">{{ $product->sku }}</td>
                    <td class="px-6 py-4">
                        <span class="font-bold {{ $product->stock_quantity <= $product->low_stock_threshold ? 'text-red-600' : 'text-emerald-600' }}">
                            {{ $product->stock_quantity }}
                        </span>
                        @if($product->stock_quantity <= $product->low_stock_threshold && $product->low_stock_threshold > 0)
                        <p class="text-xs text-red-600 font-semibold">Low Stock</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">${{ number_format($product->cost_price, 2) }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($product->expiry_date)
                            @if($product->expiry_date < now())
                            <span class="text-red-600 font-bold">Expired {{ $product->expiry_date->format('M d, Y') }}</span>
                            @elseif($product->expiry_date < now()->addDays(30))
                            <span class="text-amber-600 font-bold">{{ $product->expiry_date->format('M d, Y') }}</span>
                            @else
                            <span class="text-gray-600 dark:text-gray-400">{{ $product->expiry_date->format('M d, Y') }}</span>
                            @endif
                        @else
                        <span class="text-gray-400 text-xs">No expiry</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('inventory.show', $product) }}" class="text-primary hover:underline text-xs font-semibold">View</a>
                        <a href="{{ route('inventory.edit', $product) }}" class="text-amber-600 hover:underline text-xs font-semibold">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <p class="text-gray-400">No products found. <a href="{{ route('inventory.create') }}" class="text-primary hover:underline">Add one now</a>.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
