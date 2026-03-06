@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Team Members</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Manage clinic staff and their access</p>
    </div>
    <a href="{{ route('users.create') }}" class="px-5 py-2.5 bg-primary text-white rounded-xl font-bold hover:bg-primary/90 transition flex items-center gap-2 shadow-lg">
        <i data-lucide="plus" class="w-4 h-4"></i> Add Team Member
    </a>
</div>

@if (session('success'))
    <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-300 text-sm">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50/80 dark:bg-gray-800/50 uppercase font-bold text-gray-700 dark:text-gray-300 text-xs">
                <tr>
                    <th scope="col" class="px-6 py-4">Name</th>
                    <th scope="col" class="px-6 py-4">Email</th>
                    <th scope="col" class="px-6 py-4">Role</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            @if($user->role === 'clinic_admin') bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300
                            @elseif($user->role === 'doctor') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                            @elseif($user->role === 'accountant') bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300
                            @else bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_active)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">Active</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('users.edit', $user) }}" class="text-primary hover:underline text-xs font-semibold">Edit</a>
                        <form action="{{ route('users.toggle', $user) }}" method="POST" style="display: inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-amber-600 hover:underline text-xs font-semibold">
                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;"
                              onsubmit="return confirm('Delete {{ $user->name }}? This action cannot be undone.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <p class="text-gray-400">No team members yet. <a href="{{ route('users.create') }}" class="text-primary hover:underline">Add one now</a>.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
