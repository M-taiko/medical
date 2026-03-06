<?php

namespace App\Notifications;

use App\Models\Visit;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;

class FollowUpReminder extends Notification
{
    use Queueable;

    protected $visit;

    public function __construct(Visit $visit)
    {
        $this->visit = $visit;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'whatsapp'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'visit_id' => $this->visit->id,
            'patient_name' => $this->visit->patient->first_name . ' ' . $this->visit->patient->last_name,
            'follow_up_date' => $this->visit->follow_up_date?->format('M d, Y'),
            'doctor_name' => $this->visit->doctor->name,
            'title' => 'Follow-Up Appointment Reminder',
            'message' => "You have a follow-up appointment scheduled for {$this->visit->follow_up_date?->format('M d, Y')}.",
        ];
    }

    public function toWhatsApp(object $notifiable): void
    {
        $patient = $this->visit->patient;

        if (!$patient->whatsapp_number) {
            return;
        }

        $message = "Hello {$patient->first_name},\n\n"
                 . "This is a reminder about your follow-up appointment.\n\n"
                 . "Date: {$this->visit->follow_up_date?->format('M d, Y')}\n"
                 . "Doctor: Dr. {$this->visit->doctor->name}\n\n"
                 . "Please contact us if you need to reschedule.";

        try {
            $whatsapp = app(WhatsAppService::class);
            $whatsapp->send($patient->whatsapp_number, $message);
        } catch (\Exception $e) {
            // Log silently; WhatsApp send failures are non-blocking
        }
    }
}
