<?php

namespace App\Http\Controllers;

use App\Models\AccountCode;
use App\Models\AccountCategory;
use Illuminate\Http\Request;

class AccountCodeController extends Controller
{
    public function index()
    {
        $codes = AccountCode::with('category')->paginate(10);
        return view('codes.index', compact('codes'));
    }

    public function create()
    {
        $categories = AccountCategory::all();
        return view('codes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:account_codes',
            'kelkode_id' => 'required|exists:account_categories,id',
            'code_name' => 'required'
        ]);

        AccountCode::create($request->all());
        return redirect()->route('codes.index')->with('success', 'Account code created');
    }

    public function edit(AccountCode $code)
    {
        $categories = AccountCategory::all();
        return view('codes.edit', compact('code', 'categories'));
    }

    public function update(Request $request, AccountCode $code)
    {
        $request->validate([
            'code' => 'required|unique:account_codes,code,' . $code->id,
            'kelkode_id' => 'required|exists:account_categories,id',
            'code_name' => 'required'
        ]);

        $code->update($request->all());
        return redirect()->route('codes.index')->with('success', 'Account code updated');
    }

    public function destroy(AccountCode $code)
    {
        $code->delete();
        return redirect()->route('codes.index')->with('success', 'Account code deleted');
    }
}
