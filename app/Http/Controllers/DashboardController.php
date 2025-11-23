<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntryDetail;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;
// Ensure the correct namespace for AccountingHelper is imported
use App\Helpers\AccountingHelper; // Replace 'App\Helpers' with the actual namespace of AccountingHelper

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua akun berdasarkan tipe PSAK
        $assets      = ChartOfAccount::whereHas('category', fn($q) => $q->where('category_type', 'Asset'))->get();
        $liabilities = ChartOfAccount::whereHas('category', fn($q) => $q->where('category_type', 'Liability'))->get();
        $equity      = ChartOfAccount::whereHas('category', fn($q) => $q->where('category_type', 'Equity'))->get();
        $revenues    = ChartOfAccount::whereHas('category', fn($q) => $q->where('category_type', 'Revenue'))->get();
        $expenses    = ChartOfAccount::whereHas('category', fn($q) => $q->where('category_type', 'Expense'))->get();

        // Hitung saldo tiap kelompok
        $totalAssets      = $assets->sum(fn($a) => AccountingHelper::saldoAkun($a->id));
        $totalLiabilities = $liabilities->sum(fn($a) => AccountingHelper::saldoAkun($a->id));
        $totalEquity      = $equity->sum(fn($a) => AccountingHelper::saldoAkun($a->id));

        // Profit = revenue - expenses
        $totalRevenue = $revenues->sum(fn($a) => AccountingHelper::saldoAkun($a->id));
        $totalExpense = $expenses->sum(fn($a) => AccountingHelper::saldoAkun($a->id));
        $profitLoss   = $totalRevenue - $totalExpense;

        $latestTransactions = JournalEntry::with('details.account')
            ->orderBy('entry_date', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'profitLoss',
            'latestTransactions'
        ));
    }

    // 👉 Grafik Arus Kas
    public function chartCashFlow()
    {
        $months = [];
        $values = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthStart = Carbon::create(null, $i, 1)->startOfMonth();
            $monthEnd   = Carbon::create(null, $i, 1)->endOfMonth();

            // akun kas / bank = category_type Asset + code kb/kb1
            $kas = ChartOfAccount::where('account_name', 'LIKE', '%Kas%')->pluck('id');

            $saldoBulan = JournalEntryDetail::whereIn('account_id', $kas)
                ->join('journal_entries', 'journal_entries.id', 'journal_entry_details.journal_entry_id')
                ->whereBetween('journal_entries.entry_date', [$monthStart, $monthEnd])
                ->selectRaw("SUM(debit_amount - credit_amount) as total")
                ->first()
                ->total ?? 0;

            $months[] = $monthStart->format('M');
            $values[] = $saldoBulan;
        }

        return response()->json([
            'labels' => $months,
            'data'   => $values
        ]);
    }

    // 👉 Grafik Pendapatan & Beban Bulanan
    public function chartIncomeExpense()
    {
        $months = [];
        $income = [];
        $expense = [];

        for ($i = 1; $i <= 12; $i++) {
            $mStart = Carbon::create(null, $i, 1)->startOfMonth();
            $mEnd   = Carbon::create(null, $i, 1)->endOfMonth();

            $incomeAccounts = ChartOfAccount::whereHas('category', fn($q) => $q->where('category_type', 'Revenue'))->pluck('id');
            $expenseAccounts = ChartOfAccount::whereHas('category', fn($q) => $q->where('category_type', 'Expense'))->pluck('id');

            $incomeValue = JournalEntryDetail::whereIn('account_id', $incomeAccounts)
                ->join('journal_entries', 'journal_entries.id', '=', 'journal_entry_details.journal_entry_id')
                ->whereBetween('journal_entries.entry_date', [$mStart, $mEnd])
                ->sum('credit_amount');

            $expenseValue = JournalEntryDetail::whereIn('account_id', $expenseAccounts)
                ->join('journal_entries', 'journal_entries.id', '=', 'journal_entry_details.journal_entry_id')
                ->whereBetween('journal_entries.entry_date', [$mStart, $mEnd])
                ->sum('debit_amount');

            $months[] = $mStart->format('M');
            $income[] = $incomeValue;
            $expense[] = $expenseValue;
        }

        return response()->json([
            'labels' => $months,
            'income' => $income,
            'expense' => $expense
        ]);
    }

    // 👉 Doughnut Chart Composition
    public function chartComposition()
    {
        // Sama seperti index()
        $assets      = ChartOfAccount::whereHas('category', fn($q) => $q->where('category_type', 'Asset'))->get();
        $liabilities = ChartOfAccount::whereHas('category', fn($q) => $q->where('category_type', 'Liability'))->get();
        $equity      = ChartOfAccount::whereHas('category', fn($q) => $q->where('category_type', 'Equity'))->get();

        $totalAssets      = $assets->sum(fn($a) => AccountingHelper::saldoAkun($a->id));
        $totalLiabilities = $liabilities->sum(fn($a) => AccountingHelper::saldoAkun($a->id));
        $totalEquity      = $equity->sum(fn($a) => AccountingHelper::saldoAkun($a->id));

        return response()->json([
            'labels' => ['Aset', 'Liabilitas', 'Ekuitas'],
            'data'   => [$totalAssets, $totalLiabilities, $totalEquity]
        ]);
    }
}
