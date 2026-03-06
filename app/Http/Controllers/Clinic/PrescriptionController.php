<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Visit;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::with(['patient', 'doctor'])
            ->latest()
            ->paginate(20);

        return view('clinic.prescriptions.index', compact('prescriptions'));
    }

    public function create(Patient $patient)
    {
        $visits = $patient->visits()->latest()->take(10)->get();
        return view('clinic.prescriptions.create', compact('patient', 'visits'));
    }

    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'visit_id'     => 'nullable|exists:visits,id',
            'diagnosis'    => 'required|string',
            'instructions' => 'nullable|string',
            'prescribed_at'=> 'required|date',
            'items'        => 'required|array|min:1',
            'items.*.medicine_name' => 'required|string|max:255',
            'items.*.dosage'        => 'required|string|max:100',
            'items.*.frequency'     => 'required|string|max:100',
            'items.*.duration'      => 'required|string|max:100',
            'items.*.notes'         => 'nullable|string|max:255',
        ]);

        $prescription = Prescription::create([
            'patient_id'   => $patient->id,
            'visit_id'     => $validated['visit_id'] ?? null,
            'doctor_id'    => auth()->id(),
            'diagnosis'    => $validated['diagnosis'],
            'instructions' => $validated['instructions'],
            'prescribed_at'=> $validated['prescribed_at'],
        ]);

        foreach ($validated['items'] as $item) {
            $prescription->items()->create($item);
        }

        return redirect()->route('prescriptions.show', $prescription)
                         ->with('success', 'Prescription created successfully.');
    }

    public function show(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor', 'items', 'visit']);
        return view('clinic.prescriptions.show', compact('prescription'));
    }

    public function pdf(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor', 'items']);
        $clinic = auth()->user()->clinic;

        $pdf = Pdf::loadView('pdf.prescription', compact('prescription', 'clinic'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("prescription-{$prescription->id}.pdf");
    }

    public function sendWhatsApp(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor']);

        if (!$prescription->patient->whatsapp_number) {
            return redirect()->route('prescriptions.show', $prescription)
                           ->with('error', 'Patient does not have a WhatsApp number on file.');
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdf.prescription', [
            'prescription' => $prescription,
            'clinic' => auth()->user()->clinic,
        ]);

        // Store temporarily
        $filename = "prescription-{$prescription->id}.pdf";
        $path = storage_path("app/temp/{$filename}");
        @mkdir(storage_path('app/temp'), 0755, true);
        file_put_contents($path, $pdf->output());

        // Send via WhatsApp
        $whatsapp = app(WhatsAppService::class);
        $message = "Hello {$prescription->patient->first_name},\n\n"
                 . "Your prescription from Dr. {$prescription->doctor->name} is attached.\n\n"
                 . "Diagnosis: {$prescription->diagnosis}\n"
                 . "Date: {$prescription->prescribed_at->format('M d, Y')}\n\n"
                 . "Please follow the instructions carefully.";

        try {
            $whatsapp->sendWithAttachment(
                $prescription->patient->whatsapp_number,
                $message,
                $path
            );

            // Clean up temp file
            @unlink($path);

            return redirect()->route('prescriptions.show', $prescription)
                           ->with('success', 'Prescription sent to WhatsApp successfully!');
        } catch (\Exception $e) {
            @unlink($path);
            return redirect()->route('prescriptions.show', $prescription)
                           ->with('error', 'Failed to send WhatsApp message.');
        }
    }
}
