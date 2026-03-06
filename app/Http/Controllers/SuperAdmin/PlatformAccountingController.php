<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PlatformExpense;
use App\Models\PlatformIncome;
use App\Services\PlatformAccountingService;
use Illuminate\Http\Request;

class PlatformAccountingController extends Controller
{
    public function __construct(private PlatformAccountingService $accounting) {}

    public function index()
    {
        $year  = request('year', now()->year);
        $month = request('month', now()->month);

        $revenue       = $this->accounting->monthlyRevenue($year, $month);
        $expenses      = $this->accounting->monthlyExpenses($year, $month);
        $profit        = $revenue - $expenses;
        $yearlyReport  = $this->accounting->yearlyReport($year);
        $expensesBreakdown = $this->accounting->expensesByCategory($year, $month);

        return view('superadmin.accounting.index', compact(
            'revenue', 'expenses', 'profit', 'yearlyReport', 'expensesBreakdown', 'year', 'month'
        ));
    }

    public function income()
    {
        $incomeRecords = PlatformIncome::with(['clinic', 'subscription.plan'])
            ->latest('received_date')
            ->paginate(20);

        return view('superadmin.accounting.income', compact('incomeRecords'));
    }

    public function expenses()
    {
        $expenseRecords = PlatformExpense::with('creator')
            ->latest('expense_date')
            ->paginate(20);

        $categories = PlatformExpense::CATEGORIES;

        return view('superadmin.accounting.expenses', compact('expenseRecords', 'categories'));
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'category'     => 'required|in:' . implode(',', array_keys(PlatformExpense::CATEGORIES)),
            'amount'       => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'description'  => 'nullable|string|max:500',
        ]);

        $this->accounting->recordExpense($validated);

        return redirect()->route('superadmin.accounting.expenses')
                         ->with('success', 'Expense recorded.');
    }

    public function reports()
    {
        $year  = request('year', now()->year);
        $yearlyReport = $this->accounting->yearlyReport($year);

        $totalRevenue  = array_sum(array_column($yearlyReport, 'revenue'));
        $totalExpenses = array_sum(array_column($yearlyReport, 'expenses'));
        $totalProfit   = $totalRevenue - $totalExpenses;

        return view('superadmin.accounting.reports', compact(
            'yearlyReport', 'totalRevenue', 'totalExpenses', 'totalProfit', 'year'
        ));
    }
}
