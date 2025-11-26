<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AmenityController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $query = $user->is_super_admin 
            ? Amenity::with('colony')
            : Amenity::where('colony_id', $user->current_colony_id);
            
        $amenities = $query->orderBy('name')->paginate(15);
        return view('admin.amenities.index', compact('amenities'));
    }

    public function create()
    {
        return view('admin.amenities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'booking_fee' => 'required|numeric|min:0',
            'max_advance_booking_days' => 'required|integer|min:1',
            'min_advance_booking_hours' => 'required|integer|min:0',
            'requires_approval' => 'boolean',
            'is_active' => 'boolean',
            'available_days' => 'nullable|array',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'max_booking_duration_hours' => 'nullable|integer|min:1',
            'terms_and_conditions' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['requires_approval'] = $request->has('requires_approval');
        $validated['is_active'] = $request->has('is_active');
        $validated['colony_id'] = auth()->user()->current_colony_id;

        Amenity::create($validated);

        return redirect(panel_route('amenities.index'))
            ->with('success', 'Amenity created successfully.');
    }

    public function show(Amenity $amenity)
    {
        $amenity->load(['bookings.resident', 'bookings.unit']);
        return view('admin.amenities.show', compact('amenity'));
    }

    public function edit(Amenity $amenity)
    {
        return view('admin.amenities.edit', compact('amenity'));
    }

    public function update(Request $request, Amenity $amenity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'booking_fee' => 'required|numeric|min:0',
            'max_advance_booking_days' => 'required|integer|min:1',
            'min_advance_booking_hours' => 'required|integer|min:0',
            'requires_approval' => 'boolean',
            'is_active' => 'boolean',
            'available_days' => 'nullable|array',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'max_booking_duration_hours' => 'nullable|integer|min:1',
            'terms_and_conditions' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['requires_approval'] = $request->has('requires_approval');
        $validated['is_active'] = $request->has('is_active');

        $amenity->update($validated);

        return redirect(panel_route('amenities.index'))
            ->with('success', 'Amenity updated successfully.');
    }

    public function destroy(Amenity $amenity)
    {
        $amenity->delete();
        return redirect(panel_route('amenities.index'))
            ->with('success', 'Amenity deleted successfully.');
    }
}
