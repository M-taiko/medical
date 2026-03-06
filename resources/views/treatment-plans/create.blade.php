@extends('layouts.app')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('patients.show', $patient) }}" class="text-sm text-primary hover:underline flex items-center gap-1 mb-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Patient
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Add Treatment Plan</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $patient->first_name }} {{ $patient->last_name }}</p>
    </div>

    <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-8">
        <form action="{{ route('treatment-plans.store', $patient) }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tooth Number</label>
                    <input type="number" name="tooth_number" min="1" max="32" value="{{ old('tooth_number') }}"
                           placeholder="1-32"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white @error('tooth_number') border-red-500 @enderror">
                    @error('tooth_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Planned Date</label>
                    <input type="date" name="planned_date" value="{{ old('planned_date') }}"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white @error('planned_date') border-red-500 @enderror">
                    @error('planned_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Procedure Name *</label>
                <input type="text" name="procedure_name" value="{{ old('procedure_name') }}" required
                       placeholder="e.g., Root Canal Treatment, Crown, Composite Filling"
                       class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white @error('procedure_name') border-red-500 @enderror">
                @error('procedure_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                <textarea name="description" rows="4"
                          placeholder="Clinical notes, special considerations, etc."
                          class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Estimated Cost</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-gray-600 dark:text-gray-400 font-semibold">$</span>
                    <input type="number" name="estimated_cost" value="{{ old('estimated_cost') }}" step="0.01" min="0"
                           placeholder="0.00"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl pl-8 pr-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white @error('estimated_cost') border-red-500 @enderror">
                </div>
                @error('estimated_cost')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Create Treatment Plan
                </button>
                <a href="{{ route('patients.show', $patient) }}" class="px-8 py-3 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
