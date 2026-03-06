<?php

namespace App\Models;

use App\Traits\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Prescription;
use App\Models\TreatmentRecord;
use App\Models\Invoice;

class Patient extends Model
{
    use HasFactory, SoftDeletes, BelongsToClinic;

    protected $fillable = [
        'clinic_id',
        'first_name',
        'last_name',
        'national_id',
        'date_of_birth',
        'gender',
        'email',
        'phone',
        'whatsapp_number',
        'address',
        'chronic_diseases',
        'allergies',
        'blood_type',
        'emergency_contact',
        'notes',
        'referral_source',
        'referred_by_patient_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'chronic_diseases' => 'array',
        'allergies' => 'array',
        'emergency_contact' => 'array',
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function dentalCharts()
    {
        return $this->hasMany(DentalChartRecord::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class)->latest();
    }

    public function treatmentRecords()
    {
        return $this->hasMany(TreatmentRecord::class)->latest();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class)->latest();
    }

    public function referredBy()
    {
        return $this->belongsTo(Patient::class, 'referred_by_patient_id');
    }

    public function referrals()
    {
        return $this->hasMany(Patient::class, 'referred_by_patient_id');
    }

    public function treatmentPlans()
    {
        return $this->hasMany(TreatmentPlan::class)->latest();
    }
}
