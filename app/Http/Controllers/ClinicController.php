<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function index()
    {
        $clinics = Clinic::withCount('patients')->latest()->paginate(15);
        return view('clinics.index', compact('clinics'));
    }

    public function show(Clinic $clinic)
    {
        $clinic->loadCount(['patients', 'users']);
        return view('clinics.show', compact('clinic'));
    }

    public function create()
    {
        return view('clinics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $clinic = Clinic::create($validated);

        return redirect()->route('clinics.show', $clinic)
            ->with('success', __('clinics.created_success'));
    }

    public function edit(Clinic $clinic)
    {
        return view('clinics.edit', compact('clinic'));
    }

    public function update(Request $request, Clinic $clinic)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:255',
            'address'  => 'nullable|string|max:500',
            'currency' => 'nullable|string|max:5',
            'timezone' => 'nullable|string',
        ]);

        $clinic->update([
            'name'    => $validated['name'],
            'phone'   => $validated['phone'],
            'email'   => $validated['email'],
            'address' => $validated['address'],
            'settings' => [
                'currency' => $validated['currency'] ?? 'USD',
                'timezone' => $validated['timezone'] ?? 'UTC',
            ],
        ]);

        return back()->with('success', __('clinics.updated_success'));
    }

    public function destroy(Clinic $clinic)
    {
        $clinic->delete();
        return redirect()->route('clinics.index')
            ->with('success', __('clinics.deleted_success'));
    }
}
