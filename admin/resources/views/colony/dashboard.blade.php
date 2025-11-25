@extends('layouts.admin.master')

@section('title', 'Colony Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>{{ $colony->name }} - Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('colony.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Overview</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Total Units</h6>
                    <h3>{{ $stats['total_units'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Total Residents</h6>
                    <h3>{{ $stats['total_residents'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Pending Bills</h6>
                    <h3>{{ $stats['pending_bills'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6>Open Complaints</h6>
                    <h3>{{ $stats['open_complaints'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

