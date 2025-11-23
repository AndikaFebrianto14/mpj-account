<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\ChartOfAccount;

class BalanceSheetExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $startDate = $this->request->start_date;
        $endDate   = $this->request->end_date;

        $accounts = ChartOfAccount::with('details.journal', 'category')->get();

        $data = collect();

        foreach ($accounts as $acc) {
            $query = $acc->details();

            if ($startDate && $endDate) {
                $query->whereHas('journal', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('entry_date', [$startDate, $endDate]);
                });
            }

            $details = $query->get();
            $debit   = $details->sum('debit_amount');
            $credit  = $details->sum('credit_amount');
            $balance = ($acc->normal_balance == 'Debit') ? $debit - $credit : $credit - $debit;

            if ($acc->category) {
                $data->push([
                    'account_number' => $acc->account_number,
                    'account_name'   => $acc->account_name,
                    'balance'        => $balance,
                    'type'           => $acc->category->category_type,
                ]);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Account Number',
            'Account Name',
            'Balance',
            'Type',
        ];
    }
}
