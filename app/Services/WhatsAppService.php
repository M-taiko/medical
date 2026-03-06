<?php

namespace App\Services;

use Twilio\Rest\Client; // Example of abstraction
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send the dental chart PDF to WhatsApp.
     */
    public function sendDentalReport($patientPhone, $patientName, $clinicName, $pdfUrl)
    {
        $message = "Hello {$patientName},\n\n";
        $message .= "This is your dental report from {$clinicName}.\n";
        $message .= "View or download your report here: {$pdfUrl}\n\n";
        $message .= "If you have any questions, feel free to contact us.\n";
        $message .= "Wishing you a healthy smile! 🦷";

        // In production, we'd use Gupshup, Twilio, UltraMsg, or official WhatsApp Cloud API.
        Log::info("WhatsApp message sent to {$patientPhone}", ['message' => $message]);

        // Simulating the API Call
        return [
            'status' => 'success',
            'message' => 'WhatsApp sent successfully',
            'delivered_to' => $patientPhone
        ];
    }
}
