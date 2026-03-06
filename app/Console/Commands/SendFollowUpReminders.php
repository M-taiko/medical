<?php

namespace App\Console\Commands;

use App\Models\Visit;
use App\Notifications\FollowUpReminder;
use Illuminate\Console\Command;

class SendFollowUpReminders extends Command
{
    protected $signature = 'appointments:send-followup-reminders';

    protected $description = 'Send WhatsApp and database reminders for upcoming follow-up appointments (2 days before)';

    public function handle()
    {
        $targetDate = now()->addDays(2)->toDateString();

        $visits = Visit::with(['patient', 'doctor'])
            ->whereNotNull('follow_up_date')
            ->whereDate('follow_up_date', $targetDate)
            ->get();

        $this->info("Found {$visits->count()} visits with follow-ups on {$targetDate}");

        foreach ($visits as $visit) {
            try {
                $visit->patient->notify(new FollowUpReminder($visit));
                $this->line("✓ Reminder sent for {$visit->patient->first_name} {$visit->patient->last_name}");
            } catch (\Exception $e) {
                $this->error("✗ Failed to send reminder for visit {$visit->id}: {$e->getMessage()}");
            }
        }

        $this->info('Follow-up reminders sent successfully.');
    }
}
