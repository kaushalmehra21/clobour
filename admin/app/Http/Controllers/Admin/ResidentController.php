<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\Unit;
use App\Models\MoveInOutLog;
use App\Repositories\ResidentRepository;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    protected $residentRepository;

    public function __construct(ResidentRepository $residentRepository)
    {
        $this->residentRepository = $residentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Resident::with('unit');

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $residents = $query->paginate(15);
        $units = Unit::all();

        return view('admin.residents.index', compact('residents', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $units = $user->is_super_admin 
            ? Unit::all()
            : Unit::where('colony_id', $user->current_colony_id)->get();
        return view('admin.residents.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:residents,email|max:255',
            'phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'type' => 'required|in:owner,tenant,family_member',
            'status' => 'required|in:active,inactive,moved_out',
            'date_of_birth' => 'nullable|date',
            'aadhar_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'move_in_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $resident = $this->residentRepository->create($validated);

        // Log move-in if date is provided
        if ($request->filled('move_in_date')) {
            MoveInOutLog::create([
                'resident_id' => $resident->id,
                'unit_id' => $resident->unit_id,
                'type' => 'move_in',
                'date' => $request->move_in_date,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('admin.residents.index')
            ->with('success', 'Resident created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resident $resident)
    {
        $resident->load(['unit', 'bills', 'payments', 'complaints', 'vehicles', 'moveInOutLogs']);
        return view('admin.residents.show', compact('resident'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resident $resident)
    {
        $units = Unit::all();
        return view('admin.residents.edit', compact('resident', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:residents,email,' . $resident->id . '|max:255',
            'phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'type' => 'required|in:owner,tenant,family_member',
            'status' => 'required|in:active,inactive,moved_out',
            'date_of_birth' => 'nullable|date',
            'aadhar_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'move_in_date' => 'nullable|date',
            'move_out_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $this->residentRepository->update($resident->id, $validated);

        // Log move-out if date is provided and status changed
        if ($request->filled('move_out_date') && $resident->status !== 'moved_out') {
            MoveInOutLog::create([
                'resident_id' => $resident->id,
                'unit_id' => $resident->unit_id,
                'type' => 'move_out',
                'date' => $request->move_out_date,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('admin.residents.index')
            ->with('success', 'Resident updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resident $resident)
    {
        $this->residentRepository->delete($resident->id);

        return redirect()->route('admin.residents.index')
            ->with('success', 'Resident deleted successfully.');
    }
}
