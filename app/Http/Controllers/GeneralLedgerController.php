<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryDetail;

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
}
