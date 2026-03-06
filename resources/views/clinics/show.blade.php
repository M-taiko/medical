@extends('layouts.app')

@section('content')
{{-- Page Header --}}
<div class="mb-8 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('clinics.index') }}" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition text-gray-500">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $clinic->name }}</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">{{ __('clinics.show_subtitle') }}</p>
        </div>
    </div>
    <a href="{{ route('clinics.edit', $clinic) }}"
       class="bg-primary hover:bg-opacity-90 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg hover:shadow-primary/30 transition-all">
        <i data-lucide="pencil" class="w-4 h-4"></i> {{ __('common.edit') }}
    </a>
</div>

{{-- Stats Row --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="glass rounded-2xl p-5 border border-gray-100 dark:border-gray-800 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
            <i data-lucide="users" class="w-6 h-6 text-primary"></i>
        </div>
        <div>
            <div class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $clinic->patients_count }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('clinics.total_patients') }}</div>
        </div>
    </div>
    <div class="glass rounded-2xl p-5 border border-gray-100 dark:border-gray-800 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center">
            <i data-lucide="stethoscope" class="w-6 h-6 text-secondary"></i>
        </div>
        <div>
            <div class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $clinic->users_count }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('clinics.total_doctors') }}</div>
        </div>
    </div>
    <div class="glass rounded-2xl p-5 border border-gray-100 dark:border-gray-800 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center">
            <i data-lucide="calendar" class="w-6 h-6 text-accent"></i>
        </div>
        <div>
            <div class="text-2xl font-extrabold text-gray-900 dark:text-white">
                {{ $clinic->settings['currency'] ?? 'USD' }}
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('clinics.currency') }}</div>
        </div>
    </div>
</div>

{{-- Info Card --}}
<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-8">
    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
        <i data-lucide="info" class="w-5 h-5 text-primary"></i> {{ __('clinics.clinic_info') }}
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('clinics.clinic_name') }}</label>
            <p class="text-gray-900 dark:text-white font-semibold">{{ $clinic->name }}</p>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('clinics.phone') }}</label>
            <p class="text-gray-900 dark:text-white font-semibold">{{ $clinic->phone ?: __('common.na') }}</p>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('clinics.email') }}</label>
            <p class="text-gray-900 dark:text-white font-semibold">{{ $clinic->email ?: __('common.na') }}</p>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('clinics.address') }}</label>
            <p class="text-gray-900 dark:text-white font-semibold">{{ $clinic->address ?: __('common.na') }}</p>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('clinics.timezone') }}</label>
            <p class="text-gray-900 dark:text-white font-semibold">{{ $clinic->settings['timezone'] ?? 'UTC' }}</p>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('clinics.created_at') }}</label>
            <p class="text-gray-900 dark:text-white font-semibold">{{ $clinic->created_at->format('Y-m-d') }}</p>
        </div>
    </div>
</div>

{{-- Recent Patients --}}
@if($clinic->patients_count > 0)
<div class="mt-8 glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <i data-lucide="users" class="w-5 h-5 text-primary"></i> {{ __('clinics.recent_patients') }}
        </h3>
        <a href="{{ route('patients.index') }}" class="text-sm text-primary hover:underline font-medium">{{ __('common.view_all') }} →</a>
    </div>
    <div class="divide-y divide-gray-100 dark:divide-gray-800">
        @foreach($clinic->patients()->latest()->take(5)->get() as $patient)
        <div class="px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-primary to-secondary text-white flex items-center justify-center text-xs font-bold">
                    {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $patient->first_name }} {{ $patient->last_name }}</p>
                    <p class="text-xs text-gray-500">{{ $patient->phone }}</p>
                </div>
            </div>
            <a href="{{ route('dental-chart.show', $patient) }}"
               class="text-xs font-bold text-primary hover:underline">{{ __('common.view') }}</a>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
