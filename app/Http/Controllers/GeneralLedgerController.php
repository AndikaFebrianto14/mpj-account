<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryDetail;
use App\Models\JournalEntry;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LedgerExport;

class GeneralLedgerController extends Controller
{
    public function index(Request $request)
    {
        $accounts = ChartOfAccount::orderBy('account_number')->get();
        $selectedAccount = null;
        $entries = collect();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($request->account_id) {
            $selectedAccount = ChartOfAccount::find($request->account_id);
            if ($selectedAccount) {
                $query = JournalEntryDetail::with('journal')
                    ->where('account_id', $selectedAccount->id)
                    ->join('journal_entries', 'journal_entry_details.journal_entry_id', '=', 'journal_entries.id')
                    ->select('journal_entry_details.*');

                if ($startDate && $endDate) {
                    $query->whereBetween('journal_entries.entry_date', [$startDate, $endDate]);
                }

                $entries = $query->orderBy('journal_entries.entry_date', 'asc')->get();
            }
        }

        return view('ledger.index', compact('accounts', 'selectedAccount', 'entries', 'startDate', 'endDate'));
    }

    public function print(Request $request)
    {
        $account_id = $request->account_id;
        $start = $request->start_date;
        $end = $request->end_date;

        // Ambil account
        $account = ChartOfAccount::find($account_id);
        if (!$account) {
            abort(404, 'Account not found.');
        }

        // Ambil ledger (sama konsep dengan index)
        $entries = JournalEntryDetail::with('journal')
            ->where('account_id', $account_id)
            ->join('journal_entries', 'journal_entry_details.journal_entry_id', '=', 'journal_entries.id')
            ->select('journal_entry_details.*')
            ->when($start && $end, function ($q) use ($start, $end) {
                $q->whereBetween('journal_entries.entry_date', [$start, $end]);
            })
            ->orderBy('journal_entries.entry_date', 'asc')
            ->get();

        return view('ledger.print', [
            'account' => $account,
            'entries' => $entries,
            'start' => $start,
            'end' => $end
        ]);
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new LedgerExport(
                $request->account_id,
                $request->start_date,
                $request->end_date
            ),
            'ledger.xlsx'
        );
    }
    public function exportPdf(Request $request)
    {
        $accountId = $request->account_id;
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        // Ambil akun
        $account = ChartOfAccount::find($accountId);

        if (!$account) {
            abort(404, "Account not found");
        }

        // Ambil entries sesuai filter
        $query = JournalEntryDetail::with('journal')
            ->where('account_id', $accountId)
            ->join('journal_entries', 'journal_entry_details.journal_entry_id', '=', 'journal_entries.id')
            ->select('journal_entry_details.*');

        if ($startDate && $endDate) {
            $query->whereBetween('journal_entries.entry_date', [$startDate, $endDate]);
        }

        $entries = $query->orderBy('journal_entries.entry_date', 'asc')->get();

        // Load PDF
        $pdf = PDF::loadView('ledger.pdf', [
            'account' => $account,
            'entries' => $entries,
            'start'   => $startDate,
            'end'     => $endDate
        ])->setPaper('A4', 'portrait');

        // Download
        return $pdf->download("Ledger-{$account->account_number}.pdf");
    }
}
