<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::where('clinic_id', auth()->user()->clinic_id)
            ->where('role', '!=', 'superadmin')
            ->latest()
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateUser($request);

        $validated['clinic_id'] = auth()->user()->clinic_id;
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully! They can now log in.');
    }

    public function edit(User $user)
    {
        // Ensure clinic_admin can only edit their own clinic's users
        if ($user->clinic_id !== auth()->user()->clinic_id) {
            abort(403);
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Ensure clinic_admin can only edit their own clinic's users
        if ($user->clinic_id !== auth()->user()->clinic_id) {
            abort(403);
        }

        $validated = $this->validateUser($request, $user->id);

        // Only update password if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(Request $request, User $user)
    {
        // Ensure clinic_admin can only delete their own clinic's users
        if ($user->clinic_id !== auth()->user()->clinic_id) {
            abort(403);
        }

        // Prevent deleting the last clinic_admin
        if ($user->role === 'clinic_admin') {
            $adminCount = User::where('clinic_id', auth()->user()->clinic_id)
                ->where('role', 'clinic_admin')
                ->count();

            if ($adminCount <= 1) {
                return redirect()->route('users.index')
                    ->with('error', 'Cannot delete the last clinic admin. Assign another admin first.');
            }
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('success', "$userName has been deleted.");
    }

    public function toggleActive(User $user)
    {
        // Ensure clinic_admin can only toggle their own clinic's users
        if ($user->clinic_id !== auth()->user()->clinic_id) {
            abort(403);
        }

        // Prevent deactivating the last clinic_admin
        if ($user->role === 'clinic_admin' && $user->is_active && !$this->hasOtherActiveAdmin($user)) {
            return redirect()->route('users.index')
                ->with('error', 'Cannot deactivate the last active clinic admin.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->route('users.index')->with('success', "{$user->name} has been $status.");
    }

    protected function validateUser(Request $request, ?int $userId = null)
    {
        $passwordRule = $userId ? 'nullable|min:8' : 'required|min:8';

        return $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => "required|email|unique:users,email" . ($userId ? ",$userId" : ''),
            'password' => $passwordRule,
            'role'     => 'required|in:doctor,receptionist,accountant,clinic_admin',
        ], [
            'password.required' => 'Password is required for new users.',
            'password.min'      => 'Password must be at least 8 characters.',
            'role.in'           => 'Invalid role selected.',
        ]);
    }

    protected function hasOtherActiveAdmin(User $user): bool
    {
        return User::where('clinic_id', auth()->user()->clinic_id)
            ->where('role', 'clinic_admin')
            ->where('is_active', true)
            ->where('id', '!=', $user->id)
            ->exists();
    }
}
