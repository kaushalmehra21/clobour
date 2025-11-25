<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyBill;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Complaint;
use App\Models\VisitorLog;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function financial(Request $request)
    {
        $user = auth()->user();
        $colonyId = $user->current_colony_id;
        $month = $request->get('month', now()->format('Y-m'));
        $year = $request->get('year', now()->format('Y'));

        // Maintenance Collection
        $billsQuery = MonthlyBill::where('month', 'like', $year . '-%');
        if (!$user->is_super_admin && $colonyId) {
            $billsQuery->where('colony_id', $colonyId);
        }
        $bills = $billsQuery->when($month, function ($q) use ($month) {
                $q->where('month', $month);
            })
            ->get();

        $totalBilled = $bills->sum('total_amount');
        $totalCollected = $bills->sum('paid_amount');
        $totalPending = $bills->sum('pending_amount');

        // Payments
        $paymentsQuery = Payment::where('status', 'completed');
        if (!$user->is_super_admin && $colonyId) {
            $paymentsQuery->where('colony_id', $colonyId);
        }
        $payments = $paymentsQuery->when($month, function ($q) use ($month) {
                $q->whereYear('payment_date', Carbon::parse($month)->year)
                  ->whereMonth('payment_date', Carbon::parse($month)->month);
            })
            ->when(!$month && $year, function ($q) use ($year) {
                $q->whereYear('payment_date', $year);
            })
            ->get();

        // Expenses
        $expensesQuery = Expense::where('status', 'approved');
        if (!$user->is_super_admin && $colonyId) {
            $expensesQuery->where('colony_id', $colonyId);
        }
        $expenses = $expensesQuery->when($month, function ($q) use ($month) {
                $q->whereYear('expense_date', Carbon::parse($month)->year)
                  ->whereMonth('expense_date', Carbon::parse($month)->month);
            })
            ->when(!$month && $year, function ($q) use ($year) {
                $q->whereYear('expense_date', $year);
            })
            ->get();

        $totalExpenses = $expenses->sum('amount');
        $netIncome = $totalCollected - $totalExpenses;

        return view('admin.reports.financial', compact(
            'month', 'year', 'bills', 'totalBilled', 'totalCollected', 
            'totalPending', 'payments', 'expenses', 'totalExpenses', 'netIncome'
        ));
    }

    public function operational(Request $request)
    {
        $user = auth()->user();
        $colonyId = $user->current_colony_id;
        $month = $request->get('month', now()->format('Y-m'));
        $year = $request->get('year', now()->format('Y'));

        // Complaints
        $complaintsQuery = Complaint::query();
        if (!$user->is_super_admin && $colonyId) {
            $complaintsQuery->where('colony_id', $colonyId);
        }
        $complaints = $complaintsQuery->when($month, function ($q) use ($month) {
                $q->whereYear('created_at', Carbon::parse($month)->year)
                  ->whereMonth('created_at', Carbon::parse($month)->month);
            })
            ->when(!$month && $year, function ($q) use ($year) {
                $q->whereYear('created_at', $year);
            })
            ->get();

        // Visitor Logs
        $visitorLogsQuery = VisitorLog::query();
        if (!$user->is_super_admin && $colonyId) {
            $visitorLogsQuery->where('colony_id', $colonyId);
        }
        $visitorLogs = $visitorLogsQuery->when($month, function ($q) use ($month) {
                $q->whereYear('entry_time', Carbon::parse($month)->year)
                  ->whereMonth('entry_time', Carbon::parse($month)->month);
            })
            ->when(!$month && $year, function ($q) use ($year) {
                $q->whereYear('entry_time', $year);
            })
            ->get();

        // Bookings
        $bookingsQuery = Booking::query();
        if (!$user->is_super_admin && $colonyId) {
            $bookingsQuery->where('colony_id', $colonyId);
        }
        $bookings = $bookingsQuery->when($month, function ($q) use ($month) {
                $q->whereYear('booking_date', Carbon::parse($month)->year)
                  ->whereMonth('booking_date', Carbon::parse($month)->month);
            })
            ->when(!$month && $year, function ($q) use ($year) {
                $q->whereYear('booking_date', $year);
            })
            ->get();

        return view('admin.reports.operational', compact(
            'month', 'year', 'complaints', 'visitorLogs', 'bookings'
        ));
    }
}
