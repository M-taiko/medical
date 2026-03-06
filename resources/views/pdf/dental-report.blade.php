<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dental Report</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 0;}
        .container { width: 100%; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 2px solid #4F46E5; padding-bottom: 10px; margin-bottom: 20px; display: table; width: 100%; }
        .header .clinic-info { display: table-cell; width: 50%; }
        .header .doctor-info { display: table-cell; width: 50%; text-align: right; }
        .title { text-align: center; color: #4F46E5; font-size: 24px; margin-bottom: 20px; }
        .patient-box { background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e5e5e5; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f3f4f6; color: #374151; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .total-box { text-align: right; font-size: 18px; font-weight: bold; margin-bottom: 30px; }
        .footer { border-top: 1px solid #ddd; padding-top: 20px; font-size: 12px; color: #6b7280; text-align: center; }
        .qr-code { text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="clinic-info">
                <h2 style="margin: 0; color: #4F46E5;">{{ $clinic->name ?? 'Masar Dental' }}</h2>
                <p style="margin: 5px 0;">{{ $clinic->address ?? 'Premium Dental Care' }}</p>
            </div>
            <div class="doctor-info">
                <strong style="font-size: 16px;">{{ $doctor->name ?? 'Dr. Specialist' }}</strong>
                <p style="margin: 5px 0;">Date: {{ date('Y-m-d') }}</p>
            </div>
        </div>

        <h1 class="title">Official Dental Report & Treatment Plan</h1>

        <!-- Patient Info -->
        <div class="patient-box">
            <table style="border: none; margin: 0;">
                <tr style="background: none;">
                    <td style="border: none; padding: 5px;"><strong>Patient Name:</strong> {{ $patient->first_name }} {{ $patient->last_name }}</td>
                    <td style="border: none; padding: 5px;"><strong>ID:</strong> #{{ $patient->id }}</td>
                </tr>
                <tr style="background: none;">
                    <td style="border: none; padding: 5px;"><strong>Phone:</strong> {{ $patient->phone }}</td>
                    <td style="border: none; padding: 5px;"><strong>Blood Type:</strong> {{ $patient->blood_type ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <!-- Treatments Table -->
        <h3>Treatment Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Tooth</th>
                    <th>Condition</th>
                    <th>Treatment</th>
                    <th>Findings/Notes</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $rec)
                <tr>
                    <td><strong>{{ $rec->tooth_number }}</strong></td>
                    <td style="text-transform: capitalize;">{{ $rec->condition }}</td>
                    <td>{{ $rec->treatment ?? 'N/A' }}</td>
                    <td>{{ $rec->findings ?? '-' }}</td>
                    <td>${{ number_format($rec->price, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No treatments recorded for this session.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Totals -->
        <div class="total-box">
            Total Estimated Cost: <span style="color: #10B981;">${{ number_format($total, 2) }}</span>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing {{ $clinic->name ?? 'Masar Dental' }}. This document is system-generated and serves as an official medical record.</p>
            <div style="margin-top: 20px;">
                <!-- Usually a real QR code using a package, doing dummy text for concept -->
                [ QR Code Verification: {{ $qrCodeUrl ?? 'N/A' }} ]
            </div>
        </div>
    </div>
</body>
</html>
