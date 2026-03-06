<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'logo_path',
        'settings',
        'is_active',
        'subscription_status',
    ];

    protected $casts = [
        'settings'  => 'array',
        'is_active' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(ClinicSubscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(ClinicSubscription::class)
                    ->where('subscription_status', 'active')
                    ->where('subscription_end_date', '>=', now()->toDateString())
                    ->latestOfMany('subscription_start_date');
    }

    public function latestSubscription()
    {
        return $this->hasOne(ClinicSubscription::class)->latestOfMany('subscription_start_date');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === 'active';
    }
}
