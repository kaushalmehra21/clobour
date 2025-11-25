@extends('layouts.admin.master')

@section('title', 'Reports')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Reports</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Financial Reports</h5>
                </div>
                <div class="card-body">
                    <p>View financial reports including maintenance collection, expenses, and outstanding dues.</p>
                    <a href="{{ panel_route('reports.financial') }}" class="btn btn-primary">
                        <i data-feather="dollar-sign" class="me-2"></i>View Financial Reports
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Operational Reports</h5>
                </div>
                <div class="card-body">
                    <p>View operational reports including complaints, visitor logs, and facility usage.</p>
                    <a href="{{ panel_route('reports.operational') }}" class="btn btn-primary">
                        <i data-feather="bar-chart-2" class="me-2"></i>View Operational Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

