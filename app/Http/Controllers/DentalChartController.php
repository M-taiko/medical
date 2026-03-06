<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\DentalChartRecord;
use App\Services\DentalChartService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DentalChartController extends Controller
{
    protected $chartService;
    protected $whatsappService;

    public function __construct(DentalChartService $chartService, WhatsAppService $whatsappService)
    {
        $this->chartService = $chartService;
        $this->whatsappService = $whatsappService;
    }

    public function show(Patient $patient)
    {
        $chartData = $this->chartService->getPatientChart($patient->id);

        // Get clinical timeline: all historical events ordered chronologically
        $timeline = collect()
            ->concat($patient->dentalCharts()->get()->map(fn ($r) => [
                'type' => 'dental_chart',
                'date' => $r->created_at,
                'title' => "Tooth #{$r->tooth_number}",
                'description' => $r->condition . ($r->treatment ? " → {$r->treatment}" : ''),
                'icon' => 'activity',
                'color' => 'blue',
            ]))
            ->concat($patient->visits()->get()->map(fn ($v) => [
                'type' => 'visit',
                'date' => $v->appointment_time,
                'title' => "Visit with Dr. {$v->doctor->name}",
                'description' => $v->chief_complaint ?: 'Routine checkup',
                'icon' => 'calendar',
                'color' => 'primary',
                'status' => $v->status,
            ]))
            ->concat($patient->prescriptions()->get()->map(fn ($p) => [
                'type' => 'prescription',
                'date' => $p->prescribed_at,
                'title' => "Prescription from Dr. {$p->doctor->name}",
                'description' => $p->diagnosis,
                'icon' => 'pill',
                'color' => 'emerald',
                'items_count' => $p->items->count(),
            ]))
            ->concat($patient->treatmentRecords()->get()->map(fn ($tr) => [
                'type' => 'treatment',
                'date' => $tr->created_at,
                'title' => $tr->treatment_type,
                'description' => "Cost: \$" . number_format($tr->cost, 2),
                'icon' => 'stethoscope',
                'color' => 'purple',
            ]))
            ->sortByDesc('date')
            ->values();

        return view('dental-chart.show', compact('patient', 'chartData', 'timeline'));
    }

    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'tooth_number' => 'required|string',
            'condition' => 'required|string',
            'findings' => 'nullable|string',
            'treatment' => 'nullable|string',
            'price' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $validated['patient_id'] = $patient->id;
        $validated['visit_id'] = $request->input('visit_id');

        $record = $this->chartService->saveRecord($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tooth status updated successfully.',
            'record' => $record
        ]);
    }

    public function finalizeAndSendReport(Request $request, Patient $patient)
    {
        // 1. Gather data
        $records = $patient->dentalCharts()->where('created_at', '>=', today())->get();
        if ($records->isEmpty()) {
            return back()->with('error', 'No new records to send today.');
        }

        $total = $records->sum('price');

        // 2. Generate PDF using DomPDF
        $pdf = Pdf::loadView('pdf.dental-report', [
            'patient' => $patient,
            'records' => $records,
            'clinic' => $patient->clinic ?? (object) ['name' => 'Masar Dental Clinic'],
            'doctor' => auth()->user(),
            'total' => $total,
            'qrCodeUrl' => url('/verify-visit/' . uniqid()) // Dummy QR URL
        ]);

        $fileName = 'reports/patient_' . $patient->id . '_' . time() . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        // 3. Send WhatsApp via Service
        $pdfUrl = asset('storage/' . $fileName);
        $this->whatsappService->sendDentalReport(
            $patient->whatsapp_number ?? $patient->phone,
            $patient->first_name,
            $patient->clinic->name ?? 'Masar Dental Clinic',
            $pdfUrl
        );

        return back()->with('success', 'Dental report generated and sent to WhatsApp!');
    }
}
