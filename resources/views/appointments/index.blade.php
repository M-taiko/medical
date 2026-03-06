@extends('layouts.app')

@section('content')
<!-- Include FullCalendar Core CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<div x-data="{ viewMode: 'calendar', editOpen: false, selectedVisit: null, patients: [], doctors: [] }">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __('appointments.title') }}</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">{{ __('appointments.subtitle') }}</p>
        </div>
        <div class="flex items-center gap-4">
            <!-- View Toggles -->
            <div class="bg-white dark:bg-gray-800 p-1 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex">
                <button @click="viewMode = 'calendar'" :class="{'bg-primary text-white': viewMode === 'calendar', 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white': viewMode !== 'calendar'}" class="px-4 py-2 font-semibold text-sm rounded-lg transition-colors flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4"></i> {{ __('appointments.calendar_view') }}
                </button>
                <button @click="viewMode = 'list'" :class="{'bg-primary text-white': viewMode === 'list', 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white': viewMode !== 'list'}" class="px-4 py-2 font-semibold text-sm rounded-lg transition-colors flex items-center gap-2">
                    <i data-lucide="list" class="w-4 h-4"></i> {{ __('appointments.list_view') }}
                </button>
            </div>

            <a href="{{ route('appointments.create') }}" class="bg-primary hover:bg-opacity-90 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg hover:shadow-primary/30 transition-all">
                <i data-lucide="plus"></i> {{ __('appointments.new') }}
            </a>
        </div>
    </div>

    <!-- CALENDAR VIEW -->
    <div x-show="viewMode === 'calendar'" class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6 overflow-hidden">
        <style>
            .fc-theme-standard td, .fc-theme-standard th, .fc-theme-standard .fc-scrollgrid { border-color: rgba(156, 163, 175, 0.2); }
            .fc-col-header-cell { background-color: rgba(249, 250, 251, 0.5); padding: 10px 0; }
            .dark .fc-col-header-cell { background-color: rgba(31, 41, 55, 0.5); color: #e5e7eb; }
            .dark .fc-daygrid-day-number { color: #e5e7eb; }
            .fc-event { border-radius: 6px; padding: 2px 4px; font-weight: bold; cursor: pointer; transition: transform 0.2s; border: none; }
            .fc-event:hover { transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
            .fc .fc-toolbar-title { font-weight: 800; font-size: 1.5rem; color: inherit; }
            .fc .fc-button-primary { background-color: #4F46E5; border-color: #4F46E5; text-transform: capitalize; font-weight: bold; border-radius: 0.5rem; }
            .fc .fc-button-primary:not(:disabled):active, .fc .fc-button-primary:not(:disabled).fc-button-active { background-color: #4338CA; border-color: #4338CA; }
        </style>
        <div id="calendar" class="min-h-[600px] w-full z-0 font-sans text-gray-900 dark:text-white"></div>
    </div>

    <!-- LIST VIEW -->
    <div x-show="viewMode === 'list'" style="display: none;" class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden mt-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50/80 dark:bg-gray-800/50 uppercase font-bold text-gray-700 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-6 py-4">{{ __('appointments.time') }}</th>
                        <th scope="col" class="px-6 py-4">{{ __('appointments.patient') }}</th>
                        <th scope="col" class="px-6 py-4">{{ __('appointments.doctor') }}</th>
                        <th scope="col" class="px-6 py-4">{{ __('appointments.complaint') }}</th>
                        <th scope="col" class="px-6 py-4">{{ __('appointments.status') }}</th>
                        <th scope="col" class="px-6 py-4 text-right">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($visits as $v)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                            <div class="flex items-center gap-2">
                                <i data-lucide="clock" class="w-4 h-4 text-primary"></i>
                                {{ $v->appointment_time->format('M d, Y h:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $v->patient->first_name }} {{ $v->patient->last_name }}
                        </td>
                        <td class="px-6 py-4">{{ $v->doctor->name }}</td>
                        <td class="px-6 py-4">{{ $v->chief_complaint ?? __('appointments.routine_checkup') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $v->status == 'scheduled' ? 'bg-primary/20 text-primary' : ($v->status == 'completed' ? 'bg-emerald-500/20 text-emerald-600' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($v->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('dental-chart.show', $v->patient_id) }}" class="text-primary hover:underline text-xs font-semibold">Chart</a>
                            <a href="{{ route('appointments.edit', $v) }}" class="text-amber-600 hover:underline text-xs font-semibold">Edit</a>
                            @if($v->status === 'scheduled')
                            <form action="{{ route('visits.update-status', $v) }}" method="POST" style="display: inline;" onsubmit="return confirm('Mark as completed?');">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="text-emerald-600 hover:underline text-xs font-semibold">Complete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i data-lucide="calendar" class="w-12 h-12 mx-auto text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-medium">{{ __('appointments.no_appointments') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($visits->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
            {{ $visits->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: '{{ route("appointments.index") }}',
            eventClick: function(info) {
                var visitId = info.event.id;
                var editUrl = '{{ route("appointments.edit", ":id") }}'.replace(':id', visitId);
                window.location.href = editUrl;
            },
            height: 'auto',
        });

        calendar.render();
    });
</script>
@endsection
