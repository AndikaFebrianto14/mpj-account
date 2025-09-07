<?php

namespace App\Http\Controllers;

use App\Models\AccountCategory;
use Illuminate\Http\Request;

class AccountCategoryController extends Controller
{
    public function index()
    {
        $categories = AccountCategory::paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_code' => 'required|unique:account_categories',
            'category_name' => 'required',
            'category_type' => 'required|in:Asset,Liability,Equity,Revenue,Expense'
        ]);

        AccountCategory::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Category created');
    }

    public function edit(AccountCategory $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, AccountCategory $category)
    {
        $request->validate([
            'category_code' => 'required|unique:account_categories,category_code,' . $category->id,
            'category_name' => 'required',
            'category_type' => 'required|in:Asset,Liability,Equity,Revenue,Expense'
        ]);

        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Category updated');
    }

    public function destroy(AccountCategory $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted');
    }
}
