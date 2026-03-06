@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Treatment Records</h1>

    <div class="glass rounded-2xl shadow-sm dark:bg-gray-800/50 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Treatment</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Doctor</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Cost</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($treatments as $t)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                    <td class="px-6 py-4 font-semibold text-gray-800 dark:text-white">
                        <a href="{{ route('patients.show', $t->patient) }}" class="hover:text-primary">{{ $t->patient->first_name }} {{ $t->patient->last_name }}</a>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $t->treatment_type }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $t->doctor->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $t->status === 'completed' ? 'bg-green-100 text-green-700' :
                               ($t->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst(str_replace('_', ' ', $t->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-medium text-gray-700 dark:text-gray-300">${{ number_format($t->cost, 2) }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $t->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No treatment records.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $treatments->links() }}</div>
    </div>
</div>
@endsection
