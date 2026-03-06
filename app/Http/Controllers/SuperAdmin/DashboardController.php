<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicSubscription;
use App\Models\PlatformIncome;
use App\Models\PlatformExpense;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClinics   = Clinic::count();
        $activeSubs     = ClinicSubscription::active()->count();
        $expiredSubs    = ClinicSubscription::expired()->count();
        $upcomingRenewals = ClinicSubscription::where('subscription_status', 'active')
            ->whereBetween('subscription_end_date', [now(), now()->addDays(7)])
            ->with('clinic')
            ->get();

        $monthlyRevenue  = PlatformIncome::whereYear('received_date', now()->year)
            ->whereMonth('received_date', now()->month)
            ->sum('amount');

        $monthlyExpenses = PlatformExpense::whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        $monthlyProfit = $monthlyRevenue - $monthlyExpenses;

        $recentClinics = Clinic::with('latestSubscription.plan')
            ->latest()
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact(
            'totalClinics',
            'activeSubs',
            'expiredSubs',
            'upcomingRenewals',
            'monthlyRevenue',
            'monthlyExpenses',
            'monthlyProfit',
            'recentClinics'
        ));
    }
}
