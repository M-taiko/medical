<?php

namespace App\Services;

use App\Models\DentalChartRecord;
use Illuminate\Support\Facades\DB;

class DentalChartService
{
    /**
     * Save a dental chart record after validating and calculating stuff.
     */
    public function saveRecord(array $data)
    {
        return DB::transaction(function () use ($data) {
            $record = DentalChartRecord::create([
                'patient_id' => $data['patient_id'],
                'visit_id' => $data['visit_id'] ?? null,
                'doctor_id' => auth()->id(),
                'tooth_number' => $data['tooth_number'],
                'condition' => $data['condition'],
                'findings' => $data['findings'] ?? null,
                'treatment' => $data['treatment'] ?? null,
                'price' => $data['price'] ?? 0,
                'notes' => $data['notes'] ?? null,
            ]);

            // Optional: Deduct stock automatically if a treatment template is linked
            // $this->inventoryService->deductStockForTreatment($data['treatment']);

            return $record;
        });
    }

    public function getPatientChart($patientId)
    {
        // Get the latest condition for each tooth for the patient
        return DentalChartRecord::where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('tooth_number')
            ->values();
    }
}
