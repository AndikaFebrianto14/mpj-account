<?php

namespace App\Helpers;

use App\Models\JournalEntryDetail;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;

class AccountingHelper
{
    // Hitung saldo per akun (sum debit - sum kredit)
    public static function saldoAkun($accountId, $from = null, $to = null)
    {
        $query = JournalEntryDetail::where('account_id', $accountId)
            ->join('journal_entries', 'journal_entries.id', '=', 'journal_entry_details.journal_entry_id');

        if ($from && $to) {
            $query->whereBetween('journal_entries.entry_date', [$from, $to]);
        }

        $debit  = $query->sum('debit_amount');
        $credit = $query->sum('credit_amount');

        // normal balance debit → saldo = debit - kredit
        // normal balance credit → saldo = kredit - debit
        $coa = ChartOfAccount::find($accountId);

        return $coa->normal_balance === 'Debit'
            ? $debit - $credit
            : $credit - $debit;
    }
}
