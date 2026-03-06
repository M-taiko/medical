<?php

namespace App\Http\Controllers;

use App\Models\DentalChartRecord;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Visit;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $base = [
            'totalPatients'     => Patient::count(),
            'todayVisits'       => Visit::whereDate('appointment_time', today())->count(),
            'upcomingVisits'    => Visit::with('patient', 'doctor')
                                        ->whereDate('appointment_time', today())
                                        ->where('appointment_time', '>=', now())
                                        ->orderBy('appointment_time')
                                        ->take(8)
                                        ->get(),
            'monthlyTreatments' => DentalChartRecord::whereMonth('created_at', now()->month)
                                                    ->whereYear('created_at', now()->year)
                                                    ->count(),
            'monthlyRevenue'    => Invoice::whereMonth('created_at', now()->month)
                                          ->whereYear('created_at', now()->year)
                                          ->sum('total_amount'),
        ];

        // Doctor-specific extras
        if ($user->isDoctor()) {
            $base['myTodayVisits'] = Visit::where('doctor_id', $user->id)
                ->whereDate('appointment_time', today())
                ->with('patient')
                ->get();
        }

        // Accountant-specific extras
        if ($user->isAccountant()) {
            $base['unpaidInvoices'] = Invoice::where('status', 'unpaid')->count();
            $base['outstandingBalance'] = Invoice::sum('remaining_balance');
        }

        // Receptionist
        if ($user->isReceptionist()) {
            $base['allTodayVisits'] = Visit::whereDate('appointment_time', today())
                ->with('patient', 'doctor')
                ->orderBy('appointment_time')
                ->get();
        }

        return view('dashboard', $base);
    }
}
