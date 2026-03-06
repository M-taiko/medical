<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function index()
    {
        $invoices      = Invoice::with(['patient', 'visit'])->latest()->paginate(10);
        $totalRevenue  = Invoice::sum('paid_amount');
        $outstanding   = Invoice::sum('remaining_balance');
        $totalExpenses = Expense::sum('amount');
        $netProfit     = $totalRevenue - $totalExpenses;

        $expenses = Expense::latest()->limit(10)->get();

        return view('financials.index', compact('invoices', 'totalRevenue', 'outstanding', 'totalExpenses', 'netProfit', 'expenses'));
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'category'    => 'required|in:rent,salaries,supplies,utilities,maintenance,other',
            'amount'      => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'expense_date'=> 'required|date',
        ]);

        $validated['user_id'] = auth()->id();

        Expense::create($validated);

        return redirect()->route('financials.index')->with('success', 'Expense recorded.');
    }

    public function reports()
    {
        $year  = request()->input('year', now()->year);
        $month = request()->input('month', now()->month);

        // Monthly revenue and expenses for the selected month
        $monthlyRevenue  = Invoice::whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('paid_amount');
        $monthlyExpenses = Expense::whereYear('expense_date', $year)->whereMonth('expense_date', $month)->sum('amount');
        $monthlyProfit   = $monthlyRevenue - $monthlyExpenses;

        // Revenue by doctor for the month
        $revenueByDoctor = Invoice::with('visit.doctor')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get()
            ->groupBy(fn ($inv) => optional(optional($inv->visit)->doctor)->name ?? 'Unknown')
            ->map(fn ($group) => $group->sum('paid_amount'))
            ->sortByDesc(fn ($v) => $v);

        // Outstanding aging
        $aging = [
            '0-30'  => Invoice::where('remaining_balance', '>', 0)->where('created_at', '>=', now()->subDays(30))->sum('remaining_balance'),
            '31-60' => Invoice::where('remaining_balance', '>', 0)->whereBetween('created_at', [now()->subDays(60), now()->subDays(31)])->sum('remaining_balance'),
            '61-90' => Invoice::where('remaining_balance', '>', 0)->whereBetween('created_at', [now()->subDays(90), now()->subDays(61)])->sum('remaining_balance'),
            '90+'   => Invoice::where('remaining_balance', '>', 0)->where('created_at', '<', now()->subDays(90))->sum('remaining_balance'),
        ];

        // 12-month revenue trend
        $revenueTrend = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueTrend[] = [
                'month'    => \Carbon\Carbon::create($year, $m)->format('M'),
                'revenue'  => Invoice::whereYear('created_at', $year)->whereMonth('created_at', $m)->sum('paid_amount'),
                'expenses' => Expense::whereYear('expense_date', $year)->whereMonth('expense_date', $m)->sum('amount'),
            ];
        }

        // Expense breakdown by category
        $expenseByCategory = Expense::selectRaw('category, SUM(amount) as total')
            ->whereYear('expense_date', $year)->whereMonth('expense_date', $month)
            ->groupBy('category')->pluck('total', 'category')->toArray();

        return view('financials.reports', compact(
            'year', 'month', 'monthlyRevenue', 'monthlyExpenses', 'monthlyProfit',
            'revenueByDoctor', 'aging', 'revenueTrend', 'expenseByCategory'
        ));
    }
}
