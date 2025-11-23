<?php

namespace App\Http\Controllers;

// ProfitLossController.php
use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProfitLossExport; // nanti kita buat

class ProfitLossController extends Controller
{
    // Fungsi utama ambil data P&L
    public static function generateData(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $accounts = ChartOfAccount::with('details.journal', 'category')->get();

        $revenues = [];
        $expenses = [];
        $totalRevenue = 0;
        $totalExpense = 0;

        foreach ($accounts as $acc) {
            if (!in_array($acc->category->category_type, ['Revenue', 'Expense'])) continue;

            $query = $acc->details();
            if ($startDate && $endDate) {
                $query->whereHas('journal', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('entry_date', [$startDate, $endDate]);
                });
            }

            $details = $query->get();
            $debit  = $details->sum('debit_amount');
            $credit = $details->sum('credit_amount');
            $balance = ($acc->normal_balance == 'Debit') ? $debit - $credit : $credit - $debit;

            if ($acc->category->category_type == 'Revenue') {
                $revenues[] = ['acc' => $acc, 'balance' => $balance];
                $totalRevenue += $balance;
            } elseif ($acc->category->category_type == 'Expense') {
                $expenses[] = ['acc' => $acc, 'balance' => $balance];
                $totalExpense += $balance;
            }
        }

        $netProfit = $totalRevenue - $totalExpense;

        return compact(
            'revenues',
            'expenses',
            'totalRevenue',
            'totalExpense',
            'netProfit',
            'startDate',
            'endDate'
        );
    }

    // Index
    public function index(Request $request)
    {
        $data = $this->generateData($request);
        return view('profitloss.index', $data);
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        $data = $this->generateData($request);
        $pdf = PDF::loadView('profitloss.pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->stream("ProfitLoss-{$data['startDate']}-to-{$data['endDate']}.pdf");
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        return Excel::download(new ProfitLossExport($request), "ProfitLoss-{$request->start_date}-to-{$request->end_date}.xlsx");
    }
}
