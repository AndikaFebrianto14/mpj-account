<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    public function index()
    {
        $entries = JournalEntry::orderBy('entry_date', 'desc')->paginate(10);
        return view('journals.index', compact('entries'));
    }

    public function create()
    {
        $accounts = ChartOfAccount::where('is_active', 1)->orderBy('account_number')->get();
        return view('journals.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required',
            'account_id.*' => 'required|exists:chart_of_accounts,id',
            'debit_amount.*' => 'nullable|numeric|min:0',
            'credit_amount.*' => 'nullable|numeric|min:0',
        ]);

        $debits  = collect($request->debit_amount)->map(fn($v) => floatval($v))->sum();
        $credits = collect($request->credit_amount)->map(fn($v) => floatval($v))->sum();

        if ($debits != $credits) {
            return back()
                ->withErrors(['balance' => "Total Debit ($debits) dan Credit ($credits) tidak balance"])
                ->withInput();
        }

        DB::transaction(function () use ($request, $debits) {
            $entry_number = 'JN-' . now()->format('Ymd-His');

            $journal = JournalEntry::create([
                'entry_date' => $request->entry_date,
                'entry_number' => $entry_number,
                'description' => $request->description,
                'reference_number' => $request->reference_number,
                'total_amount' => $debits
            ]);

            foreach ($request->account_id as $i => $acc) {
                if (!$acc) continue;

                JournalEntryDetail::create([
                    'journal_entry_id' => $journal->id,
                    'account_id' => $acc,
                    'debit_amount' => floatval($request->debit_amount[$i] ?? 0),
                    'credit_amount' => floatval($request->credit_amount[$i] ?? 0),
                    'description' => $request->line_description[$i] ?? null,
                ]);
            }
        });

        return redirect()->route('journals.index')->with('success', 'Journal entry created');
    }

    public function show(JournalEntry $journal)
    {
        $journal->load('details.account');
        return view('journals.show', compact('journal'));
    }

    public function destroy(JournalEntry $journal)
    {
        $journal->delete();
        return redirect()->route('journals.index')->with('success', 'Journal entry deleted');
    }
}
