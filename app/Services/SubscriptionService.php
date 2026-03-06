<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\ClinicSubscription;
use App\Models\PlatformIncome;
use App\Models\PlatformTransaction;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    /**
     * Assign a plan to a clinic and record the income.
     */
    public function assignPlan(Clinic $clinic, SubscriptionPlan $plan, float $pricePaid, ?string $notes = null): ClinicSubscription
    {
        return DB::transaction(function () use ($clinic, $plan, $pricePaid, $notes) {
            $start = Carbon::today();
            $end   = $start->copy()->addMonths($plan->duration_months);

            $subscription = ClinicSubscription::create([
                'clinic_id'                => $clinic->id,
                'plan_id'                  => $plan->id,
                'subscription_start_date'  => $start,
                'subscription_end_date'    => $end,
                'renewal_date'             => $end,
                'subscription_status'      => 'active',
                'price_paid'               => $pricePaid,
                'notes'                    => $notes,
                'created_by'               => auth()->id(),
            ]);

            $clinic->update(['subscription_status' => 'active', 'is_active' => true]);

            $income = PlatformIncome::create([
                'clinic_id'       => $clinic->id,
                'subscription_id' => $subscription->id,
                'amount'          => $pricePaid,
                'received_date'   => $start,
                'description'     => "Subscription: {$plan->name} for {$clinic->name}",
            ]);

            PlatformTransaction::create([
                'type'             => 'income',
                'reference_id'     => $income->id,
                'reference_type'   => PlatformIncome::class,
                'amount'           => $pricePaid,
                'transaction_date' => $start,
                'description'      => $income->description,
            ]);

            return $subscription;
        });
    }

    /**
     * Renew a clinic subscription (creates a new subscription record).
     */
    public function renew(Clinic $clinic, SubscriptionPlan $plan, float $pricePaid, ?string $notes = null): ClinicSubscription
    {
        // New subscription starts from today or from the end of current active one
        $existing = $clinic->activeSubscription;
        $start = $existing && $existing->subscription_end_date->isFuture()
            ? $existing->subscription_end_date->addDay()
            : Carbon::today();

        return DB::transaction(function () use ($clinic, $plan, $pricePaid, $notes, $start) {
            $end = $start->copy()->addMonths($plan->duration_months);

            $subscription = ClinicSubscription::create([
                'clinic_id'                => $clinic->id,
                'plan_id'                  => $plan->id,
                'subscription_start_date'  => $start,
                'subscription_end_date'    => $end,
                'renewal_date'             => $end,
                'subscription_status'      => 'active',
                'price_paid'               => $pricePaid,
                'notes'                    => $notes,
                'created_by'               => auth()->id(),
            ]);

            $clinic->update(['subscription_status' => 'active', 'is_active' => true]);

            $income = PlatformIncome::create([
                'clinic_id'       => $clinic->id,
                'subscription_id' => $subscription->id,
                'amount'          => $pricePaid,
                'received_date'   => Carbon::today(),
                'description'     => "Renewal: {$plan->name} for {$clinic->name}",
            ]);

            PlatformTransaction::create([
                'type'             => 'income',
                'reference_id'     => $income->id,
                'reference_type'   => PlatformIncome::class,
                'amount'           => $pricePaid,
                'transaction_date' => Carbon::today(),
                'description'      => $income->description,
            ]);

            return $subscription;
        });
    }

    /**
     * Suspend a clinic (manual or after grace period).
     */
    public function suspend(Clinic $clinic): void
    {
        $clinic->update(['subscription_status' => 'suspended', 'is_active' => false]);

        ClinicSubscription::where('clinic_id', $clinic->id)
            ->where('subscription_status', 'expired')
            ->update(['subscription_status' => 'suspended']);
    }

    /**
     * Process all expiring/expired subscriptions (called by scheduler).
     */
    public function processExpirations(): array
    {
        $stats = ['expired' => 0, 'suspended' => 0];

        // Mark expired subscriptions
        $nowExpired = ClinicSubscription::where('subscription_status', 'active')
            ->whereDate('subscription_end_date', '<', now()->toDateString())
            ->get();

        foreach ($nowExpired as $sub) {
            $sub->update(['subscription_status' => 'expired']);
            $sub->clinic->update(['subscription_status' => 'expired']);
            $stats['expired']++;
        }

        // Suspend clinics where subscription expired > 7 days ago
        $toSuspend = ClinicSubscription::where('subscription_status', 'expired')
            ->whereDate('subscription_end_date', '<', now()->subDays(7)->toDateString())
            ->get();

        foreach ($toSuspend as $sub) {
            $sub->update(['subscription_status' => 'suspended']);
            $sub->clinic->update(['subscription_status' => 'suspended', 'is_active' => false]);
            $stats['suspended']++;
        }

        return $stats;
    }
}
