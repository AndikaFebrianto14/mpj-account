<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BalanceSheetExport; // nanti kita buat

class BalanceSheetController extends Controller
{
    // Generate data utama untuk index, PDF, Excel
    private function generateData(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $accounts = ChartOfAccount::with('details.journal', 'category')->get();

        $report = [
            'assets'      => [],
            'liabilities' => [],
            'equity'      => [],
        ];

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
                switch ($acc->category->category_type) {
                    case 'Asset':
                        $report['assets'][] = ['acc' => $acc, 'balance' => $balance];
                        break;
                    case 'Liability':
                        $report['liabilities'][] = ['acc' => $acc, 'balance' => $balance];
                        break;
                    case 'Equity':
                        $report['equity'][] = ['acc' => $acc, 'balance' => $balance];
                        break;
                }
            }
        }

        $totalAssets      = collect($report['assets'])->sum('balance');
        $totalLiabilities = collect($report['liabilities'])->sum('balance');
        $totalEquity      = collect($report['equity'])->sum('balance');

        return compact('report', 'startDate', 'endDate', 'totalAssets', 'totalLiabilities', 'totalEquity');
    }

    // Halaman utama
    public function index(Request $request)
    {
        $data = $this->generateData($request);
        return view('balancesheet.index', $data);
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        $data = $this->generateData($request);

        $pdf = PDF::loadView('balancesheet.pdf', $data)->setPaper('A4', 'portrait');

        // Stream untuk preview, bisa dicetak langsung
        return $pdf->stream("Balance-Sheet-{$data['startDate']}-to-{$data['endDate']}.pdf");
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new BalanceSheetExport($request),
            "Balance-Sheet-{$request->start_date}-to-{$request->end_date}.xlsx"
        );
    }
}
