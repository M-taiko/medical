<?php

namespace App\Models;

use App\Traits\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use HasFactory, SoftDeletes, BelongsToClinic;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'doctor_id',
        'appointment_time',
        'status',
        'chief_complaint',
        'diagnosis',
        'follow_up_date',
    ];

    protected $casts = [
        'appointment_time' => 'datetime',
        'follow_up_date'   => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function dentalCharts()
    {
        return $this->hasMany(DentalChartRecord::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }

    public function treatmentRecords()
    {
        return $this->hasMany(TreatmentRecord::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
