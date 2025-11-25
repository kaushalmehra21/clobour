@extends('layouts.admin.master')

@section('title', 'Edit Admin')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Edit Admin</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('admins.index') }}">Admins</a></li>
                    <li class="breadcrumb-item active">{{ $admin->name }}</li>
                </ol>
            </div>
        </div>
    </div>
    <form action="{{ panel_route('admins.update', $admin) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.admins.form', ['submitLabel' => 'Update Admin', 'admin' => $admin])
    </form>
</div>
@endsection

