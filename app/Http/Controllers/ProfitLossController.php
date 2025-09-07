<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;

class ProfitLossController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $accounts = ChartOfAccount::with('details.journal', 'category')->get();

        $revenues = [];
        $expenses = [];
        $totalRevenue = 0;
        $totalExpense = 0;

        foreach ($accounts as $acc) {
            // hanya Revenue & Expense
            if (!in_array($acc->category->category_type, ['Revenue', 'Expense'])) continue;

            $query = $acc->details();
            if ($startDate && $endDate) {
                $query->whereHas('journal', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('entry_date', [$startDate, $endDate]);
                });
            }

            $details = $query->get();
            $debit  = $details->sum('debit_amount');
            $credit = $details->sum('credit_amount');
            $balance = ($acc->normal_balance == 'Debit') ? $debit - $credit : $credit - $debit;

            if ($acc->category->category_type == 'Revenue') {
                $revenues[] = ['acc' => $acc, 'balance' => $balance];
                $totalRevenue += $balance;
            } elseif ($acc->category->category_type == 'Expense') {
                $expenses[] = ['acc' => $acc, 'balance' => $balance];
                $totalExpense += $balance;
            }
        }

        $netProfit = $totalRevenue - $totalExpense;

        return view('profitloss.index', compact(
            'revenues',
            'expenses',
            'totalRevenue',
            'totalExpense',
            'netProfit',
            'startDate',
            'endDate'
        ));
    }
}
