<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Charge;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChargeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $query = $user->is_super_admin 
            ? Charge::with('colony')
            : Charge::where('colony_id', $user->current_colony_id);
            
        $charges = $query->orderBy('name')->paginate(15);
        return view('admin.charges.index', compact('charges'));
    }

    public function create()
    {
        return view('admin.charges.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,per_sqft,per_unit',
            'amount' => 'required|numeric|min:0',
            'per_sqft_rate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['colony_id'] = auth()->user()->current_colony_id;

        Charge::create($validated);

        return redirect(panel_route('charges.index'))
            ->with('success', 'Charge created successfully.');
    }

    public function edit(Charge $charge)
    {
        return view('admin.charges.edit', compact('charge'));
    }

    public function update(Request $request, Charge $charge)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,per_sqft,per_unit',
            'amount' => 'required|numeric|min:0',
            'per_sqft_rate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $charge->update($validated);

        return redirect(panel_route('charges.index'))
            ->with('success', 'Charge updated successfully.');
    }

    public function destroy(Charge $charge)
    {
        $charge->delete();
        return redirect(panel_route('charges.index'))
            ->with('success', 'Charge deleted successfully.');
    }
}
