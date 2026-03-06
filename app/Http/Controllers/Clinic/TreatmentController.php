<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\TreatmentRecord;
use App\Models\Visit;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index()
    {
        $treatments = TreatmentRecord::with(['patient', 'doctor', 'visit'])
            ->latest()
            ->paginate(20);

        return view('clinic.treatments.index', compact('treatments'));
    }

    public function create(Visit $visit)
    {
        $visit->load('patient');
        return view('clinic.treatments.create', compact('visit'));
    }

    public function store(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'treatment_type' => 'required|string|max:255',
            'teeth_involved' => 'nullable|array',
            'cost'           => 'required|numeric|min:0',
            'status'         => 'required|in:planned,in_progress,completed',
            'notes'          => 'nullable|string',
        ]);

        TreatmentRecord::create(array_merge($validated, [
            'patient_id' => $visit->patient_id,
            'visit_id'   => $visit->id,
            'doctor_id'  => auth()->id(),
        ]));

        return redirect()->route('patients.show', $visit->patient_id)
                         ->with('success', 'Treatment record added.');
    }
}
