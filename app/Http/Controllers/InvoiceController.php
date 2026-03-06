<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['patient', 'visit.doctor'])->latest()->paginate(15);
        return view('invoices.index', compact('invoices'));
    }

    public function create(Visit $visit)
    {
        $visit->load(['patient', 'doctor', 'dentalCharts', 'treatmentRecords']);

        // Pre-populate line items from dental chart and treatment records
        $lineItems = collect();

        foreach ($visit->dentalCharts as $record) {
            if ($record->treatment && $record->price > 0) {
                $lineItems->push([
                    'description' => "Tooth #{$record->tooth_number} — {$record->treatment}",
                    'amount'      => $record->price,
                ]);
            }
        }

        foreach ($visit->treatmentRecords as $tr) {
            if ($tr->cost > 0) {
                $lineItems->push([
                    'description' => $tr->treatment_type,
                    'amount'      => $tr->cost,
                ]);
            }
        }

        $suggestedTotal = $lineItems->sum('amount');

        return view('invoices.create', compact('visit', 'lineItems', 'suggestedTotal'));
    }

    public function store(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'total_amount'  => 'required|numeric|min:0',
            'paid_amount'   => 'required|numeric|min:0',
            'discount'      => 'nullable|numeric|min:0',
            'notes'         => 'nullable|string|max:500',
        ]);

        if ($visit->invoice) {
            return redirect()->route('invoices.show', $visit->invoice)->with('info', 'Invoice already exists for this visit.');
        }

        $total   = (float) $validated['total_amount'] - (float) ($validated['discount'] ?? 0);
        $paid    = min((float) $validated['paid_amount'], $total);
        $balance = $total - $paid;
        $status  = $balance <= 0 ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid');

        $invoice = Invoice::create([
            'clinic_id'         => auth()->user()->clinic_id,
            'patient_id'        => $visit->patient_id,
            'visit_id'          => $visit->id,
            'total_amount'      => $total,
            'paid_amount'       => $paid,
            'remaining_balance' => $balance,
            'status'            => $status,
        ]);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'visit.doctor', 'visit.dentalCharts', 'visit.treatmentRecords', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load(['patient', 'visit.doctor', 'visit.dentalCharts', 'visit.treatmentRecords', 'payments']);
        $clinic = auth()->user()->clinic;

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice', 'clinic'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("invoice-{$invoice->id}.pdf");
    }
}
