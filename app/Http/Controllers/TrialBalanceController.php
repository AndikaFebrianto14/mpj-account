<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryDetail;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $accounts = ChartOfAccount::with('details.journal')->get();

        $report = $accounts->map(function ($acc) use ($startDate, $endDate) {
            $query = $acc->details();

            if ($startDate && $endDate) {
                $query->whereHas('journal', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('entry_date', [$startDate, $endDate]);
                });
            }

            $details = $query->get();

            $debit  = $details->sum('debit_amount');
            $credit = $details->sum('credit_amount');
            $ending = ($acc->normal_balance == 'Debit')
                ? $debit - $credit
                : $credit - $debit;

            return [
                'account_number' => $acc->account_number,
                'account_name'   => $acc->account_name,
                'debit'          => $debit,
                'credit'         => $credit,
                'ending'         => $ending,
            ];
        });

        $totalDebit  = $report->sum('debit');
        $totalCredit = $report->sum('credit');

        return view('trialbalance.index', compact('report', 'startDate', 'endDate', 'totalDebit', 'totalCredit'));
    }
}
