<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\VisitorLog;
use App\Models\Resident;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->is_super_admin 
            ? Visitor::with(['resident', 'unit', 'colony'])
            : Visitor::with(['resident', 'unit'])->where('colony_id', $user->current_colony_id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('expected_arrival', $request->date);
        }

        $visitors = $query->orderBy('expected_arrival', 'desc')->paginate(15);
        $units = $user->is_super_admin 
            ? Unit::all()
            : Unit::where('colony_id', $user->current_colony_id)->get();

        return view('admin.visitors.index', compact('visitors', 'units'));
    }

    public function approve(Visitor $visitor)
    {
        $visitor->update([
            'status' => 'approved',
            'approved_at' => now(),
            'otp' => str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
            'qr_code' => Str::random(32),
        ]);

        return redirect()->back()->with('success', 'Visitor approved successfully.');
    }

    public function reject(Visitor $visitor)
    {
        $visitor->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Visitor rejected.');
    }

    public function show(Visitor $visitor)
    {
        $visitor->load(['resident', 'unit', 'logs']);
        return view('admin.visitors.show', compact('visitor'));
    }

    public function logEntry(Request $request)
    {
        $validated = $request->validate([
            'visitor_id' => 'nullable|exists:visitors,id',
            'unit_id' => 'required|exists:units,id',
            'visitor_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'purpose' => 'nullable|string',
            'vehicle_number' => 'nullable|string',
        ]);

        VisitorLog::create([
            'visitor_id' => $validated['visitor_id'] ?? null,
            'unit_id' => $validated['unit_id'],
            'visitor_name' => $validated['visitor_name'],
            'phone' => $validated['phone'] ?? null,
            'purpose' => $validated['purpose'] ?? null,
            'vehicle_number' => $validated['vehicle_number'] ?? null,
            'entry_time' => now(),
            'entry_verified_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Visitor entry logged successfully.');
    }

    public function logExit(VisitorLog $log)
    {
        $log->update([
            'exit_time' => now(),
            'exit_verified_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Visitor exit logged successfully.');
    }

    public function logs()
    {
        $user = auth()->user();
        
        $query = $user->is_super_admin 
            ? VisitorLog::with(['unit', 'visitor', 'entryVerifiedBy', 'exitVerifiedBy', 'colony'])
            : VisitorLog::with(['unit', 'visitor', 'entryVerifiedBy', 'exitVerifiedBy'])
                ->where('colony_id', $user->current_colony_id);
        
        $logs = $query->orderBy('entry_time', 'desc')->paginate(15);

        return view('admin.visitors.logs', compact('logs'));
    }
}
