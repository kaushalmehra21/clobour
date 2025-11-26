<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComplaintCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ComplaintCategoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $query = $user->is_super_admin 
            ? ComplaintCategory::with('colony')
            : ComplaintCategory::where('colony_id', $user->current_colony_id);
            
        $categories = $query->orderBy('name')->paginate(15);
        return view('admin.complaint-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.complaint-categories.create');
    }

    public function store(Request $request)
    {
        $colonyId = auth()->user()->current_colony_id;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:complaint_categories,name,NULL,id,colony_id,' . $colonyId,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['colony_id'] = $colonyId;

        ComplaintCategory::create($validated);

        return redirect(panel_route('complaint-categories.index'))
            ->with('success', 'Complaint category created successfully.');
    }

    public function edit(ComplaintCategory $complaintCategory)
    {
        return view('admin.complaint-categories.edit', compact('complaintCategory'));
    }

    public function update(Request $request, ComplaintCategory $complaintCategory)
    {
        $colonyId = auth()->user()->current_colony_id;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:complaint_categories,name,' . $complaintCategory->id . ',id,colony_id,' . $colonyId,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $complaintCategory->update($validated);

        return redirect(panel_route('complaint-categories.index'))
            ->with('success', 'Complaint category updated successfully.');
    }

    public function destroy(ComplaintCategory $complaintCategory)
    {
        $complaintCategory->delete();
        return redirect(panel_route('complaint-categories.index'))
            ->with('success', 'Complaint category deleted successfully.');
    }
}
