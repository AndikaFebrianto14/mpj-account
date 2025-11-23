<?php

namespace App\Exports;

use App\Models\JournalEntryDetail;
use App\Models\ChartOfAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LedgerExport implements FromCollection, WithHeadings, WithMapping
{
    protected $account_id;
    protected $start;
    protected $end;

    public function __construct($account_id, $start, $end)
    {
        $this->account_id = $account_id;
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        return JournalEntryDetail::with('journal')
            ->where('account_id', $this->account_id)
            ->join('journal_entries', 'journal_entry_details.journal_entry_id', '=', 'journal_entries.id')
            ->select('journal_entry_details.*')
            ->whereBetween('journal_entries.entry_date', [$this->start, $this->end])
            ->orderBy('journal_entries.entry_date', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Entry Number',
            'Description',
            'Debit',
            'Credit'
        ];
    }

    public function map($row): array
    {
        return [
            optional($row->journal)->entry_date,
            optional($row->journal)->entry_number,
            $row->description,
            $row->debit_amount,
            $row->credit_amount,
        ];
    }
}
