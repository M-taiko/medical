<?php

namespace App\Notifications;

use App\Models\ClinicSubscription;
use Illuminate\Notifications\Notification;

class SubscriptionExpired extends Notification
{
    public function __construct(private ClinicSubscription $subscription) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'subscription_expired',
            'expiry_date' => $this->subscription->subscription_end_date->toDateString(),
            'clinic_name' => $this->subscription->clinic->name,
            'plan_name'   => $this->subscription->plan->name,
            'message'     => 'Your clinic subscription has expired. Please contact support to renew your subscription and restore access.',
        ];
    }
}
