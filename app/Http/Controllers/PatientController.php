<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $patients = Patient::withCount('visits')
            ->when($search, function ($q, $s) {
                $q->where(function ($q) use ($s) {
                    $q->where('first_name', 'like', "%$s%")
                      ->orWhere('last_name', 'like', "%$s%")
                      ->orWhere('phone', 'like', "%$s%")
                      ->orWhere('whatsapp_number', 'like', "%$s%")
                      ->orWhere('national_id', 'like', "%$s%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('patients.index', compact('patients', 'search'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePatient($request);
        Patient::create($validated);
        return redirect()->route('patients.index')->with('success', 'Patient created successfully!');
    }

    public function show(Patient $patient)
    {
        $patient->load([
            'visits.doctor',
            'visits.invoice',
            'dentalCharts',
            'clinic',
            'prescriptions.doctor',
            'prescriptions.items',
            'treatmentRecords.doctor',
        ]);
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $this->validatePatient($request, $patient);
        $patient->update($validated);
        return redirect()->route('patients.show', $patient)->with('success', 'Patient updated successfully!');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted.');
    }

    private function validatePatient(Request $request, ?Patient $patient = null): array
    {
        $validated = $request->validate([
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'nullable|string|max:255',
            'national_id'     => 'nullable|string|max:50',
            'gender'          => 'nullable|in:Male,Female',
            'date_of_birth'   => 'nullable|date',
            'blood_type'      => 'nullable|string|max:5',
            'phone'           => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string|max:500',
            'chronic_diseases'=> 'nullable|string',
            'allergies'       => 'nullable|string',
            'notes'           => 'nullable|string',
            'emergency_contact_name'  => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        if (!empty($validated['chronic_diseases'])) {
            $validated['chronic_diseases'] = array_filter(array_map('trim', explode(',', $validated['chronic_diseases'])));
        } else {
            $validated['chronic_diseases'] = [];
        }

        if (!empty($validated['allergies'])) {
            $validated['allergies'] = array_filter(array_map('trim', explode(',', $validated['allergies'])));
        } else {
            $validated['allergies'] = [];
        }

        $validated['emergency_contact'] = [
            'name'  => $validated['emergency_contact_name'] ?? null,
            'phone' => $validated['emergency_contact_phone'] ?? null,
        ];
        unset($validated['emergency_contact_name'], $validated['emergency_contact_phone']);

        return $validated;
    }
}
