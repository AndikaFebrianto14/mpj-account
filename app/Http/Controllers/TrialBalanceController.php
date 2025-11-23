<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrialBalanceExport;

class TrialBalanceController extends Controller
{
    private function generateData(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $accounts = ChartOfAccount::orderBy('account_number')->get();

        $report = collect();
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($accounts as $acc) {
            $details = JournalEntryDetail::join('journal_entries', 'journal_entry_details.journal_entry_id', '=', 'journal_entries.id')
                ->where('journal_entry_details.account_id', $acc->id)
                ->when($startDate && $endDate, fn($q) => $q->whereBetween('journal_entries.entry_date', [$startDate, $endDate]))
                ->select('journal_entry_details.*')
                ->get();

            $debit = $details->sum('debit_amount');
            $credit = $details->sum('credit_amount');
            $ending = $debit - $credit;

            $report->push([
                'account_number' => $acc->account_number,
                'account_name'   => $acc->account_name,
                'debit'          => $debit,
                'credit'         => $credit,
                'ending'         => $ending
            ]);

            $totalDebit += $debit;
            $totalCredit += $credit;
        }

        return compact('report', 'totalDebit', 'totalCredit', 'startDate', 'endDate');
    }

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

    public function exportPdf(Request $request)
    {
        $data = $this->generateData($request);

        $pdf = PDF::loadView('trialbalance.pdf', $data)->setPaper('A4', 'portrait');

        return $pdf->download("Trial-Balance-{$data['startDate']}-to-{$data['endDate']}.pdf");
    }
    public function exportExcel(Request $request)
    {
        $data = $this->generateData($request);

        return Excel::download(
            new TrialBalanceExport($data['report']),
            "Trial-Balance-{$data['startDate']}-to-{$data['endDate']}.xlsx"
        );
    }
    public function print(Request $request)
    {
        $data = $this->generateData($request);

        $pdf = PDF::loadView('trialbalance.pdf', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->stream("Trial-Balance.pdf");
    }
}
