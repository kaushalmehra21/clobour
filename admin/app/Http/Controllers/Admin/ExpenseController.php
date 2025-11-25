<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->is_super_admin 
            ? Expense::with(['category', 'vendor', 'createdBy', 'colony'])
            : Expense::with(['category', 'vendor', 'createdBy'])->where('colony_id', $user->current_colony_id);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('expense_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(15);
        
        $categoryQuery = ExpenseCategory::where('is_active', true);
        $vendorQuery = Vendor::where('is_active', true);
        if (!$user->is_super_admin) {
            $categoryQuery->where('colony_id', $user->current_colony_id);
            $vendorQuery->where('colony_id', $user->current_colony_id);
        }
        $categories = $categoryQuery->get();
        $vendors = $vendorQuery->get();

        return view('admin.expenses.index', compact('expenses', 'categories', 'vendors'));
    }

    public function create()
    {
        $user = auth()->user();
        $colonyId = $user->current_colony_id;
        
        $categoryQuery = ExpenseCategory::where('is_active', true);
        $vendorQuery = Vendor::where('is_active', true);
        if (!$user->is_super_admin) {
            $categoryQuery->where('colony_id', $colonyId);
            $vendorQuery->where('colony_id', $colonyId);
        }
        $categories = $categoryQuery->get();
        $vendors = $vendorQuery->get();
        return view('admin.expenses.create', compact('categories', 'vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:expense_categories,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,cheque,online,bank_transfer',
            'receipt_number' => 'nullable|string|max:255',
            'receipt_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('receipt_file')) {
            $validated['receipt_file'] = $request->file('receipt_file')->store('expenses/receipts', 'public');
        }

        $validated['created_by'] = auth()->id();
        $validated['colony_id'] = auth()->user()->current_colony_id;

        Expense::create($validated);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load(['category', 'vendor', 'createdBy', 'approvedBy']);
        return view('admin.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::where('is_active', true)->get();
        $vendors = Vendor::where('is_active', true)->get();
        return view('admin.expenses.edit', compact('expense', 'categories', 'vendors'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:expense_categories,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,cheque,online,bank_transfer',
            'receipt_number' => 'nullable|string|max:255',
            'receipt_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('receipt_file')) {
            if ($expense->receipt_file) {
                Storage::disk('public')->delete($expense->receipt_file);
            }
            $validated['receipt_file'] = $request->file('receipt_file')->store('expenses/receipts', 'public');
        }

        if ($request->status == 'approved' && $expense->status != 'approved') {
            $validated['approved_by'] = auth()->id();
            $validated['approved_at'] = now();
        }

        $expense->update($validated);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->receipt_file) {
            Storage::disk('public')->delete($expense->receipt_file);
        }
        $expense->delete();

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
