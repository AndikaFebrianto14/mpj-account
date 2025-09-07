<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\AccountCategory;
use App\Models\AccountCode;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    public function index()
    {
        $accounts = ChartOfAccount::with(['category', 'code', 'parent'])->paginate(15);
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        $categories = AccountCategory::all();
        $codes = AccountCode::all();
        $parents = ChartOfAccount::all();
        return view('accounts.create', compact('categories', 'codes', 'parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_number' => 'required|unique:chart_of_accounts',
            'account_name' => 'required',
            'account_type' => 'required|in:Debit,Riil',
            'normal_balance' => 'required|in:Debit,Credit',
            'code_id' => 'required|exists:account_codes,id',
            'kelkode_id' => 'required|exists:account_categories,id'
        ]);

        ChartOfAccount::create($request->all());
        return redirect()->route('accounts.index')->with('success', 'Account created');
    }

    public function edit(ChartOfAccount $account)
    {
        $categories = AccountCategory::all();
        $codes = AccountCode::all();
        $parents = ChartOfAccount::where('id', '!=', $account->id)->get();
        return view('accounts.edit', compact('account', 'categories', 'codes', 'parents'));
    }

    public function update(Request $request, ChartOfAccount $account)
    {
        $request->validate([
            'account_number' => 'required|unique:chart_of_accounts,account_number,' . $account->id,
            'account_name' => 'required',
            'account_type' => 'required|in:Debit,Riil',
            'normal_balance' => 'required|in:Debit,Credit',
            'code_id' => 'required|exists:account_codes,id',
            'kelkode_id' => 'required|exists:account_categories,id'
        ]);

        $account->update($request->all());
        return redirect()->route('accounts.index')->with('success', 'Account updated');
    }

    public function destroy(ChartOfAccount $account)
    {
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Account deleted');
    }
}
