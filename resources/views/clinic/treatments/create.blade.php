@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <div>
        <a href="{{ route('patients.show', $visit->patient) }}" class="text-sm text-primary hover:underline flex items-center gap-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Patient</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Add Treatment Record</h1>
        <p class="text-sm text-gray-500">Patient: <strong>{{ $visit->patient->first_name }} {{ $visit->patient->last_name }}</strong></p>
    </div>

    <form action="{{ route('treatments.store', $visit) }}" method="POST" class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50 space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Treatment Type *</label>
            <input type="text" name="treatment_type" value="{{ old('treatment_type') }}" required placeholder="e.g. Crown, Extraction, Root Canal"
                   class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">
        </div>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Cost ($) *</label>
                <input type="number" step="0.01" name="cost" value="{{ old('cost', 0) }}" required min="0"
                       class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                <select name="status" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">
                    <option value="planned">Planned</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Notes</label>
            <textarea name="notes" rows="3"
                      class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">{{ old('notes') }}</textarea>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-primary/90 transition">Save Treatment</button>
            <a href="{{ route('patients.show', $visit->patient) }}" class="px-6 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
