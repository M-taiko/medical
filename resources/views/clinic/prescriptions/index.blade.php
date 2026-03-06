@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Prescriptions</h1>
    </div>

    <div class="glass rounded-2xl shadow-sm dark:bg-gray-800/50 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Doctor</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Diagnosis</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Medicines</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($prescriptions as $rx)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                    <td class="px-6 py-4 font-semibold text-gray-800 dark:text-white">
                        <a href="{{ route('patients.show', $rx->patient) }}" class="hover:text-primary">{{ $rx->patient->first_name }} {{ $rx->patient->last_name }}</a>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $rx->doctor->name }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300 max-w-xs truncate">{{ $rx->diagnosis }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $rx->prescribed_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $rx->items->count() }} item(s)</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('prescriptions.show', $rx) }}" class="text-primary hover:underline text-sm">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No prescriptions found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $prescriptions->links() }}</div>
    </div>
</div>
@endsection
