@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __('dental_chart.patient_record') }}</h1>
        <div class="text-sm text-gray-500 mt-1 flex items-center gap-4">
            <span><strong class="font-semibold text-gray-700 dark:text-gray-300">{{ __('dental_chart.name') }}:</strong> {{ $patient->first_name }} {{ $patient->last_name }}</span>
            <span><strong class="font-semibold text-gray-700 dark:text-gray-300">{{ __('dental_chart.id') }}:</strong> #{{ $patient->id }}</span>
            <span><strong class="font-semibold text-gray-700 dark:text-gray-300">{{ __('dental_chart.blood_type') }}:</strong> {{ $patient->blood_type ?? __('common.na') }}</span>
        </div>
    </div>

    <!-- WhatsApp Report Generation Button -->
    <form action="{{ route('dental-chart.send-report', $patient) }}" method="POST">
        @csrf
        <button type="submit" class="flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white px-5 py-2.5 rounded-xl shadow-lg hover:shadow-emerald-500/30 font-bold transition-all">
            <i data-lucide="file-text" class="w-5 h-5"></i>
            {{ __('dental_chart.send_report') }}
        </button>
    </form>
</div>

<!-- Extra Patient Details / Medical Record -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="glass p-5 rounded-2xl border border-red-100 dark:border-red-900 shadow-sm relative overflow-hidden">
        <div class="absolute -right-4 -top-4 opacity-5 bg-red-500 w-24 h-24 rounded-full"></div>
        <h3 class="text-red-600 dark:text-red-400 font-bold mb-2 flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5"></i> {{ __('dental_chart.allergies') }}
        </h3>
        <p class="text-gray-700 dark:text-gray-300 text-sm italic">{{ is_array($patient->allergies) ? implode(', ', $patient->allergies) : ($patient->allergies ?: __('dental_chart.no_allergies')) }}</p>
    </div>

    <div class="glass p-5 rounded-2xl border border-orange-100 dark:border-orange-900 shadow-sm relative overflow-hidden">
        <div class="absolute -right-4 -top-4 opacity-5 bg-orange-500 w-24 h-24 rounded-full"></div>
        <h3 class="text-orange-600 dark:text-orange-400 font-bold mb-2 flex items-center gap-2">
            <i data-lucide="activity" class="w-5 h-5"></i> {{ __('dental_chart.chronic_diseases') }}
        </h3>
        <p class="text-gray-700 dark:text-gray-300 text-sm italic">{{ is_array($patient->chronic_diseases) ? implode(', ', $patient->chronic_diseases) : ($patient->chronic_diseases ?: __('dental_chart.none')) }}</p>
    </div>

    <div class="glass p-5 rounded-2xl border border-blue-100 dark:border-blue-900 shadow-sm relative overflow-hidden">
        <div class="absolute -right-4 -top-4 opacity-5 bg-blue-500 w-24 h-24 rounded-full"></div>
        <h3 class="text-blue-600 dark:text-blue-400 font-bold mb-2 flex items-center gap-2">
            <i data-lucide="phone" class="w-5 h-5"></i> {{ __('dental_chart.emergency_contact') }}
        </h3>
        <p class="text-gray-700 dark:text-gray-300 text-sm">
            @if(is_array($patient->emergency_contact))
                {{ $patient->emergency_contact['name'] ?? '' }} ({{ $patient->emergency_contact['phone'] ?? '' }})
            @else
                {{ __('dental_chart.not_provided') }}
            @endif
        </p>
    </div>
</div>

<!-- SVG Dental Chart -->
<x-dental-chart :patient="$patient" :chartData="$chartData" />




<!-- Timeline -->
@if($timeline->isNotEmpty())
<div class="mt-12 glass p-8 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
    <h3 class="text-xl font-bold mb-6 flex items-center gap-2 text-primary">
        <i data-lucide="history" class="w-6 h-6"></i> Clinical Timeline ({{ $timeline->count() }} events)
    </h3>

    <div class="relative border-l-2 border-primary/30 ml-3 space-y-8">
        @foreach($timeline as $event)
        <div class="relative pl-8">
            @php
                $colorClasses = [
                    'primary' => 'bg-primary text-white',
                    'blue' => 'bg-blue-500 text-white',
                    'emerald' => 'bg-emerald-500 text-white',
                    'purple' => 'bg-purple-500 text-white',
                ];
                $borderClasses = [
                    'primary' => 'border-primary/20 bg-primary/5',
                    'blue' => 'border-blue-200/50 dark:border-blue-900/30 bg-blue-50/50 dark:bg-blue-900/10',
                    'emerald' => 'border-emerald-200/50 dark:border-emerald-900/30 bg-emerald-50/50 dark:bg-emerald-900/10',
                    'purple' => 'border-purple-200/50 dark:border-purple-900/30 bg-purple-50/50 dark:bg-purple-900/10',
                ];
                $color = $event['color'] ?? 'primary';
            @endphp
            <div class="absolute -left-2.5 top-1 w-5 h-5 {{ $colorClasses[$color] ?? $colorClasses['primary'] }} rounded-full shadow border-4 border-white dark:border-gray-900 flex items-center justify-center text-xs font-bold">
                <i data-lucide="{{ $event['icon'] }}" class="w-2.5 h-2.5"></i>
            </div>
            <div class="text-xs text-gray-500 font-medium mb-1 uppercase">{{ $event['date']->format('M d, Y') }} @ {{ $event['date']->format('h:i A') }}</div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 {{ $borderClasses[$color] ?? $borderClasses['primary'] }}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="font-bold text-gray-800 dark:text-white text-sm">
                            @if($event['type'] === 'visit')
                                <span class="inline-block px-2 py-1 rounded text-xs font-bold mr-2 {{ $event['status'] === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ ucfirst($event['status']) }}
                                </span>
                            @elseif($event['type'] === 'prescription')
                                <span class="inline-block px-2 py-1 rounded text-xs font-bold mr-2 bg-emerald-100 text-emerald-700">
                                    {{ $event['items_count'] }} medicine{{ $event['items_count'] !== 1 ? 's' : '' }}
                                </span>
                            @endif
                            {{ $event['title'] }}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $event['description'] }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="mt-12 glass p-8 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm text-center text-gray-500">
    <i data-lucide="history" class="w-12 h-12 mx-auto mb-3 opacity-30"></i>
    <p>No clinical history yet. Timeline events will appear here.</p>
</div>
@endif
@endsection
