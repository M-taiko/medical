@extends('layouts.superadmin')

@section('content')
<div class="max-w-xl">
    <div class="mb-6">
        <a href="{{ route('superadmin.plans.index') }}" class="text-sm text-primary hover:underline flex items-center gap-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Edit Plan — {{ $plan->name }}</h1>
    </div>

    <form action="{{ route('superadmin.plans.update', $plan) }}" method="POST" class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50 space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Plan Name *</label>
            <input type="text" name="name" value="{{ old('name', $plan->name) }}" required
                   class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">
        </div>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Duration (months)</label>
                <input type="number" name="duration_months" value="{{ old('duration_months', $plan->duration_months) }}" min="1" max="12"
                       class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Price ($)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $plan->price) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">
            </div>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" id="is_active" {{ $plan->is_active ? 'checked' : '' }} class="rounded text-primary">
            <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</label>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-primary/90 transition">Save Changes</button>
            <a href="{{ route('superadmin.plans.index') }}" class="px-6 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
