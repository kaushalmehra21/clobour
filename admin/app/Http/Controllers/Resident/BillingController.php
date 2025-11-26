<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\BillPaymentRequest;
use App\Models\Bill;
use App\Models\Payment;
use App\Services\RazorpayService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    use ApiResponseTrait;

    public function currentDues(): JsonResponse
    {
        $dues = Bill::where('resident_id', Auth::id())->where('status', 'pending')->get();
        return response()->json($this->success('Current dues fetched.', $dues));
    }

    public function history(): JsonResponse
    {
        $bills = Bill::where('resident_id', Auth::id())->orderBy('month', 'desc')->get();
        return response()->json($this->success('Bill history fetched.', $bills));
    }

    public function download(Bill $bill): JsonResponse
    {
        $this->ensureOwnership($bill);
        return response()->json($this->success('Download ready.', [
            'url' => url("/storage/bills/{$bill->bill_number}.pdf"),
        ]));
    }

    public function pay(BillPaymentRequest $request, Bill $bill, RazorpayService $razorpay): JsonResponse
    {
        $this->ensureOwnership($bill);

        if ($request->payment_method === 'razorpay') {
            $order = $razorpay->createOrder($bill->amount * 100);
            return response()->json($this->success('Order created.', $order));
        }

        Payment::create([
            'bill_id' => $bill->id,
            'resident_id' => Auth::id(),
            'amount' => $bill->amount,
            'method' => $request->payment_method,
            'status' => 'initiated',
        ]);

        return response()->json($this->success('Payment initiated.'));
    }

    public function receipt(Bill $bill): JsonResponse
    {
        $this->ensureOwnership($bill);
        $payment = Payment::where('bill_id', $bill->id)->latest()->first();

        return response()->json($this->success('Receipt fetched.', $payment));
    }

    protected function ensureOwnership(Bill $bill): void
    {
        if ($bill->resident_id !== Auth::id()) {
            abort(403, 'Not authorized.');
        }
    }
}

