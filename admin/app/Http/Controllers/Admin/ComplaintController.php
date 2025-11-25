<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\ComplaintComment;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->is_super_admin 
            ? Complaint::with(['resident', 'unit', 'category', 'assignedTo', 'colony'])
            : Complaint::with(['resident', 'unit', 'category', 'assignedTo'])->where('colony_id', $user->current_colony_id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        $complaints = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $categoryQuery = ComplaintCategory::where('is_active', true);
        if (!$user->is_super_admin) {
            $categoryQuery->where('colony_id', $user->current_colony_id);
        }
        $categories = $categoryQuery->get();
        
        $staff = User::whereHas('colonies', function ($q) use ($user) {
            $q->where('colonies.id', $user->current_colony_id);
        })->whereHas('roles', function ($q) {
            $q->whereIn('slug', ['admin', 'staff', 'colony_admin']);
        })->get();

        return view('admin.complaints.index', compact('complaints', 'categories', 'staff'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['resident', 'unit', 'category', 'assignedTo', 'comments.user']);
        $staff = User::whereHas('roles', function ($q) {
            $q->whereIn('slug', ['admin', 'staff']);
        })->get();
        return view('admin.complaints.show', compact('complaint', 'staff'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed,rejected',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'resolution_notes' => 'nullable|string',
        ]);

        if ($validated['status'] == 'resolved' && $complaint->status != 'resolved') {
            $validated['resolved_at'] = now();
        }

        $complaint->update($validated);

        return redirect()->route('admin.complaints.show', $complaint)
            ->with('success', 'Complaint updated successfully.');
    }

    public function addComment(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        ComplaintComment::create([
            'complaint_id' => $complaint->id,
            'user_id' => auth()->id(),
            'comment' => $validated['comment'],
            'is_internal' => $request->has('is_internal'),
        ]);

        return redirect()->route('admin.complaints.show', $complaint)
            ->with('success', 'Comment added successfully.');
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint deleted successfully.');
    }
}
