<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyBill;
use App\Models\Unit;
use App\Services\BillingService;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    protected $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->is_super_admin 
            ? MonthlyBill::with(['unit', 'resident', 'colony'])
            : MonthlyBill::with(['unit', 'resident'])->where('colony_id', $user->current_colony_id);

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $bills = $query->orderBy('bill_date', 'desc')->paginate(15);
        $units = $user->is_super_admin 
            ? Unit::all()
            : Unit::where('colony_id', $user->current_colony_id)->get();

        return view('admin.billing.index', compact('bills', 'units'));
    }

    public function generate(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $colonyId = auth()->user()->current_colony_id;
        
        $result = $this->billingService->generateMonthlyBills($month, $colonyId);
        
        $prefix = auth()->user()->is_super_admin ? 'super-admin' : 'colony';
        return redirect()->route("{$prefix}.billing.index", ['month' => $month])
            ->with('success', "Generated {$result['generated']} bills successfully.");
    }

    public function show(MonthlyBill $billing)
    {
        $billing->load(['unit', 'resident', 'payments']);
        return view('admin.billing.show', compact('billing'));
    }

    public function destroy(MonthlyBill $billing)
    {
        $billing->delete();
        return redirect(panel_route('billing.index'))
            ->with('success', 'Bill deleted successfully.');
    }
}
