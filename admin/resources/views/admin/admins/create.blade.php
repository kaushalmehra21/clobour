@extends('layouts.admin.master')

@section('title', 'Add Admin')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Add Admin</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('admins.index') }}">Admins</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
    <form action="{{ panel_route('admins.store') }}" method="POST">
        @csrf
        @include('admin.admins.form', ['submitLabel' => 'Create Admin'])
    </form>
</div>
@endsection

