<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Resident;
use App\Models\Unit;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->is_super_admin 
            ? Vehicle::with(['resident', 'unit', 'colony'])
            : Vehicle::with(['resident', 'unit'])->where('colony_id', $user->current_colony_id);

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('vehicle_number', 'like', '%' . $request->search . '%');
        }

        $vehicles = $query->orderBy('vehicle_number')->paginate(15);
        $units = $user->is_super_admin 
            ? Unit::all()
            : Unit::where('colony_id', $user->current_colony_id)->get();

        return view('admin.vehicles.index', compact('vehicles', 'units'));
    }

    public function create()
    {
        $user = auth()->user();
        $units = $user->is_super_admin 
            ? Unit::all()
            : Unit::where('colony_id', $user->current_colony_id)->get();
        return view('admin.vehicles.create', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'unit_id' => 'required|exists:units,id',
            'vehicle_number' => 'required|string|unique:vehicles,vehicle_number|max:50',
            'vehicle_type' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:100',
            'parking_type' => 'nullable|in:covered,open,basement',
            'parking_slot_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        // Get colony_id from unit
        $unit = Unit::find($validated['unit_id']);
        $validated['colony_id'] = $unit->colony_id;

        Vehicle::create($validated);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle registered successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['resident', 'unit']);
        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $user = auth()->user();
        $units = $user->is_super_admin 
            ? Unit::all()
            : Unit::where('colony_id', $user->current_colony_id)->get();
        return view('admin.vehicles.edit', compact('vehicle', 'units'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'unit_id' => 'required|exists:units,id',
            'vehicle_number' => 'required|string|unique:vehicles,vehicle_number,' . $vehicle->id . '|max:50',
            'vehicle_type' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:100',
            'parking_type' => 'nullable|in:covered,open,basement',
            'parking_slot_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $vehicle->update($validated);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}
