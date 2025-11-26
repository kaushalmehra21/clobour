<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\MonthlyBill;
use App\Models\Unit;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->is_super_admin 
            ? Payment::with(['unit', 'resident', 'bill', 'colony'])
            : Payment::with(['unit', 'resident', 'bill'])->where('colony_id', $user->current_colony_id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);
        $units = $user->is_super_admin 
            ? Unit::all()
            : Unit::where('colony_id', $user->current_colony_id)->get();

        return view('admin.payments.index', compact('payments', 'units'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $colonyId = $user->current_colony_id;
        
        $units = $user->is_super_admin 
            ? Unit::all()
            : Unit::where('colony_id', $colonyId)->get();
        $bills = $user->is_super_admin 
            ? MonthlyBill::where('status', '!=', 'paid')->get()
            : MonthlyBill::where('colony_id', $colonyId)->where('status', '!=', 'paid')->get();
        $billId = $request->get('bill_id');
        
        return view('admin.payments.create', compact('units', 'bills', 'billId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bill_id' => 'nullable|exists:monthly_bills,id',
            'unit_id' => 'required|exists:units,id',
            'resident_id' => 'nullable|exists:residents,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,cheque,online,bank_transfer,upi,card',
            'status' => 'required|in:pending,completed,failed,refunded',
            'payment_date' => 'required|date',
            'transaction_id' => 'nullable|string',
            'cheque_number' => 'nullable|string',
            'cheque_date' => 'nullable|date',
            'bank_name' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $payment = $this->paymentService->recordPayment($validated);

        return redirect(panel_route('payments.index'))
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['unit', 'resident', 'bill', 'receivedBy']);
        return view('admin.payments.show', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect(panel_route('payments.index'))
            ->with('success', 'Payment deleted successfully.');
    }
}
