@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __('patients.add_title') }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ __('patients.add_subtitle') }}</p>
    </div>
    <a href="{{ route('patients.index') }}" class="text-primary font-bold hover:underline flex items-center gap-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> {{ __('patients.back_to_directory') }}
    </a>
</div>

<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden p-8 max-w-4xl">
    <form action="{{ route('patients.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <!-- Personal Info -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">
                <i data-lucide="user" class="text-primary w-5 h-5"></i> {{ __('patients.personal_info') }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('patients.first_name') }} *</label>
                    <input type="text" name="first_name" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('patients.last_name') }}</label>
                    <input type="text" name="last_name" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('patients.date_of_birth') }}</label>
                    <input type="date" name="date_of_birth" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('patients.blood_type') }}</label>
                    <select name="blood_type" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                        <option value="">{{ __('common.select') }}</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">
                <i data-lucide="phone" class="text-primary w-5 h-5"></i> {{ __('patients.contact_section') }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('patients.phone') }} *</label>
                    <input type="tel" name="phone" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('patients.whatsapp') }}</label>
                    <input type="tel" name="whatsapp_number" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
            </div>
        </div>

        <!-- Medical History -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">
                <i data-lucide="activity" class="text-red-500 w-5 h-5"></i> {{ __('patients.medical_history') }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('patients.chronic_diseases') }}</label>
                    <input type="text" name="chronic_diseases" placeholder="{{ __('patients.chronic_placeholder') }}" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('patients.allergies') }}</label>
                    <input type="text" name="allergies" placeholder="{{ __('patients.allergies_placeholder') }}" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
            </div>
        </div>

        <!-- Referral Tracking -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">
                <i data-lucide="share-2" class="text-emerald-500 w-5 h-5"></i> Referral Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">How did they hear about us?</label>
                    <select name="referral_source" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                        <option value="walk_in">Walk-in</option>
                        <option value="patient">Referred by another patient</option>
                        <option value="doctor">Doctor referral</option>
                        <option value="google">Google/Search</option>
                        <option value="social_media">Social media</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Referred by patient (optional)</label>
                    <select name="referred_by_patient_id" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                        <option value="">Select a patient...</option>
                        @forelse(\App\Models\Patient::orderBy('first_name')->get() as $p)
                        <option value="{{ $p->id }}">{{ $p->first_name }} {{ $p->last_name }}</option>
                        @empty
                        <option disabled>No patients yet</option>
                        @endforelse
                    </select>
                </div>
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-primary to-secondary hover:from-indigo-600 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i> {{ __('patients.register') }}
            </button>
        </div>
    </form>
</div>
@endsection
