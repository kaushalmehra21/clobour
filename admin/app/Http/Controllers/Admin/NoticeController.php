<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->is_super_admin 
            ? Notice::with('createdBy', 'colony')
            : Notice::with('createdBy')->where('colony_id', $user->current_colony_id);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published);
        }

        $notices = $query->orderBy('publish_date', 'desc')->paginate(15);
        return view('admin.notices.index', compact('notices'));
    }

    public function create()
    {
        $user = auth()->user();
        $units = $user->is_super_admin 
            ? Unit::all()
            : Unit::where('colony_id', $user->current_colony_id)->get();
        return view('admin.notices.create', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,maintenance,meeting,emergency,event',
            'priority' => 'required|in:low,medium,high',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:publish_date',
            'is_published' => 'boolean',
            'send_notification' => 'boolean',
            'target_audience' => 'nullable|array',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $validated['send_notification'] = $request->has('send_notification');
        $validated['created_by'] = auth()->id();
        $validated['colony_id'] = auth()->user()->current_colony_id;

        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('notices/attachments', 'public');
            }
            $validated['attachments'] = $attachments;
        }

        Notice::create($validated);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice created successfully.');
    }

    public function show(Notice $notice)
    {
        $notice->load('createdBy');
        return view('admin.notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        $units = Unit::all();
        return view('admin.notices.edit', compact('notice', 'units'));
    }

    public function update(Request $request, Notice $notice)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,maintenance,meeting,emergency,event',
            'priority' => 'required|in:low,medium,high',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:publish_date',
            'is_published' => 'boolean',
            'send_notification' => 'boolean',
            'target_audience' => 'nullable|array',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $validated['send_notification'] = $request->has('send_notification');

        if ($request->hasFile('attachments')) {
            // Delete old attachments
            if ($notice->attachments) {
                foreach ($notice->attachments as $file) {
                    Storage::disk('public')->delete($file);
                }
            }
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('notices/attachments', 'public');
            }
            $validated['attachments'] = $attachments;
        }

        $notice->update($validated);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        if ($notice->attachments) {
            foreach ($notice->attachments as $file) {
                Storage::disk('public')->delete($file);
            }
        }
        $notice->delete();

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice deleted successfully.');
    }
}
