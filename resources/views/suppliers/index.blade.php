@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Suppliers</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Manage clinic suppliers and vendors</p>
    </div>
    <a href="{{ route('suppliers.create') }}" class="px-5 py-2.5 bg-primary text-white rounded-xl font-bold hover:bg-primary/90 transition flex items-center gap-2 shadow-lg">
        <i data-lucide="plus" class="w-4 h-4"></i> Add Supplier
    </a>
</div>

@if (session('success'))
    <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-300 text-sm">
        {{ session('success') }}
    </div>
@endif

<!-- Search -->
<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-4 mb-6">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" placeholder="Search by name, contact, phone or email..." value="{{ $search }}"
               class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-4 py-2 text-sm dark:text-white focus:ring-2 focus:ring-primary outline-none">
        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition text-sm">Search</button>
        @if($search)
        <a href="{{ route('suppliers.index') }}" class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition text-sm">Clear</a>
        @endif
    </form>
</div>

<!-- Suppliers Table -->
<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50/80 dark:bg-gray-800/50 uppercase font-bold text-gray-700 dark:text-gray-300 text-xs">
                <tr>
                    <th scope="col" class="px-6 py-4">Supplier</th>
                    <th scope="col" class="px-6 py-4">Contact</th>
                    <th scope="col" class="px-6 py-4">Phone</th>
                    <th scope="col" class="px-6 py-4">Email</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($suppliers as $supplier)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $supplier->name }}</td>
                    <td class="px-6 py-4">{{ $supplier->contact_person ?? '—' }}</td>
                    <td class="px-6 py-4">{{ $supplier->phone ?? '—' }}</td>
                    <td class="px-6 py-4 text-blue-600 hover:underline">
                        @if($supplier->email)
                            <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="text-amber-600 hover:underline text-xs font-semibold">Edit</a>
                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: inline;"
                              onsubmit="return confirm('Delete {{ $supplier->name }}?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <p class="text-gray-400">No suppliers yet. <a href="{{ route('suppliers.create') }}" class="text-primary hover:underline">Add one now</a>.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suppliers->hasPages())
    <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
        {{ $suppliers->links() }}
    </div>
    @endif
</div>
@endsection
