@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('appointments.index') }}" class="text-sm text-primary hover:underline flex items-center gap-1 mb-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Edit Appointment</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $visit->patient->first_name }} {{ $visit->patient->last_name }}</p>
    </div>
</div>

@if($errors->any())
    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-sm">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-8 max-w-2xl">
    <form action="{{ route('appointments.update', $visit) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Patient *</label>
            <select name="patient_id" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                <option value="">Select a patient...</option>
                @foreach($patients as $p)
                    <option value="{{ $p->id }}" {{ old('patient_id', $visit->patient_id) == $p->id ? 'selected' : '' }}>
                        {{ $p->first_name }} {{ $p->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Doctor *</label>
            <select name="doctor_id" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                <option value="">Select a doctor...</option>
                @foreach($doctors as $d)
                    <option value="{{ $d->id }}" {{ old('doctor_id', $visit->doctor_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Date & Time *</label>
            <input type="datetime-local" name="appointment_time" required
                   value="{{ old('appointment_time', $visit->appointment_time->format('Y-m-d\TH:i')) }}"
                   class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Chief Complaint</label>
            <textarea name="chief_complaint" rows="3"
                      class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">{{ old('chief_complaint', $visit->chief_complaint) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Follow-Up Date</label>
            <input type="date" name="follow_up_date"
                   value="{{ old('follow_up_date', $visit->follow_up_date?->format('Y-m-d')) }}"
                   class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Save Changes
            </button>
            <a href="{{ route('appointments.index') }}" class="px-8 py-3 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">Cancel</a>

            <form action="{{ route('appointments.destroy', $visit) }}" method="POST" class="ml-auto"
                  onsubmit="return confirm('Delete this appointment?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition flex items-center gap-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
                </button>
            </form>
        </div>
    </form>
</div>
@endsection
