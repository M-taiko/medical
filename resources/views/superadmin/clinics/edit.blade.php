@extends('layouts.superadmin')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('superadmin.clinics.show', $clinic) }}" class="text-sm text-primary hover:underline flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Edit Clinic — {{ $clinic->name }}</h1>
    </div>

    <form action="{{ route('superadmin.clinics.update', $clinic) }}" method="POST" class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50 space-y-5">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Clinic Name *</label>
                <input type="text" name="name" value="{{ old('name', $clinic->name) }}" required
                       class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $clinic->email) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $clinic->phone) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Address</label>
                <textarea name="address" rows="2"
                          class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">{{ old('address', $clinic->address) }}</textarea>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-primary/90 transition">Save Changes</button>
            <a href="{{ route('superadmin.clinics.show', $clinic) }}" class="px-6 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
