<?php

namespace App\Services;

use App\Models\PlatformExpense;
use App\Models\PlatformIncome;
use App\Models\PlatformTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PlatformAccountingService
{
    public function recordExpense(array $data): PlatformExpense
    {
        return DB::transaction(function () use ($data) {
            $expense = PlatformExpense::create([
                'category'     => $data['category'],
                'amount'       => $data['amount'],
                'expense_date' => $data['expense_date'],
                'description'  => $data['description'] ?? null,
                'receipt_path' => $data['receipt_path'] ?? null,
                'created_by'   => auth()->id(),
            ]);

            PlatformTransaction::create([
                'type'             => 'expense',
                'reference_id'     => $expense->id,
                'reference_type'   => PlatformExpense::class,
                'amount'           => $expense->amount,
                'transaction_date' => $expense->expense_date,
                'description'      => $expense->description,
            ]);

            return $expense;
        });
    }

    public function monthlyRevenue(int $year, int $month): float
    {
        return (float) PlatformIncome::whereYear('received_date', $year)
            ->whereMonth('received_date', $month)
            ->sum('amount');
    }

    public function monthlyExpenses(int $year, int $month): float
    {
        return (float) PlatformExpense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->sum('amount');
    }

    public function monthlyProfit(int $year, int $month): float
    {
        return $this->monthlyRevenue($year, $month) - $this->monthlyExpenses($year, $month);
    }

    public function yearlyReport(int $year): array
    {
        $report = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenue  = $this->monthlyRevenue($year, $m);
            $expenses = $this->monthlyExpenses($year, $m);
            $report[] = [
                'month'    => Carbon::create($year, $m, 1)->format('M'),
                'revenue'  => $revenue,
                'expenses' => $expenses,
                'profit'   => $revenue - $expenses,
            ];
        }
        return $report;
    }

    public function expensesByCategory(int $year, int $month): array
    {
        return PlatformExpense::selectRaw('category, SUM(amount) as total')
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();
    }
}
