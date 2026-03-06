@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div>
        <a href="{{ route('patients.show', $patient) }}" class="text-sm text-primary hover:underline flex items-center gap-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Patient</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">New Prescription</h1>
        <p class="text-sm text-gray-500">Patient: <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong></p>
    </div>

    <form action="{{ route('prescriptions.store', $patient) }}" method="POST" class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50 space-y-6" x-data="prescriptionForm()">
        @csrf

        <!-- Diagnosis -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Diagnosis *</label>
            <textarea name="diagnosis" rows="2" required
                      class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">{{ old('diagnosis') }}</textarea>
        </div>

        <!-- Link to Visit -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Link to Visit (optional)</label>
            <select name="visit_id" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">
                <option value="">— None —</option>
                @foreach($visits as $visit)
                <option value="{{ $visit->id }}">{{ $visit->appointment_time->format('M d, Y H:i') }} — {{ $visit->chief_complaint ?? 'Visit' }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Date *</label>
            <input type="date" name="prescribed_at" value="{{ old('prescribed_at', today()->toDateString()) }}" required
                   class="w-48 px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">
        </div>

        <!-- Medicine Items -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Medicines *</label>
                <button type="button" @click="addItem()" class="text-sm text-primary hover:underline flex items-center gap-1">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Medicine
                </button>
            </div>
            <template x-for="(item, index) in items" :key="index">
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-3 p-4 border border-gray-100 dark:border-gray-700 rounded-xl relative">
                    <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                    <div class="sm:col-span-2">
                        <label class="text-xs text-gray-400 mb-0.5 block">Medicine Name</label>
                        <input type="text" :name="`items[${index}][medicine_name]`" x-model="item.medicine_name" required
                               class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 mb-0.5 block">Dosage</label>
                        <input type="text" :name="`items[${index}][dosage]`" x-model="item.dosage" required placeholder="e.g. 500mg"
                               class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 mb-0.5 block">Frequency</label>
                        <input type="text" :name="`items[${index}][frequency]`" x-model="item.frequency" required placeholder="e.g. twice daily"
                               class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 mb-0.5 block">Duration</label>
                        <input type="text" :name="`items[${index}][duration]`" x-model="item.duration" required placeholder="e.g. 7 days"
                               class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                </div>
            </template>
        </div>

        <!-- Instructions -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Additional Instructions</label>
            <textarea name="instructions" rows="2" placeholder="e.g. Take after meals, avoid dairy..."
                      class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary outline-none">{{ old('instructions') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-primary/90 transition">Create Prescription</button>
            <a href="{{ route('patients.show', $patient) }}" class="px-6 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>

<script>
function prescriptionForm() {
    return {
        items: [{ medicine_name: '', dosage: '', frequency: '', duration: '' }],
        addItem() {
            this.items.push({ medicine_name: '', dosage: '', frequency: '', duration: '' });
            this.$nextTick(() => lucide.createIcons());
        },
        removeItem(index) {
            this.items.splice(index, 1);
        }
    }
}
</script>
@endsection
