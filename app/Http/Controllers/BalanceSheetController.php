<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        // Ambil semua akun
        $accounts = ChartOfAccount::with('details.journal')->get();

        $report = [
            'assets'      => [],
            'liabilities' => [],
            'equity'      => [],
        ];

        foreach ($accounts as $acc) {
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

            switch ($acc->category->category_type) {
                case 'Asset':
                    $report['assets'][] = ['acc' => $acc, 'balance' => $balance];
                    break;
                case 'Liability':
                    $report['liabilities'][] = ['acc' => $acc, 'balance' => $balance];
                    break;
                case 'Equity':
                    $report['equity'][] = ['acc' => $acc, 'balance' => $balance];
                    break;
            }
        }

        $totalAssets      = collect($report['assets'])->sum('balance');
        $totalLiabilities = collect($report['liabilities'])->sum('balance');
        $totalEquity      = collect($report['equity'])->sum('balance');

        return view('balancesheet.index', compact(
            'report',
            'startDate',
            'endDate',
            'totalAssets',
            'totalLiabilities',
            'totalEquity'
        ));
    }
}
