@extends('layouts.superadmin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Clinics</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $clinics->total() }} clinics registered</p>
        </div>
        <a href="{{ route('superadmin.clinics.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary/90 transition">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Clinic
        </a>
    </div>

    <div class="glass rounded-2xl shadow-sm dark:bg-gray-800/50 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Clinic</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Patients</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Plan</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($clinics as $clinic)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $clinic->name }}</p>
                        <p class="text-xs text-gray-400">{{ $clinic->address }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                        <p>{{ $clinic->email }}</p>
                        <p class="text-xs text-gray-400">{{ $clinic->phone }}</p>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-700 dark:text-gray-300">{{ $clinic->patients_count }}</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                        {{ $clinic->latestSubscription?->plan?->name ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        @php $status = $clinic->subscription_status; @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                               ($status === 'expired' ? 'bg-red-100 text-red-700' :
                               ($status === 'suspended' ? 'bg-gray-200 text-gray-600' : 'bg-blue-100 text-blue-700')) }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('superadmin.clinics.show', $clinic) }}" class="p-1.5 text-primary hover:bg-primary/10 rounded-lg transition" title="View">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('superadmin.subscriptions.assign', $clinic) }}" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition" title="Assign Plan">
                                <i data-lucide="credit-card" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('superadmin.clinics.edit', $clinic) }}" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">No clinics found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">
            {{ $clinics->links() }}
        </div>
    </div>
</div>
@endsection
