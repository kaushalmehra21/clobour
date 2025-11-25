@extends('layouts.admin.master')

@section('title', 'Complaint Details')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Complaint Details</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('complaints.index') }}">Complaints</a></li>
                    <li class="breadcrumb-item active">{{ $complaint->ticket_number }}</li>
                </ol>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{ $complaint->subject }}</h5>
                </div>
                <div class="card-body">
                    <p><strong>Description:</strong></p>
                    <p>{{ $complaint->description }}</p>
                    <hr>
                    <h6>Comments</h6>
                    @foreach($complaint->comments as $comment)
                        <div class="mb-3 p-3 border rounded">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $comment->user->name }}</strong>
                                <small class="text-muted">{{ $comment->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                            <p class="mb-0 mt-2">{{ $comment->comment }}</p>
                            @if($comment->is_internal)
                                <span class="badge badge-warning">Internal</span>
                            @endif
                        </div>
                    @endforeach
                    <form action="{{ panel_route('complaints.comment', $complaint) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <textarea name="comment" class="form-control" rows="3" placeholder="Add a comment..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_internal" id="is_internal">
                                <label class="form-check-label" for="is_internal">Internal Comment (not visible to resident)</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Comment</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Complaint Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ panel_route('complaints.update', $complaint) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="open" {{ $complaint->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $complaint->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="rejected" {{ $complaint->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Priority <span class="text-danger">*</span></label>
                            <select name="priority" class="form-control" required>
                                <option value="low" {{ $complaint->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $complaint->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $complaint->priority == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ $complaint->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assign To</label>
                            <select name="assigned_to" class="form-control">
                                <option value="">Unassigned</option>
                                @foreach($staff as $s)
                                    <option value="{{ $s->id }}" {{ $complaint->assigned_to == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Resolution Notes</label>
                            <textarea name="resolution_notes" class="form-control" rows="3">{{ old('resolution_notes', $complaint->resolution_notes) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Complaint</button>
                    </form>
                    <hr>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th>Ticket #:</th>
                            <td>{{ $complaint->ticket_number }}</td>
                        </tr>
                        <tr>
                            <th>Unit:</th>
                            <td>{{ $complaint->unit->unit_number }}</td>
                        </tr>
                        <tr>
                            <th>Resident:</th>
                            <td>{{ $complaint->resident?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Category:</th>
                            <td>{{ $complaint->category?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $complaint->created_at->format('M d, Y') }}</td>
                        </tr>
                        @if($complaint->resolved_at)
                        <tr>
                            <th>Resolved:</th>
                            <td>{{ $complaint->resolved_at->format('M d, Y') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

