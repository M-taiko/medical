@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('clinics.index') }}" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition text-gray-500">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __('clinics.add_new') }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ __('clinics.create_subtitle') }}</p>
    </div>
</div>

<div class="max-w-2xl">
    <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-8">
        <form action="{{ route('clinics.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('clinics.clinic_name') }} *
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white @error('name') border-red-400 @enderror"
                        placeholder="{{ __('clinics.clinic_name_placeholder') }}">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('clinics.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white"
                        placeholder="+966 5x xxx xxxx">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('clinics.email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white"
                        placeholder="clinic@example.com">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('clinics.address') }}</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                        class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white"
                        placeholder="{{ __('clinics.address_placeholder') }}">
                </div>
            </div>

            <div class="pt-2 flex gap-3 justify-end">
                <a href="{{ route('clinics.index') }}"
                   class="px-6 py-3 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    {{ __('common.cancel') }}
                </a>
                <button type="submit"
                    class="bg-primary hover:bg-opacity-90 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-primary/30 transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i> {{ __('clinics.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
