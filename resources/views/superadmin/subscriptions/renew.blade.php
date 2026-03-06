@extends('layouts.superadmin')

@section('content')
<div class="max-w-xl">
    <div class="mb-6">
        <a href="{{ route('superadmin.clinics.show', $clinic) }}" class="text-sm text-primary hover:underline flex items-center gap-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Renew Subscription</h1>
        <p class="text-sm text-gray-500">For: <strong>{{ $clinic->name }}</strong></p>
    </div>

    @if($current)
    <div class="p-4 mb-5 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl text-sm">
        <p class="font-semibold text-amber-700 dark:text-amber-400">Current Subscription</p>
        <p class="text-amber-600 dark:text-amber-300">{{ $current->plan->name }} · Expires {{ $current->subscription_end_date->format('M d, Y') }}</p>
    </div>
    @endif

    <form action="{{ route('superadmin.subscriptions.process-renewal', $clinic) }}" method="POST" class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50 space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Select New Plan *</label>
            <div class="space-y-3">
                @foreach($plans as $plan)
                <label class="flex items-center gap-4 p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:border-primary transition has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                    <input type="radio" name="plan_id" value="{{ $plan->id }}" class="text-primary" {{ ($current && $current->plan_id === $plan->id) ? 'checked' : '' }}>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $plan->name }}</p>
                        <p class="text-xs text-gray-400">{{ $plan->duration_months }} month(s)</p>
                    </div>
                    <p class="font-bold text-primary text-lg">${{ number_format($plan->price, 2) }}</p>
                </label>
                @endforeach
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Price Paid ($) *</label>
            <input type="number" step="0.01" name="price_paid" value="{{ old('price_paid', $current?->price_paid) }}" required
                   class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Notes</label>
            <textarea name="notes" rows="2"
                      class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">{{ old('notes') }}</textarea>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition">Renew Subscription</button>
            <a href="{{ route('superadmin.clinics.show', $clinic) }}" class="px-6 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
