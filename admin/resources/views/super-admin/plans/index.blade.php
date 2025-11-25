@extends('layouts.admin.master')

@section('title', 'Subscription Plans')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Subscription Plans</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Plans</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ route('super-admin.plans.create') }}" class="btn btn-primary">
                    <i data-feather="plus" class="me-2"></i>Create Plan
                </a>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="row">
        @forelse($plans as $plan)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $plan->name }}</h5>
                        <span class="badge badge-{{ $plan->is_active ? 'success' : 'secondary' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <h3 class="text-primary">₹{{ number_format($plan->price, 2) }}</h3>
                        <p class="text-muted">/{{ $plan->billing_cycle }}</p>
                        <p>{{ $plan->description }}</p>
                        <ul class="list-unstyled">
                            <li><i data-feather="check" class="me-2"></i>Max Units: {{ $plan->max_units }}</li>
                            <li><i data-feather="check" class="me-2"></i>Max Residents: {{ $plan->max_residents }}</li>
                            <li><i data-feather="check" class="me-2"></i>Max Staff: {{ $plan->max_staff }}</li>
                            <li><i data-feather="check" class="me-2"></i>Trial Days: {{ $plan->trial_days }}</li>
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('super-admin.plans.edit', $plan) }}" class="btn btn-sm btn-outline-primary">
                                <i data-feather="edit"></i> Edit
                            </a>
                            <form action="{{ route('super-admin.plans.destroy', $plan) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i data-feather="trash-2"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center text-muted">
                        <p>No subscription plans found.</p>
                        <a href="{{ route('super-admin.plans.create') }}" class="btn btn-primary">Create First Plan</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

