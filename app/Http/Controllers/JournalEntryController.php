<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    /**
     * Display list of journals
     */
    public function index()
    {
        $entries = JournalEntry::orderBy('entry_date', 'desc')
            ->paginate(10);

        return view('journals.index', compact('entries'));
    }

    /**
     * Show form to create a new journal entry
     */
    public function create()
    {
        $accounts = ChartOfAccount::where('is_active', 1)
            ->orderBy('account_number')
            ->get();

        return view('journals.create', compact('accounts'));
    }

    /**
     * Store journal entry
     */
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

        return redirect()->route('journals.index')
            ->with('success', 'Journal entry created');
    }

    /**
     * Display detail journal entry
     */
    public function show(JournalEntry $journal)
    {
        $journal->load('details.account');
        return view('journals.show', compact('journal'));
    }

    /**
     * Show form to edit journal entry
     */
    public function edit(JournalEntry $journal)
    {
        $journal->load('details');
        $accounts = ChartOfAccount::where('is_active', 1)
            ->orderBy('account_number')
            ->get();

        return view('journals.edit', compact('journal', 'accounts'));
    }

    /**
     * Update journal entry
     */
    public function update(Request $request, JournalEntry $journal)
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

        DB::transaction(function () use ($request, $journal, $debits) {

            // Update header
            $journal->update([
                'entry_date' => $request->entry_date,
                'description' => $request->description,
                'reference_number' => $request->reference_number,
                'total_amount' => $debits
            ]);

            // Delete old details
            $journal->details()->delete();

            // Insert new details
            foreach ($request->account_id as $i => $acc) {
                JournalEntryDetail::create([
                    'journal_entry_id' => $journal->id,
                    'account_id' => $acc,
                    'debit_amount' => floatval($request->debit_amount[$i] ?? 0),
                    'credit_amount' => floatval($request->credit_amount[$i] ?? 0),
                    'description' => $request->line_description[$i] ?? null,
                ]);
            }
        });

        return redirect()->route('journals.show', $journal->id)
            ->with('success', 'Journal entry updated');
    }

    /**
     * Delete journal entry
     */
    public function destroy(JournalEntry $journal)
    {
        DB::transaction(function () use ($journal) {
            $journal->details()->delete();
            $journal->delete();
        });

        return redirect()->route('journals.index')
            ->with('success', 'Journal entry deleted');
    }
}
