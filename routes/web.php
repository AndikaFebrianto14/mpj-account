<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountCategoryController;
use App\Http\Controllers\AccountCodeController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\ProfitLossController;
use App\Http\Controllers\CashFlowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin|accountant'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});

Route::middleware(['auth', 'role:admin|accountant'])->group(function () {
    Route::resource('categories', AccountCategoryController::class);
    Route::resource('codes', AccountCodeController::class);
    Route::resource('accounts', ChartOfAccountController::class);
    Route::resource('journals', JournalEntryController::class);
});

Route::middleware(['auth', 'role:admin|accountant'])->group(function () {
    Route::get('ledger', [GeneralLedgerController::class, 'index'])->name('ledger.index');
});

Route::middleware(['auth', 'role:admin|accountant'])->group(function () {
    Route::get('trial-balance', [TrialBalanceController::class, 'index'])->name('trialbalance.index');
});

Route::middleware(['auth', 'role:admin|accountant'])->group(function () {
    Route::get('balance-sheet', [BalanceSheetController::class, 'index'])->name('balancesheet.index');
});

Route::middleware(['auth', 'role:admin|accountant'])->group(function () {
    Route::get('profit-loss', [ProfitLossController::class, 'index'])->name('profitloss.index');
});

Route::middleware(['auth', 'role:admin|accountant'])->group(function () {
    Route::get('cash-flow', [CashFlowController::class, 'index'])->name('cashflow.index');
});

require __DIR__ . '/auth.php';
