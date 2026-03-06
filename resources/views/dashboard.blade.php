@extends('layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __('dashboard.title') }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ __('dashboard.subtitle') }}</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="bg-primary/10 text-primary px-4 py-2 rounded-xl font-bold flex items-center gap-2">
            <i data-lucide="calendar"></i> {{ date('F j, Y') }}
        </span>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 bg-blue-500/10 w-24 h-24 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('dashboard.total_patients') }}</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalPatients) }}</h3>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="text-sm text-green-500 font-medium flex items-center gap-1">
            <i data-lucide="trending-up" class="w-4 h-4"></i> +12% {{ __('dashboard.from_last_month', ['pct' => 12]) }}
        </div>
    </div>

    <div class="glass p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 bg-emerald-500/10 w-24 h-24 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('dashboard.appointments_today') }}</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($todayVisits) }}</h3>
            </div>
            <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl">
                <i data-lucide="calendar-check" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">
            {{ __('dashboard.remaining', ['n' => 3]) }}
        </div>
    </div>

    <div class="glass p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 bg-purple-500/10 w-24 h-24 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('dashboard.monthly_revenue') }}</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">${{ number_format($monthlyRevenue, 2) }}</h3>
            </div>
            <div class="p-3 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl">
                <i data-lucide="dollar-sign" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="text-sm text-green-500 font-medium flex items-center gap-1">
            <i data-lucide="trending-up" class="w-4 h-4"></i> +8% {{ __('dashboard.from_last_month', ['pct' => 8]) }}
        </div>
    </div>

    <div class="glass p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 bg-orange-500/10 w-24 h-24 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('dashboard.treatments_done') }}</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($monthlyTreatments) }}</h3>
            </div>
            <div class="p-3 bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-xl">
                <i data-lucide="activity" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="text-sm text-gray-500 dark:text-gray-400 font-medium flex items-center gap-1">
            {{ __('dashboard.current_month') }}
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Upcoming Appointments -->
    <div class="lg:col-span-2 glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i data-lucide="clock" class="text-primary w-5 h-5"></i> Upcoming {{ __('dashboard.appointments_today') }}
            </h3>
            <a href="#" class="text-sm font-semibold text-primary hover:text-secondary transition-colors">{{ __('common.view_all') }}</a>
        </div>
        
        <div class="p-0">
            @forelse($upcomingVisits as $visit)
            <div class="p-4 border-b border-gray-50 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-primary/20 to-secondary/20 flex items-center justify-center text-primary font-bold">
                        {{ substr($visit->patient->first_name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $visit->patient->first_name }} {{ $visit->patient->last_name }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.chief_complaint') }}: {{ $visit->chief_complaint ?? __('dashboard.routine_checkup') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-gray-900 dark:text-white">{{ $visit->appointment_time->format('h:i A') }}</div>
                    <a href="{{ route('dental-chart.show', $visit->patient) }}" class="text-sm text-primary hover:underline font-medium">{{ __('dashboard.open_chart') }} →</a>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                <i data-lucide="calendar" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                <p>{{ __('dashboard.no_appointments') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- {{ __('dashboard.quick_actions') }} -->
    <div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">{{ __('dashboard.quick_actions') }}</h3>
        
        <div class="space-y-4">
            <a href="{{ route('patients.index') }}" class="group flex items-center p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 hover:border-primary/50 hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors mr-4">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white">{{ __('dashboard.patients_directory') }}</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('dashboard.patients_desc') }}</p>
                </div>
            </a>

            <button class="w-full group flex items-center p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 hover:border-secondary/50 hover:shadow-md transition-all text-left">
                <div class="w-10 h-10 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center group-hover:bg-secondary group-hover:text-white transition-colors mr-4">
                    <i data-lucide="calendar-plus" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white">{{ __('dashboard.new_appointment') }}</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('dashboard.new_appointment_desc') }}</p>
                </div>
            </button>

            <button class="w-full group flex items-center p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 hover:border-emerald-500/50 hover:shadow-md transition-all text-left">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-500 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors mr-4">
                    <i data-lucide="receipt" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white">{{ __('dashboard.create_invoice') }}</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('dashboard.create_invoice_desc') }}</p>
                </div>
            </button>
        </div>
    </div>
</div>
@endsection
