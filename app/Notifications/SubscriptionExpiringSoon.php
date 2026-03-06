<?php

namespace App\Notifications;

use App\Models\ClinicSubscription;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringSoon extends Notification
{
    public function __construct(
        private ClinicSubscription $subscription,
        private int $daysRemaining
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'            => 'subscription_expiring',
            'days_remaining'  => $this->daysRemaining,
            'expiry_date'     => $this->subscription->subscription_end_date->toDateString(),
            'clinic_name'     => $this->subscription->clinic->name,
            'plan_name'       => $this->subscription->plan->name,
            'message'         => "Your clinic subscription will expire in {$this->daysRemaining} day(s). Please renew your subscription to avoid service interruption.",
        ];
    }
}
