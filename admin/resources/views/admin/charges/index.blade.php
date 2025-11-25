@extends('layouts.admin.master')

@section('title', 'Charges Management')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Charges Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Charges</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('charges.create') }}" class="btn btn-primary">
                    <i data-feather="plus" class="me-2"></i>Add Charge
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
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Amount/Rate</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($charges as $charge)
                        <tr>
                            <td class="fw-semibold">{{ $charge->name }}</td>
                            <td><span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $charge->type)) }}</span></td>
                            <td>
                                @if($charge->type == 'per_sqft')
                                    ₹{{ number_format($charge->per_sqft_rate, 2) }}/sqft
                                @else
                                    ₹{{ number_format($charge->amount, 2) }}
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $charge->is_active ? 'success' : 'secondary' }}">
                                    {{ $charge->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end table-action-cell">
                                <div class="table-actions">
                                    <a href="{{ panel_route('charges.edit', $charge) }}" class="btn btn-sm btn-outline-secondary"
                                        title="Edit {{ $charge->name }}">
                                        <i data-feather="edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ panel_route('charges.destroy', $charge) }}" method="POST" class="mb-0"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete {{ $charge->name }}">
                                            <i data-feather="trash-2"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No charges found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $charges->links() }}
        </div>
    </div>
</div>
@endsection

