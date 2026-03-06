<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicSubscription extends Model
{
    protected $fillable = [
        'clinic_id',
        'plan_id',
        'subscription_start_date',
        'subscription_end_date',
        'renewal_date',
        'subscription_status',
        'price_paid',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'subscription_start_date' => 'date',
        'subscription_end_date'   => 'date',
        'renewal_date'            => 'date',
        'price_paid'              => 'decimal:2',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function income()
    {
        return $this->hasOne(PlatformIncome::class, 'subscription_id');
    }

    public function isActive(): bool
    {
        return $this->subscription_status === 'active'
            && $this->subscription_end_date->isFuture();
    }

    public function daysRemaining(): int
    {
        return max(0, now()->diffInDays($this->subscription_end_date, false));
    }

    public function scopeActive($query)
    {
        return $query->where('subscription_status', 'active')
                     ->where('subscription_end_date', '>=', now()->toDateString());
    }

    public function scopeExpired($query)
    {
        return $query->where('subscription_status', 'expired');
    }

    public function scopeExpiringInDays($query, int $days)
    {
        $target = now()->addDays($days)->toDateString();
        return $query->where('subscription_status', 'active')
                     ->whereDate('subscription_end_date', $target);
    }
}
