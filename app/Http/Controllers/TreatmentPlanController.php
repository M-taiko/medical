<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\TreatmentPlan;
use Illuminate\Http\Request;

class TreatmentPlanController extends Controller
{
    public function create(Patient $patient)
    {
        $this->authorize('update', $patient);
        return view('treatment-plans.create', compact('patient'));
    }

    public function store(Request $request, Patient $patient)
    {
        $this->authorize('update', $patient);

        $validated = $request->validate([
            'tooth_number' => 'nullable|integer|between:1,32',
            'procedure_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'estimated_cost' => 'nullable|numeric|min:0',
            'planned_date' => 'nullable|date|after:today',
        ]);

        $validated['clinic_id'] = auth()->user()->clinic_id;
        $patient->treatmentPlans()->create($validated);

        return redirect()->route('patients.show', $patient)
                       ->with('success', 'Treatment plan added successfully.');
    }

    public function edit(Patient $patient, TreatmentPlan $plan)
    {
        $this->authorize('update', $patient);
        return view('treatment-plans.edit', compact('patient', 'plan'));
    }

    public function update(Request $request, Patient $patient, TreatmentPlan $plan)
    {
        $this->authorize('update', $patient);

        $validated = $request->validate([
            'tooth_number' => 'nullable|integer|between:1,32',
            'procedure_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'estimated_cost' => 'nullable|numeric|min:0',
            'planned_date' => 'nullable|date',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
        ]);

        $plan->update($validated);

        return redirect()->route('patients.show', $patient)
                       ->with('success', 'Treatment plan updated successfully.');
    }

    public function destroy(Patient $patient, TreatmentPlan $plan)
    {
        $this->authorize('delete', $patient);
        $plan->delete();

        return redirect()->route('patients.show', $patient)
                       ->with('success', 'Treatment plan deleted successfully.');
    }
}
