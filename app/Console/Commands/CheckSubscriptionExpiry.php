<?php

namespace App\Console\Commands;

use App\Models\Clinic;
use App\Models\ClinicSubscription;
use App\Models\User;
use App\Notifications\SubscriptionExpired;
use App\Notifications\SubscriptionExpiringSoon;
use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class CheckSubscriptionExpiry extends Command
{
    protected $signature   = 'subscriptions:check-expiry';
    protected $description = 'Check subscription expiry dates and send notifications / update statuses';

    public function handle(SubscriptionService $subscriptionService): int
    {
        // ── Notify: 7 days before expiry ──────────────────────────────────────
        $this->notifyExpiringSoon(7);

        // ── Notify: 3 days before expiry ──────────────────────────────────────
        $this->notifyExpiringSoon(3);

        // ── Mark expired + send notification ──────────────────────────────────
        $expiredToday = ClinicSubscription::where('subscription_status', 'active')
            ->whereDate('subscription_end_date', now()->toDateString())
            ->with(['clinic.users', 'plan'])
            ->get();

        foreach ($expiredToday as $subscription) {
            $subscription->update(['subscription_status' => 'expired']);
            $subscription->clinic->update(['subscription_status' => 'expired']);

            $this->notifyClinicUsers($subscription->clinic, new SubscriptionExpired($subscription));
        }

        // ── Process all status changes (mark older expired, suspend) ──────────
        $stats = $subscriptionService->processExpirations();

        $this->info("Done. Expired: {$stats['expired']}, Suspended: {$stats['suspended']}");

        return Command::SUCCESS;
    }

    private function notifyExpiringSoon(int $days): void
    {
        $subscriptions = ClinicSubscription::expiringInDays($days)
            ->with(['clinic.users', 'plan'])
            ->get();

        foreach ($subscriptions as $subscription) {
            $this->notifyClinicUsers(
                $subscription->clinic,
                new SubscriptionExpiringSoon($subscription, $days)
            );
        }

        $this->line("Notified {$subscriptions->count()} clinic(s) expiring in {$days} days.");
    }

    private function notifyClinicUsers(Clinic $clinic, $notification): void
    {
        $recipients = $clinic->users()
            ->whereIn('role', ['clinic_admin', 'doctor'])
            ->where('is_active', true)
            ->get();

        foreach ($recipients as $user) {
            $user->notify($notification);
        }
    }
}
