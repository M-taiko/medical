@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __('settings.title') }}</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">{{ __('settings.subtitle') }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Navigation Sidebar for Settings -->
    <div class="lg:col-span-1 space-y-2">
        <a href="#" class="flex items-center gap-3 p-4 bg-primary/10 text-primary rounded-xl font-bold transition-all border border-primary/20">
            <i data-lucide="building" class="w-5 h-5"></i> {{ __('settings.clinic_profile') }}
        </a>
        <a href="#" class="flex items-center gap-3 p-4 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl font-medium transition-all">
            <i data-lucide="users" class="w-5 h-5"></i> {{ __('settings.manage_users') }}
        </a>
        <a href="#" class="flex items-center gap-3 p-4 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl font-medium transition-all">
            <i data-lucide="file-text" class="w-5 h-5"></i> {{ __('settings.treatment_templates') }}
        </a>
        <a href="#" class="flex items-center gap-3 p-4 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl font-medium transition-all">
            <i data-lucide="bell" class="w-5 h-5"></i> {{ __('settings.sms_integrations') }}
        </a>
    </div>

    <!-- Settings Form Area -->
    <div class="lg:col-span-2 glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-8">
        <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <h3 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
                {{ __('settings.general_info') }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('settings.clinic_name') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $clinic->name) }}" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('settings.primary_phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $clinic->phone) }}" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('settings.email') }}</label>
                    <input type="email" name="email" value="{{ old('email', $clinic->email) }}" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('settings.address') }}</label>
                    <input type="text" name="address" value="{{ old('address', $clinic->address) }}" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-4 mb-6 mt-10">
                {{ __('settings.localization') }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                    $settings = $clinic->settings ?? [];
                @endphp
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('settings.currency') }}</label>
                    <select name="currency" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                        <option value="USD" {{ ($settings['currency'] ?? 'USD') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="EUR" {{ ($settings['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                        <option value="AED" {{ ($settings['currency'] ?? '') == 'AED' ? 'selected' : '' }}>AED (د.إ)</option>
                        <option value="SAR" {{ ($settings['currency'] ?? '') == 'SAR' ? 'selected' : '' }}>SAR (﷼)</option>
                        <option value="EGP" {{ ($settings['currency'] ?? '') == 'EGP' ? 'selected' : '' }}>EGP (£)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('settings.timezone') }}</label>
                    <select name="timezone" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                        <option value="UTC" {{ ($settings['timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                        <option value="Asia/Dubai" {{ ($settings['timezone'] ?? '') == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai</option>
                        <option value="Asia/Riyadh" {{ ($settings['timezone'] ?? '') == 'Asia/Riyadh' ? 'selected' : '' }}>Asia/Riyadh</option>
                        <option value="Africa/Cairo" {{ ($settings['timezone'] ?? '') == 'Africa/Cairo' ? 'selected' : '' }}>Africa/Cairo</option>
                    </select>
                </div>
            </div>

            <div class="pt-6 flex justify-end">
                <button type="submit" class="bg-primary hover:bg-opacity-90 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-primary/30 transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i> {{ __('settings.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
