<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $query = $user->is_super_admin 
            ? ExpenseCategory::with('colony')
            : ExpenseCategory::where('colony_id', $user->current_colony_id);
            
        $categories = $query->orderBy('name')->paginate(15);
        return view('admin.expense-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.expense-categories.create');
    }

    public function store(Request $request)
    {
        $colonyId = auth()->user()->current_colony_id;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name,NULL,id,colony_id,' . $colonyId,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['colony_id'] = $colonyId;

        ExpenseCategory::create($validated);

        return redirect(panel_route('expense-categories.index'))
            ->with('success', 'Expense category created successfully.');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('admin.expense-categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $colonyId = auth()->user()->current_colony_id;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $expenseCategory->id . ',id,colony_id,' . $colonyId,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $expenseCategory->update($validated);

        return redirect(panel_route('expense-categories.index'))
            ->with('success', 'Expense category updated successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();
        return redirect(panel_route('expense-categories.index'))
            ->with('success', 'Expense category deleted successfully.');
    }
}
