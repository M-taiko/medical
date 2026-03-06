@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between flex-wrap gap-4">
    <div>
        <a href="{{ route('patients.index') }}" class="text-sm text-primary hover:underline flex items-center gap-1 mb-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Patients</a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $patient->first_name }} {{ $patient->last_name }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Patient #{{ $patient->id }}</p>
    </div>
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('dental-chart.show', $patient) }}" class="px-4 py-2 bg-teal-600 text-white rounded-xl font-semibold hover:bg-teal-700 transition flex items-center gap-2">
            <i data-lucide="tooth" class="w-4 h-4"></i> Dental Chart
        </a>
        <a href="{{ route('prescriptions.create', $patient) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition flex items-center gap-2">
            <i data-lucide="file-text" class="w-4 h-4"></i> New Prescription
        </a>
        <a href="{{ route('appointments.create') }}?patient_id={{ $patient->id }}" class="px-4 py-2 bg-primary text-white rounded-xl font-semibold hover:bg-primary/90 transition flex items-center gap-2">
            <i data-lucide="calendar-plus" class="w-4 h-4"></i> Book Visit
        </a>
        <a href="{{ route('patients.edit', $patient) }}" class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition flex items-center gap-2">
            <i data-lucide="edit" class="w-4 h-4"></i> Edit
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 rounded-xl">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT COLUMN: Personal & Medical --}}
    <div class="space-y-6">

        {{-- Personal Info --}}
        <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4 text-primary"></i> Personal Information
            </h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Full Name</dt>
                    <dd class="font-semibold text-gray-900 dark:text-white">{{ $patient->first_name }} {{ $patient->last_name }}</dd>
                </div>
                @if($patient->national_id)
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">National ID</dt>
                    <dd class="font-semibold text-gray-900 dark:text-white">{{ $patient->national_id }}</dd>
                </div>
                @endif
                @if($patient->gender)
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Gender</dt>
                    <dd class="font-semibold text-gray-900 dark:text-white">{{ $patient->gender }}</dd>
                </div>
                @endif
                @if($patient->date_of_birth)
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Date of Birth</dt>
                    <dd class="font-semibold text-gray-900 dark:text-white">{{ $patient->date_of_birth->format('M d, Y') }}</dd>
                </div>
                @endif
                @if($patient->blood_type)
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Blood Type</dt>
                    <dd><span class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-2 py-0.5 rounded-lg font-bold text-xs">{{ $patient->blood_type }}</span></dd>
                </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Phone</dt>
                    <dd class="font-semibold text-gray-900 dark:text-white">{{ $patient->phone }}</dd>
                </div>
                @if($patient->whatsapp_number)
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">WhatsApp</dt>
                    <dd class="font-semibold text-emerald-600">{{ $patient->whatsapp_number }}</dd>
                </div>
                @endif
                @if($patient->email)
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Email</dt>
                    <dd class="font-semibold text-gray-900 dark:text-white">{{ $patient->email }}</dd>
                </div>
                @endif
                @if($patient->address)
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Address</dt>
                    <dd class="font-semibold text-gray-900 dark:text-white text-right max-w-[60%]">{{ $patient->address }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Medical Alerts --}}
        @if(!empty($patient->allergies) || !empty($patient->chronic_diseases))
        <div class="glass rounded-2xl border border-red-200 dark:border-red-900 shadow-sm p-6 bg-red-50/30 dark:bg-red-900/10">
            <h3 class="font-bold text-red-800 dark:text-red-300 mb-4 flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i> Medical Alerts
            </h3>
            @if(!empty($patient->allergies))
            <div class="mb-3">
                <p class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wide mb-1">Allergies</p>
                <div class="flex flex-wrap gap-1">
                    @foreach($patient->allergies as $a)
                        <span class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-2 py-0.5 rounded-lg text-xs font-semibold">{{ $a }}</span>
                    @endforeach
                </div>
            </div>
            @endif
            @if(!empty($patient->chronic_diseases))
            <div>
                <p class="text-xs font-bold text-orange-600 dark:text-orange-400 uppercase tracking-wide mb-1">Chronic Conditions</p>
                <div class="flex flex-wrap gap-1">
                    @foreach($patient->chronic_diseases as $d)
                        <span class="bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 px-2 py-0.5 rounded-lg text-xs font-semibold">{{ $d }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Emergency Contact --}}
        @if(!empty($patient->emergency_contact['name']))
        <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                <i data-lucide="phone-call" class="w-4 h-4 text-primary"></i> Emergency Contact
            </h3>
            <p class="font-semibold text-gray-900 dark:text-white">{{ $patient->emergency_contact['name'] }}</p>
            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $patient->emergency_contact['phone'] ?? '' }}</p>
        </div>
        @endif

    </div>

    {{-- RIGHT COLUMN: Visits, Prescriptions, Treatments --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Visit History --}}
        <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4 text-primary"></i> Visit History
                    <span class="bg-primary/10 text-primary text-xs font-bold px-2 py-0.5 rounded-full">{{ $patient->visits->count() }}</span>
                </h3>
                <a href="{{ route('appointments.create') }}?patient_id={{ $patient->id }}" class="text-xs text-primary hover:underline font-semibold">+ Book Visit</a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($patient->visits->take(10) as $visit)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5">
                            @if($visit->status === 'completed')
                                <span class="w-2 h-2 rounded-full bg-emerald-500 block mt-1.5"></span>
                            @elseif($visit->status === 'scheduled')
                                <span class="w-2 h-2 rounded-full bg-blue-500 block mt-1.5"></span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-gray-400 block mt-1.5"></span>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $visit->appointment_time->format('M d, Y — h:i A') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Dr. {{ $visit->doctor->name ?? 'N/A' }} · {{ $visit->chief_complaint ?? 'Routine' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                            @if($visit->status === 'completed') bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300
                            @elseif($visit->status === 'scheduled') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                            @else bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 @endif">
                            {{ ucfirst($visit->status) }}
                        </span>
                        @if($visit->status === 'completed' && !$visit->invoice)
                            <a href="{{ route('invoices.create', $visit) }}" class="text-xs px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 font-semibold hover:bg-amber-200 transition">Invoice</a>
                        @elseif($visit->invoice)
                            <a href="{{ route('invoices.show', $visit->invoice) }}" class="text-xs text-primary hover:underline font-semibold">Invoice #{{ $visit->invoice->id }}</a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-400 dark:text-gray-600 text-sm">No visits recorded yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Prescriptions --}}
        <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4 text-indigo-500"></i> Prescriptions
                    <span class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $patient->prescriptions->count() }}</span>
                </h3>
                <a href="{{ route('prescriptions.create', $patient) }}" class="text-xs text-primary hover:underline font-semibold">+ New</a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($patient->prescriptions->take(5) as $rx)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $rx->prescribed_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Dr. {{ $rx->doctor->name ?? 'N/A' }} · {{ $rx->items->count() }} medicine(s)</p>
                    </div>
                    <a href="{{ route('prescriptions.show', $rx) }}" class="text-xs text-primary hover:underline font-semibold">View</a>
                </div>
                @empty
                <div class="p-6 text-center text-gray-400 dark:text-gray-600 text-sm">No prescriptions yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Treatment Records --}}
        <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="activity" class="w-4 h-4 text-teal-500"></i> Treatment Records
                    <span class="bg-teal-100 dark:bg-teal-900/30 text-teal-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $patient->treatmentRecords->count() }}</span>
                </h3>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($patient->treatmentRecords->take(5) as $tr)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $tr->treatment_type }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Dr. {{ $tr->doctor->name ?? 'N/A' }} · Teeth: {{ implode(', ', $tr->teeth_involved ?? []) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900 dark:text-white text-sm">${{ number_format($tr->cost, 2) }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                                @if($tr->status === 'completed') bg-emerald-100 text-emerald-700
                                @elseif($tr->status === 'in_progress') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ ucfirst(str_replace('_', ' ', $tr->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-400 dark:text-gray-600 text-sm">No treatment records yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Treatment Plans --}}
        <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="w-4 h-4 text-amber-500"></i> Treatment Plans
                    <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $patient->treatmentPlans->count() }}</span>
                </h3>
                <a href="{{ route('treatment-plans.create', $patient) }}" class="text-xs text-primary hover:underline font-semibold">+ Add Plan</a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($patient->treatmentPlans->take(5) as $plan)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white text-sm">
                            @if($plan->tooth_number)
                                Tooth #{{ $plan->tooth_number }} —
                            @endif
                            {{ $plan->procedure_name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            @if($plan->planned_date)
                                Planned: {{ $plan->planned_date->format('M d, Y') }}
                            @endif
                            @if($plan->estimated_cost)
                                · Est. ${{'$' . number_format($plan->estimated_cost, 2) }}
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                            @if($plan->status === 'completed') bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300
                            @elseif($plan->status === 'in_progress') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                            @elseif($plan->status === 'planned') bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300
                            @else bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 @endif">
                            {{ ucfirst(str_replace('_', ' ', $plan->status)) }}
                        </span>
                        <a href="{{ route('treatment-plans.edit', [$patient, $plan]) }}" class="text-xs text-primary hover:underline font-semibold">Edit</a>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-400 dark:text-gray-600 text-sm">No treatment plans yet.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
