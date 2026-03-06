@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('patients.show', $patient) }}" class="text-sm text-primary hover:underline flex items-center gap-1 mb-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Profile</a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Edit Patient</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $patient->first_name }} {{ $patient->last_name }}</p>
    </div>
</div>

@if($errors->any())
    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
        <ul class="list-disc list-inside text-red-700 dark:text-red-300 text-sm space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-8 max-w-4xl">
    <form action="{{ route('patients.update', $patient) }}" method="POST" class="space-y-8">
        @csrf @method('PUT')

        {{-- Personal Info --}}
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">
                <i data-lucide="user" class="text-primary w-5 h-5"></i> Personal Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $patient->first_name) }}" required
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $patient->last_name) }}"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">National ID</label>
                    <input type="text" name="national_id" value="{{ old('national_id', $patient->national_id) }}"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Gender</label>
                    <select name="gender" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                        <option value="">Select</option>
                        <option value="Male" {{ old('gender', $patient->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $patient->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Blood Type</label>
                    <select name="blood_type" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                        <option value="">Select</option>
                        @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bt)
                            <option value="{{ $bt }}" {{ old('blood_type', $patient->blood_type) === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">
                <i data-lucide="phone" class="text-primary w-5 h-5"></i> Contact Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Phone *</label>
                    <input type="tel" name="phone" value="{{ old('phone', $patient->phone) }}" required
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">WhatsApp</label>
                    <input type="tel" name="whatsapp_number" value="{{ old('whatsapp_number', $patient->whatsapp_number) }}"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $patient->email) }}"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Address</label>
                    <input type="text" name="address" value="{{ old('address', $patient->address) }}"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
            </div>
        </div>

        {{-- Medical History --}}
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">
                <i data-lucide="activity" class="text-red-500 w-5 h-5"></i> Medical History
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Chronic Diseases <span class="font-normal text-gray-400">(comma-separated)</span></label>
                    <input type="text" name="chronic_diseases" value="{{ old('chronic_diseases', implode(', ', $patient->chronic_diseases ?? [])) }}"
                           placeholder="Diabetes, Hypertension..."
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Allergies <span class="font-normal text-gray-400">(comma-separated)</span></label>
                    <input type="text" name="allergies" value="{{ old('allergies', implode(', ', $patient->allergies ?? [])) }}"
                           placeholder="Penicillin, Latex..."
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
            </div>
        </div>

        {{-- Emergency Contact --}}
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">
                <i data-lucide="phone-call" class="text-primary w-5 h-5"></i> Emergency Contact
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Contact Name</label>
                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact['name'] ?? '') }}"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Contact Phone</label>
                    <input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact['phone'] ?? '') }}"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
            <textarea name="notes" rows="3"
                      class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">{{ old('notes', $patient->notes) }}</textarea>
        </div>

        {{-- Referral Tracking --}}
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-gray-800 pb-2">
                <i data-lucide="share-2" class="text-emerald-500 w-5 h-5"></i> Referral Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">How did they hear about us?</label>
                    <select name="referral_source" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                        <option value="walk_in" {{ old('referral_source', $patient->referral_source) === 'walk_in' ? 'selected' : '' }}>Walk-in</option>
                        <option value="patient" {{ old('referral_source', $patient->referral_source) === 'patient' ? 'selected' : '' }}>Referred by another patient</option>
                        <option value="doctor" {{ old('referral_source', $patient->referral_source) === 'doctor' ? 'selected' : '' }}>Doctor referral</option>
                        <option value="google" {{ old('referral_source', $patient->referral_source) === 'google' ? 'selected' : '' }}>Google/Search</option>
                        <option value="social_media" {{ old('referral_source', $patient->referral_source) === 'social_media' ? 'selected' : '' }}>Social media</option>
                        <option value="other" {{ old('referral_source', $patient->referral_source) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Referred by patient (optional)</label>
                    <select name="referred_by_patient_id" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                        <option value="">Select a patient...</option>
                        @forelse(\App\Models\Patient::where('id', '!=', $patient->id)->orderBy('first_name')->get() as $p)
                        <option value="{{ $p->id }}" {{ old('referred_by_patient_id', $patient->referred_by_patient_id) == $p->id ? 'selected' : '' }}>{{ $p->first_name }} {{ $p->last_name }}</option>
                        @empty
                        <option disabled>No other patients</option>
                        @endforelse
                    </select>
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Save Changes
            </button>
            <a href="{{ route('patients.show', $patient) }}" class="px-8 py-3 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">Cancel</a>

            @if(auth()->user()->isClinicAdmin() || auth()->user()->isSuperAdmin())
            <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="ml-auto"
                  onsubmit="return confirm('Are you sure you want to delete this patient? This action cannot be easily undone.')">
                @csrf @method('DELETE')
                <button type="submit" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition flex items-center gap-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Delete Patient
                </button>
            </form>
            @endif
        </div>
    </form>
</div>
@endsection
