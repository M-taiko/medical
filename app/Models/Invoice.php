<?php

namespace App\Models;

use App\Traits\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes, BelongsToClinic;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'visit_id',
        'total_amount',
        'paid_amount',
        'remaining_balance',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function recordPayment(float $amount): void
    {
        $newPaid      = (float) $this->paid_amount + $amount;
        $newBalance   = max(0, (float) $this->total_amount - $newPaid);
        $newStatus    = $newBalance <= 0 ? 'paid' : ($newPaid > 0 ? 'partial' : 'unpaid');

        $this->update([
            'paid_amount'       => $newPaid,
            'remaining_balance' => $newBalance,
            'status'            => $newStatus,
        ]);
    }
}
