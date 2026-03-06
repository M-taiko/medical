<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .clinic-info h1 {
            margin: 0;
            color: #4F46E5;
            font-size: 28px;
        }
        .clinic-info p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .invoice-title p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .detail-box {
            flex: 1;
        }
        .detail-box h4 {
            margin: 0 0 10px 0;
            font-size: 12px;
            text-transform: uppercase;
            color: #999;
            font-weight: bold;
        }
        .detail-box p {
            margin: 5px 0;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        thead {
            background-color: #f5f5f5;
            border-top: 2px solid #4F46E5;
            border-bottom: 2px solid #4F46E5;
        }
        th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            color: #333;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        td.amount {
            text-align: right;
            font-weight: bold;
        }
        .totals {
            width: 100%;
            margin-top: 30px;
        }
        .totals-row {
            display: flex;
            justify-content: flex-end;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        .totals-row.total {
            border-top: 2px solid #4F46E5;
            border-bottom: 2px solid #4F46E5;
            padding: 12px 0;
            font-weight: bold;
            font-size: 16px;
            color: #4F46E5;
        }
        .totals-label {
            width: 200px;
            text-align: right;
            padding-right: 20px;
        }
        .totals-amount {
            width: 100px;
            text-align: right;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            margin-top: 20px;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-partial {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-unpaid {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 11px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="clinic-info">
                <h1>{{ $clinic->name }}</h1>
                <p>{{ $clinic->address }}</p>
                <p>{{ $clinic->phone }} | {{ $clinic->email }}</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p>#{{ $invoice->id }}</p>
                <p>{{ $invoice->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <div class="details">
            <div class="detail-box">
                <h4>Patient</h4>
                <p><strong>{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</strong></p>
                <p>{{ $invoice->patient->phone }}</p>
                @if($invoice->patient->email)
                    <p>{{ $invoice->patient->email }}</p>
                @endif
            </div>
            <div class="detail-box">
                <h4>Visit Information</h4>
                <p><strong>{{ $invoice->visit->appointment_time->format('M d, Y') }}</strong></p>
                <p>Doctor: {{ $invoice->visit->doctor->name }}</p>
                @if($invoice->visit->chief_complaint)
                    <p>Chief Complaint: {{ $invoice->visit->chief_complaint }}</p>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="amount">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $subtotal = 0 @endphp
                @foreach($invoice->visit->dentalCharts as $record)
                    @if($record->treatment && $record->price > 0)
                    <tr>
                        <td>Tooth #{{ $record->tooth_number }} — {{ $record->treatment }}</td>
                        <td class="amount">${{ number_format($record->price, 2) }}</td>
                    </tr>
                    @php $subtotal += $record->price @endphp
                    @endif
                @endforeach
                @foreach($invoice->visit->treatmentRecords as $tr)
                    @if($tr->cost > 0)
                    <tr>
                        <td>{{ $tr->treatment_type }}</td>
                        <td class="amount">${{ number_format($tr->cost, 2) }}</td>
                    </tr>
                    @php $subtotal += $tr->cost @endphp
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-row">
                <span class="totals-label">Subtotal:</span>
                <span class="totals-amount">${{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="totals-row total">
                <span class="totals-label">Total:</span>
                <span class="totals-amount">${{ number_format($invoice->total_amount, 2) }}</span>
            </div>
            <div class="totals-row">
                <span class="totals-label">Amount Paid:</span>
                <span class="totals-amount">${{ number_format($invoice->paid_amount, 2) }}</span>
            </div>
            <div class="totals-row">
                <span class="totals-label">Balance Due:</span>
                <span class="totals-amount">${{ number_format($invoice->remaining_balance, 2) }}</span>
            </div>
        </div>

        <div>
            <span class="status-badge
                @if($invoice->status === 'paid') status-paid
                @elseif($invoice->status === 'partial') status-partial
                @else status-unpaid @endif">
                {{ ucfirst($invoice->status) }}
            </span>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
