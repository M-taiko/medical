@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('patients.show', $prescription->patient) }}" class="text-sm text-primary hover:underline flex items-center gap-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Patient</a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Prescription #{{ $prescription->id }}</h1>
            <p class="text-sm text-gray-500">{{ $prescription->prescribed_at->format('F j, Y') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('prescriptions.pdf', $prescription) }}" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition flex items-center gap-2 text-sm">
                <i data-lucide="download" class="w-4 h-4"></i> PDF
            </a>
            @if($prescription->patient->whatsapp_number)
            <form action="{{ route('prescriptions.whatsapp', $prescription) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition flex items-center gap-2 text-sm">
                    <i data-lucide="send" class="w-4 h-4"></i> WhatsApp
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="glass rounded-2xl p-6 shadow-sm dark:bg-gray-800/50 space-y-5">
        <!-- Patient + Doctor -->
        <div class="grid grid-cols-2 gap-6 pb-5 border-b border-gray-100 dark:border-gray-700">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Patient</p>
                <p class="font-bold text-gray-800 dark:text-white">{{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</p>
                <p class="text-sm text-gray-500">{{ $prescription->patient->phone }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Prescribing Doctor</p>
                <p class="font-bold text-gray-800 dark:text-white">{{ $prescription->doctor->name }}</p>
            </div>
        </div>

        <!-- Diagnosis -->
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Diagnosis</p>
            <p class="text-gray-800 dark:text-gray-200">{{ $prescription->diagnosis }}</p>
        </div>

        <!-- Medicines -->
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-3">Medicines</p>
            <div class="space-y-3">
                @foreach($prescription->items as $item)
                <div class="p-4 bg-primary/5 dark:bg-primary/10 rounded-xl border border-primary/10">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-bold text-gray-800 dark:text-white">{{ $item->medicine_name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-0.5">{{ $item->dosage }} · {{ $item->frequency }} · {{ $item->duration }}</p>
                        </div>
                    </div>
                    @if($item->notes)
                    <p class="text-xs text-gray-500 mt-2 italic">Note: {{ $item->notes }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Instructions -->
        @if($prescription->instructions)
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Additional Instructions</p>
            <p class="text-gray-700 dark:text-gray-300 text-sm bg-gray-50 dark:bg-gray-700 p-3 rounded-xl">{{ $prescription->instructions }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
