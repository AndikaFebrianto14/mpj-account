<?php
// app/Exports/ProfitLossExport.php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfitLossController;

class ProfitLossExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $data = ProfitLossController::generateData($this->request);

        $collection = collect();

        foreach ($data['revenues'] as $r) {
            $collection->push([
                'account' => $r['acc']->account_number . ' - ' . $r['acc']->account_name,
                'amount' => $r['balance'],
                'type' => 'Revenue'
            ]);
        }

        foreach ($data['expenses'] as $e) {
            $collection->push([
                'account' => $e['acc']->account_number . ' - ' . $e['acc']->account_name,
                'amount' => $e['balance'],
                'type' => 'Expense'
            ]);
        }

        return $collection;
    }

    public function headings(): array
    {
        return ['Account', 'Amount', 'Type'];
    }
}
