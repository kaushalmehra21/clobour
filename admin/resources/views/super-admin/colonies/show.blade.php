@extends('layouts.admin.master')

@section('title', 'Colony Details')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>{{ $colony->name }}</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.colonies.index') }}">Colonies</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ route('super-admin.colonies.edit', $colony) }}" class="btn btn-primary">
                    <i data-feather="edit" class="me-2"></i>Edit
                </a>
                <form action="{{ route('super-admin.colonies.impersonate', $colony) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-info">
                        <i data-feather="user" class="me-2"></i>Impersonate
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Colony Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Name:</strong></div>
                        <div class="col-md-8">{{ $colony->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Code:</strong></div>
                        <div class="col-md-8">{{ $colony->code }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Address:</strong></div>
                        <div class="col-md-8">{{ $colony->address ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>City:</strong></div>
                        <div class="col-md-8">{{ $colony->city ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>State:</strong></div>
                        <div class="col-md-8">{{ $colony->state ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Pincode:</strong></div>
                        <div class="col-md-8">{{ $colony->pincode ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Phone:</strong></div>
                        <div class="col-md-8">{{ $colony->phone ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Email:</strong></div>
                        <div class="col-md-8">{{ $colony->email ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Status:</strong></div>
                        <div class="col-md-8">
                            <span class="badge badge-{{ $colony->status == 'active' ? 'success' : ($colony->status == 'suspended' ? 'danger' : 'warning') }}">
                                {{ ucfirst($colony->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Subscription Plan:</strong></div>
                        <div class="col-md-8">{{ $colony->plan?->name ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Expires At:</strong></div>
                        <div class="col-md-8">{{ $colony->expires_at?->format('M d, Y') ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Max Units:</strong></div>
                        <div class="col-md-8">{{ $colony->max_units }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Max Residents:</strong></div>
                        <div class="col-md-8">{{ $colony->max_residents }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Total Units:</strong> {{ $colony->units()->count() }}
                    </div>
                    <div class="mb-3">
                        <strong>Total Residents:</strong> {{ $colony->residents()->count() }}
                    </div>
                    <div class="mb-3">
                        <strong>Total Users:</strong> {{ $colony->users()->count() }}
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Actions</h5>
                </div>
                <div class="card-body">
                    @if($colony->status == 'active')
                        <form action="{{ route('super-admin.colonies.suspend', $colony) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm w-100">Suspend Colony</button>
                        </form>
                    @else
                        <form action="{{ route('super-admin.colonies.activate', $colony) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm w-100">Activate Colony</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

