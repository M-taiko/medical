@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __('clinics.title') }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ __('clinics.subtitle') }}</p>
    </div>
    <a href="{{ route('clinics.create') }}" class="bg-primary hover:bg-opacity-90 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg hover:shadow-primary/30 transition-all">
        <i data-lucide="plus"></i> {{ __('clinics.add_new') }}
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($clinics as $clinic)
    <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-primary/10 to-secondary/10 dark:from-primary/20 dark:to-secondary/20 px-6 py-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center shadow-lg shadow-primary/30 shrink-0">
                <i data-lucide="building-2" class="w-6 h-6 text-white"></i>
            </div>
            <div class="min-w-0">
                <h2 class="text-lg font-extrabold text-gray-900 dark:text-white truncate">{{ $clinic->name }}</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ID #{{ $clinic->id }}</p>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="px-6 py-4 space-y-3">
            @if($clinic->phone)
            <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                <i data-lucide="phone" class="w-4 h-4 text-gray-400 shrink-0"></i>
                <span>{{ $clinic->phone }}</span>
            </div>
            @endif
            @if($clinic->email)
            <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                <i data-lucide="mail" class="w-4 h-4 text-gray-400 shrink-0"></i>
                <span class="truncate">{{ $clinic->email }}</span>
            </div>
            @endif
            @if($clinic->address)
            <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 shrink-0"></i>
                <span class="line-clamp-1">{{ $clinic->address }}</span>
            </div>
            @endif
            @if(!$clinic->phone && !$clinic->email && !$clinic->address)
            <p class="text-sm text-gray-400 italic">{{ __('clinics.no_details') }}</p>
            @endif
        </div>

        {{-- Card Stats --}}
        <div class="px-6 py-3 bg-gray-50/50 dark:bg-gray-800/30 border-t border-gray-100 dark:border-gray-800 flex items-center gap-6">
            <div class="flex items-center gap-2 text-sm">
                <div class="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center">
                    <i data-lucide="users" class="w-3.5 h-3.5 text-primary"></i>
                </div>
                <span class="font-bold text-gray-900 dark:text-white">{{ $clinic->patients_count }}</span>
                <span class="text-gray-500 dark:text-gray-400">{{ __('clinics.patients') }}</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <div class="w-7 h-7 rounded-lg bg-secondary/10 flex items-center justify-center">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-secondary"></i>
                </div>
                <span class="font-bold text-gray-900 dark:text-white">
                    {{ ($clinic->settings['currency'] ?? 'USD') }}
                </span>
            </div>
        </div>

        {{-- Card Actions --}}
        <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-end gap-2">
            <a href="{{ route('clinics.show', $clinic) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all">
                <i data-lucide="eye" class="w-3.5 h-3.5"></i> {{ __('common.view') }}
            </a>
            <a href="{{ route('clinics.edit', $clinic) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">
                <i data-lucide="pencil" class="w-3.5 h-3.5"></i> {{ __('common.edit') }}
            </a>
            <form action="{{ route('clinics.destroy', $clinic) }}" method="POST" onsubmit="return confirm('{{ __('clinics.confirm_delete') }}')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/40 transition-all">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> {{ __('common.delete') }}
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="md:col-span-2 xl:col-span-3 glass rounded-2xl border border-gray-100 dark:border-gray-800 py-16 flex flex-col items-center justify-center opacity-50">
        <i data-lucide="building-2" class="w-14 h-14 mb-3 text-gray-400"></i>
        <p class="font-bold text-gray-500">{{ __('clinics.no_clinics') }}</p>
        <a href="{{ route('clinics.create') }}" class="mt-4 text-sm text-primary hover:underline">{{ __('clinics.add_first') }}</a>
    </div>
    @endforelse
</div>

@if($clinics->hasPages())
<div class="mt-6">{{ $clinics->links() }}</div>
@endif
@endsection
