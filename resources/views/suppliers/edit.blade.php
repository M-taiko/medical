@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('suppliers.index') }}" class="text-sm text-primary hover:underline flex items-center gap-1 mb-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Edit Supplier</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $supplier->name }}</p>
    </div>
</div>

@if($errors->any())
    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-sm">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-8 max-w-2xl">
    <form action="{{ route('suppliers.update', $supplier) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Supplier Name *</label>
            <input type="text" name="name" required value="{{ old('name', $supplier->name) }}"
                   class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Contact Person</label>
            <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"
                   class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                <input type="tel" name="phone" value="{{ old('phone', $supplier->phone) }}"
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $supplier->email) }}"
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Save Changes
            </button>
            <a href="{{ route('suppliers.index') }}" class="px-8 py-3 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">Cancel</a>

            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="ml-auto"
                  onsubmit="return confirm('Delete this supplier?');">
                @csrf @method('DELETE')
                <button type="submit" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition flex items-center gap-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
                </button>
            </form>
        </div>
    </form>
</div>
@endsection
