<?php

namespace App\Models;

use App\Traits\BelongsToClinic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scopes\ClinicScope;

class TreatmentPlan extends Model
{
    use BelongsToClinic;

    protected $fillable = [
        'patient_id',
        'clinic_id',
        'tooth_number',
        'procedure_name',
        'description',
        'estimated_cost',
        'planned_date',
        'status',
    ];

    protected $casts = [
        'planned_date' => 'date',
        'estimated_cost' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
