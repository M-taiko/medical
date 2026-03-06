<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount'         => 'required|numeric|min:0.01|max:' . $invoice->remaining_balance,
            'payment_method' => 'required|in:cash,card,bank_transfer,other',
            'notes'          => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated, $invoice) {
            Payment::create([
                'clinic_id'      => auth()->user()->clinic_id,
                'invoice_id'     => $invoice->id,
                'patient_id'     => $invoice->patient_id,
                'amount'         => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'notes'          => $validated['notes'] ?? null,
            ]);

            $invoice->recordPayment((float) $validated['amount']);
        });

        return redirect()->route('invoices.show', $invoice)->with('success', 'Payment recorded successfully!');
    }
}
