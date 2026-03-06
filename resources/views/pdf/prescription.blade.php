<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prescription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .clinic-name {
            color: #4F46E5;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .clinic-info {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
        }
        .rx-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #4F46E5;
            margin: 20px 0;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #4F46E5;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .patient-info, .doctor-info {
            font-size: 13px;
            line-height: 1.6;
        }
        .medicines {
            margin-top: 20px;
        }
        .medicine-item {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 10px;
            border-left: 4px solid #4F46E5;
            border-radius: 2px;
        }
        .medicine-name {
            font-weight: bold;
            font-size: 13px;
            color: #333;
            margin: 0;
        }
        .medicine-details {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p class="clinic-name">{{ $clinic->name }}</p>
            <p class="clinic-info">{{ $clinic->address }}</p>
            <p class="clinic-info">{{ $clinic->phone }} | {{ $clinic->email }}</p>
        </div>

        <div class="rx-title">Rx PRESCRIPTION</div>

        <div class="section">
            <div class="section-title">Patient Information</div>
            <div class="patient-info">
                <strong>{{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</strong><br>
                {{ $prescription->patient->phone }}<br>
                @if($prescription->patient->email)
                {{ $prescription->patient->email }}<br>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">Doctor & Date</div>
            <div class="doctor-info">
                <strong>Dr. {{ $prescription->doctor->name }}</strong><br>
                Prescribed: {{ $prescription->prescribed_at->format('M d, Y') }}<br>
                @if($prescription->diagnosis)
                Diagnosis: {{ $prescription->diagnosis }}<br>
                @endif
            </div>
        </div>

        @if($prescription->items->count() > 0)
        <div class="section medicines">
            <div class="section-title">Medicines</div>
            @foreach($prescription->items as $item)
            <div class="medicine-item">
                <p class="medicine-name">{{ $item->medicine_name }}</p>
                <p class="medicine-details">
                    Dosage: <strong>{{ $item->dosage }}</strong><br>
                    Frequency: <strong>{{ $item->frequency }}</strong><br>
                    Duration: <strong>{{ $item->duration }}</strong>
                    @if($item->notes)
                    <br>Notes: {{ $item->notes }}
                    @endif
                </p>
            </div>
            @endforeach
        </div>
        @endif

        @if($prescription->instructions)
        <div class="section">
            <div class="section-title">Instructions</div>
            <div style="font-size: 13px; line-height: 1.6;">
                {{ $prescription->instructions }}
            </div>
        </div>
        @endif

        <div class="footer">
            <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
            <p>Please follow the instructions carefully and consult your doctor if you have any doubts.</p>
        </div>
    </div>
</body>
</html>
