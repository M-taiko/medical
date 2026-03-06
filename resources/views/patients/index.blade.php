@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __('patients.title') }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ __('patients.subtitle') }}</p>
    </div>
    <a href="{{ route('patients.create') }}" class="bg-primary hover:bg-opacity-90 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg hover:shadow-primary/30 transition-all">
        <i data-lucide="plus"></i> {{ __('patients.add_new') }}
    </a>
</div>

<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
    <form method="GET" action="{{ route('patients.index') }}" class="p-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 flex gap-4">
        <div class="relative flex-1 max-w-md">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"></i>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="{{ __('patients.search_placeholder') }}"
                   class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 focus:ring-2 focus:ring-primary outline-none transition-all dark:text-white text-sm">
        </div>
        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition">Search</button>
        @if($search)
            <a href="{{ route('patients.index') }}" class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition">Clear</a>
        @endif
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50/80 dark:bg-gray-800/50 uppercase font-bold text-gray-700 dark:text-gray-300">
                <tr>
                    <th scope="col" class="px-6 py-4">{{ __('patients.id') }}</th>
                    <th scope="col" class="px-6 py-4">{{ __('patients.name') }}</th>
                    <th scope="col" class="px-6 py-4">{{ __('patients.contact') }}</th>
                    <th scope="col" class="px-6 py-4">{{ __('patients.last_visit') }}</th>
                    <th scope="col" class="px-6 py-4">{{ __('patients.total_visits') }}</th>
                    <th scope="col" class="px-6 py-4 text-right">{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($patients as $p)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                        #{{ $p->id }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900 dark:text-white">{{ $p->first_name }} {{ $p->last_name }}</div>
                        <div class="text-xs text-gray-500">{{ __('patients.dob') }}: {{ $p->date_of_birth ? $p->date_of_birth->format('Y-m-d') : __('common.na') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2"><i data-lucide="phone" class="w-3 h-3 text-gray-400"></i> {{ $p->phone }}</div>
                        <div class="flex items-center gap-2 mt-1"><i data-lucide="message-circle" class="w-3 h-3 text-emerald-500"></i> {{ $p->whatsapp_number }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ $p->updated_at->diffForHumans() }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-primary/10 text-primary px-2.5 py-1 rounded-full text-xs font-bold">{{ __('patients.visits_count', ['n' => $p->visits_count]) }}</span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('dental-chart.show', $p) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-900/30 text-teal-600 hover:bg-teal-100 dark:hover:bg-teal-900/50 transition-colors" title="{{ __('patients.open_chart') }}">
                            <i data-lucide="tooth" class="w-4 h-4"></i>
                        </a>
                        <a href="{{ route('patients.show', $p) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors" title="{{ __('patients.view_profile') }}">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center bg-gray-50/30 dark:bg-gray-800/30">
                        <div class="flex flex-col items-center justify-center opacity-50">
                            <i data-lucide="users" class="w-12 h-12 mb-2"></i>
                            <p>{{ __('patients.no_patients') }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($patients->hasPages())
    <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
        {{ $patients->links() }}
    </div>
    @endif
</div>
@endsection
