@extends('layouts.admin.master')

@section('title', 'Notice Details')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Notice Details</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('notices.index') }}">Notices</a></li>
                    <li class="breadcrumb-item active">{{ $notice->title }}</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('notices.edit', $notice) }}" class="btn btn-primary">
                    <i data-feather="edit" class="me-2"></i>Edit Notice
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{ $notice->title }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge badge-info me-2">{{ ucfirst($notice->type) }}</span>
                        <span class="badge badge-{{ $notice->priority == 'high' ? 'danger' : ($notice->priority == 'medium' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($notice->priority) }} Priority
                        </span>
                        <span class="badge badge-{{ $notice->is_published ? 'success' : 'secondary' }} ms-2">
                            {{ $notice->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    <div class="content">
                        {!! nl2br(e($notice->content)) !!}
                    </div>
                    @if($notice->attachments)
                        <hr>
                        <h6>Attachments</h6>
                        @foreach($notice->attachments as $attachment)
                            <a href="{{ Storage::url($attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                <i data-feather="file" class="me-1"></i>View Attachment
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Notice Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th>Publish Date:</th>
                            <td>{{ $notice->publish_date->format('M d, Y') }}</td>
                        </tr>
                        @if($notice->expiry_date)
                        <tr>
                            <th>Expiry Date:</th>
                            <td>{{ $notice->expiry_date->format('M d, Y') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Created By:</th>
                            <td>{{ $notice->createdBy->name }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $notice->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

