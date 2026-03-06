<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function index()
    {
        $clinics = Clinic::withCount('patients')
            ->with('latestSubscription.plan')
            ->latest()
            ->paginate(20);

        return view('superadmin.clinics.index', compact('clinics'));
    }

    public function create()
    {
        return view('superadmin.clinics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $clinic = Clinic::create($validated);

        return redirect()->route('superadmin.clinics.show', $clinic)
                         ->with('success', 'Clinic created. Assign a subscription to activate it.');
    }

    public function show(Clinic $clinic)
    {
        $clinic->load([
            'subscriptions.plan',
            'users',
            'patients',
        ]);

        $activeSubscription = $clinic->activeSubscription;

        return view('superadmin.clinics.show', compact('clinic', 'activeSubscription'));
    }

    public function edit(Clinic $clinic)
    {
        return view('superadmin.clinics.edit', compact('clinic'));
    }

    public function update(Request $request, Clinic $clinic)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $clinic->update($validated);

        return redirect()->route('superadmin.clinics.show', $clinic)
                         ->with('success', 'Clinic updated.');
    }

    public function destroy(Clinic $clinic)
    {
        $clinic->delete();
        return redirect()->route('superadmin.clinics.index')
                         ->with('success', 'Clinic deleted.');
    }
}
