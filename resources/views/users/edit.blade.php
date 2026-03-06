@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('users.index') }}" class="text-sm text-primary hover:underline flex items-center gap-1 mb-1"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Edit Team Member</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $user->name }}</p>
    </div>
</div>

@if($errors->any())
    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-sm">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="glass rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-8 max-w-2xl">
    <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Full Name *</label>
            <input type="text" name="name" required value="{{ old('name', $user->name) }}"
                   class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Email Address *</label>
            <input type="email" name="email" required value="{{ old('email', $user->email) }}"
                   class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Password</label>
            <input type="password" name="password" minlength="8" value="{{ old('password') }}"
                   class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave blank to keep current password</p>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Role *</label>
            <select name="role" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none transition dark:text-white">
                <option value="clinic_admin" {{ old('role', $user->role) === 'clinic_admin' ? 'selected' : '' }}>Clinic Admin (Full access)</option>
                <option value="doctor" {{ old('role', $user->role) === 'doctor' ? 'selected' : '' }}>Doctor (Clinical access)</option>
                <option value="receptionist" {{ old('role', $user->role) === 'receptionist' ? 'selected' : '' }}>Receptionist (Front desk)</option>
                <option value="accountant" {{ old('role', $user->role) === 'accountant' ? 'selected' : '' }}>Accountant (Financial access)</option>
            </select>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Save Changes
            </button>
            <a href="{{ route('users.index') }}" class="px-8 py-3 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
