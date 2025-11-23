<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CashFlowExport;

class CashFlowController extends Controller
{
    // Fungsi utama untuk ambil data Cash Flow (dipakai index, PDF, Excel)
    public static function generateData(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $cashAccounts = ChartOfAccount::with('details.journal', 'category')
            ->whereHas('category', function ($q) {
                $q->where('category_type', 'Asset')
                    ->where('category_name', 'like', '%Kas%');
            })->get();

        $cashIn = [];
        $cashOut = [];
        $totalIn = 0;
        $totalOut = 0;

        foreach ($cashAccounts as $acc) {
            $query = $acc->details();
            if ($startDate && $endDate) {
                $query->whereHas('journal', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('entry_date', [$startDate, $endDate]);
                });
            }

            $details = $query->get();

            foreach ($details as $d) {
                if ($d->debit_amount > 0) {
                    $cashIn[] = [
                        'date' => $d->journal->entry_date,
                        'desc' => $d->description,
                        'amount' => $d->debit_amount
                    ];
                    $totalIn += $d->debit_amount;
                }
                if ($d->credit_amount > 0) {
                    $cashOut[] = [
                        'date' => $d->journal->entry_date,
                        'desc' => $d->description,
                        'amount' => $d->credit_amount
                    ];
                    $totalOut += $d->credit_amount;
                }
            }
        }

        $netCash = $totalIn - $totalOut;

        return compact('cashIn', 'cashOut', 'totalIn', 'totalOut', 'netCash', 'startDate', 'endDate');
    }

    // Halaman utama
    public function index(Request $request)
    {
        $data = $this->generateData($request);
        return view('cashflow.index', $data);
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        $data = $this->generateData($request);
        $pdf = PDF::loadView('cashflow.pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->stream("CashFlow-{$data['startDate']}-to-{$data['endDate']}.pdf");
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new CashFlowExport($request),
            "CashFlow-{$request->start_date}-to-{$request->end_date}.xlsx"
        );
    }
}
