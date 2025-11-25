<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Repositories\UnitRepository;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    protected $unitRepository;

    public function __construct(UnitRepository $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Super admin can see all colonies, colony users see only their colony
        $query = $user->is_super_admin 
            ? Unit::with('activeResident', 'colony')
            : Unit::with('activeResident')->where('colony_id', $user->current_colony_id);

        if ($request->filled('block')) {
            $query->where('block', $request->block);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('unit_number', 'like', '%' . $request->search . '%');
        }

        $units = $query->paginate(15);

        return view('admin.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_number' => 'required|string|unique:units,unit_number|max:255',
            'block' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'type' => 'required|in:flat,shop,office,parking',
            'area' => 'nullable|numeric|min:0',
            'status' => 'required|in:occupied,vacant,under_construction',
            'description' => 'nullable|string',
        ]);

        // Set colony_id for tenant isolation
        $validated['colony_id'] = auth()->user()->current_colony_id;
        
        $this->unitRepository->create($validated);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        $unit->load(['residents', 'bills', 'payments', 'vehicles']);
        return view('admin.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'unit_number' => 'required|string|unique:units,unit_number,' . $unit->id . '|max:255',
            'block' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'type' => 'required|in:flat,shop,office,parking',
            'area' => 'nullable|numeric|min:0',
            'status' => 'required|in:occupied,vacant,under_construction',
            'description' => 'nullable|string',
        ]);

        $this->unitRepository->update($unit->id, $validated);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $this->unitRepository->delete($unit->id);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit deleted successfully.');
    }

    /**
     * Get residents for a unit (AJAX)
     */
    public function getResidents(Unit $unit)
    {
        $residents = $unit->residents()->select('id', 'name')->get();
        return response()->json($residents);
    }
}
