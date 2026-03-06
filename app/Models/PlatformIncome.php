<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformIncome extends Model
{
    protected $table = 'platform_income';

    protected $fillable = [
        'clinic_id',
        'subscription_id',
        'amount',
        'received_date',
        'description',
    ];

    protected $casts = [
        'received_date' => 'date',
        'amount'        => 'decimal:2',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function subscription()
    {
        return $this->belongsTo(ClinicSubscription::class, 'subscription_id');
    }

    public function transaction()
    {
        return $this->morphOne(PlatformTransaction::class, 'reference');
    }
}
