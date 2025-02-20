@extends('admin.layouts.master')
@section('customer', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection

@push('style')
<style>
    td {
        width: 0;
    }
</style>
@endpush
@php
    $user = $data['user'];
@endphp
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $data['title'] ?? '' }}</h1>
                </div>
                <div class="col-sm-6">
                   <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $data['title'] ?? '' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h3 class="card-title"> View Customer</h3>
                                </div>
                                <div class="col-6">
                                    <div class="float-right">
                                        <a href="{{ route('admin.customer.index') }}" class="btn btn-primary">Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-4">
                            <table class="table">
                                <tr>
                                    <td>Featured Image</td>
                                    <td><img src="{{ asset('assets/images/customer/'.$user->image) }}" width="100" alt=""></td>
                                </tr>
                                <tr>
                                    <td>Customer Name</td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Customer Role</td>
                                    <td>{{ $user->role->name }}</td>
                                </tr>
                                <tr>
                                    <td>Customer Email</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td>Customer Phone</td>
                                    <td>{{ $user->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Publihed Status</td>
                                    <td>
                                        @if($user->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                        <span class="badge badge-danger">Active</span>
                                        @endif

                                    </td>
                                </tr>
                                <tr>
                                    <td>Customer Address</td>
                                    <td>{{ $user->address }}</td>
                                </tr>
                                
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
