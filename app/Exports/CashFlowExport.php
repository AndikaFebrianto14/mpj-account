<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use App\Http\Controllers\CashFlowController;


class CashFlowExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Gunakan logika sama seperti generateData
        $data = CashFlowController::generateData($this->request);

        $collection = collect();

        foreach ($data['cashIn'] as $c) {
            $collection->push([
                'date' => $c['date'],
                'description' => $c['desc'],
                'amount' => $c['amount'],
                'type' => 'Cash In'
            ]);
        }

        foreach ($data['cashOut'] as $c) {
            $collection->push([
                'date' => $c['date'],
                'description' => $c['desc'],
                'amount' => $c['amount'],
                'type' => 'Cash Out'
            ]);
        }

        return $collection;
    }

    public function headings(): array
    {
        return ['Date', 'Description', 'Amount', 'Type'];
    }
}
