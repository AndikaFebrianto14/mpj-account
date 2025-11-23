<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrialBalanceExport implements FromArray, WithHeadings
{
    protected $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function headings(): array
    {
        return [
            'Account Number',
            'Account Name',
            'Debit',
            'Credit',
            'Ending Balance'
        ];
    }

    public function array(): array
    {
        return $this->report->map(function ($r) {
            return [
                $r['account_number'],
                $r['account_name'],
                $r['debit'],
                $r['credit'],
                $r['ending'],
            ];
        })->toArray();
    }
}
