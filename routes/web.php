<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    UserController,
    AccountCategoryController,
    AccountCodeController,
    ChartOfAccountController,
    JournalEntryController,
    GeneralLedgerController,
    TrialBalanceController,
    BalanceSheetController,
    ProfitLossController,
    CashFlowController,
    DashboardController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome');

// ---------------------------
// Dashboard & Charts
// ---------------------------
Route::middleware(['auth', 'verified', 'role:admin|accountant'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::prefix('chart')->group(function () {
        Route::get('/cashflow', [DashboardController::class, 'chartCashFlow']);
        Route::get('/income-expense', [DashboardController::class, 'chartIncomeExpense']);
        Route::get('/composition', [DashboardController::class, 'chartComposition']);
    });
});

// ---------------------------
// Profile (authenticated)
// ---------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ---------------------------
// Admin Only
// ---------------------------
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});

// ---------------------------
// Admin + Accountant
// ---------------------------
Route::middleware(['auth', 'role:admin|accountant'])->group(function () {

    // Master Data
    Route::resource('categories', AccountCategoryController::class);
    Route::resource('codes', AccountCodeController::class);
    Route::resource('accounts', ChartOfAccountController::class);

    // Journal
    Route::resource('journals', JournalEntryController::class);

    // Reports
    Route::get('ledger', [GeneralLedgerController::class, 'index'])->name('ledger.index');
    Route::get('/ledger/print', [GeneralLedgerController::class, 'print'])->name('ledger.print');
    Route::get('/ledger/excel/{account_id}', [GeneralLedgerController::class, 'exportExcel'])->name('ledger.export.excel');
    Route::get('/ledger/export/pdf', [GeneralLedgerController::class, 'exportPdf'])->name('ledger.export.pdf');

    Route::get('trial-balance', [TrialBalanceController::class, 'index'])->name('trialbalance.index');
    Route::get('/trial-balance/export/pdf', [TrialBalanceController::class, 'exportPdf'])->name('trialbalance.export.pdf');
    Route::get('/trial-balance/export/excel', [TrialBalanceController::class, 'exportExcel'])->name('trialbalance.export.excel');
    Route::get('/trial-balance/print', [TrialBalanceController::class, 'print'])->name('trialbalance.print');

    Route::get('balance-sheet', [BalanceSheetController::class, 'index'])->name('balancesheet.index');
    Route::get('/balancesheet/export-pdf', [BalanceSheetController::class, 'exportPdf'])->name('balancesheet.export.pdf');
    Route::get('/balancesheet/export-excel', [BalanceSheetController::class, 'exportExcel'])->name('balancesheet.export.excel');

    Route::get('profit-loss', [ProfitLossController::class, 'index'])->name('profitloss.index');
    Route::get('profitloss/export-pdf', [ProfitLossController::class, 'exportPdf'])->name('profitloss.exportPdf');
    Route::get('profitloss/export-excel', [ProfitLossController::class, 'exportExcel'])->name('profitloss.exportExcel');

    Route::get('cash-flow', [CashFlowController::class, 'index'])->name('cashflow.index');
    Route::get('/cashflow/export-pdf', [CashFlowController::class, 'exportPdf'])->name('cashflow.exportPdf');
    Route::get('/cashflow/export-excel', [CashFlowController::class, 'exportExcel'])->name('cashflow.exportExcel');
});

require __DIR__ . '/auth.php';
