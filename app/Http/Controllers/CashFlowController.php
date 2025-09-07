<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        // ambil akun kas & setara kas
        $cashAccounts = ChartOfAccount::with('details.journal', 'category')
            ->whereHas('category', function ($q) {
                $q->where('category_type', 'Asset')
                    ->where('category_name', 'like', '%Kas%');
            })->get();

        $cashIn = [];
        $cashOut = [];
        $totalIn = 0;
        $totalOut = 0;

        foreach ($cashAccounts as $acc) {
            $query = $acc->details();
            if ($startDate && $endDate) {
                $query->whereHas('journal', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('entry_date', [$startDate, $endDate]);
                });
            }

            $details = $query->get();

            foreach ($details as $d) {
                if ($d->debit_amount > 0) {
                    $cashIn[] = [
                        'date' => $d->journal->entry_date,
                        'desc' => $d->description,
                        'amount' => $d->debit_amount
                    ];
                    $totalIn += $d->debit_amount;
                }
                if ($d->credit_amount > 0) {
                    $cashOut[] = [
                        'date' => $d->journal->entry_date,
                        'desc' => $d->description,
                        'amount' => $d->credit_amount
                    ];
                    $totalOut += $d->credit_amount;
                }
            }
        }

        $netCash = $totalIn - $totalOut;

        return view('cashflow.index', compact(
            'cashIn',
            'cashOut',
            'totalIn',
            'totalOut',
            'netCash',
            'startDate',
            'endDate'
        ));
    }
}
