@extends('admin.layouts.master')
@section('admin-user', 'active')
@section('admin_management', 'active menu-open')
@section('title')
    {{ $data['title'] ?? '' }}
@endsection
@push('style')
    <style>
        input, select, textarea {
            border-radius: 10px !important;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row mt-5">
                    <div class="col-md-6 offset-md-3">
                        <div class="card card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle"
                                    src="{{ getProfile($data['row']->image) }}" alt="{{ $data['row']->name }}"
                                         style="width:100px; height:100px; display:block;" >
                                </div>
                                <h3 class="profile-username text-center">{{ $data['row']->name }} </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6 offset-md-3">
                        <div class="card w-100">
                            <ul class="list-group list-group-flush">

                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6>Email</h6>
                                    <span class="text-secondary">{{ $data['row']->email ?? 'N/A' }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6>Role</h6>
                                    <span class="text-secondary">{{ $data['row']->role->name ?? 'N/A' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ route('admin.admins.edit', $data['row']->id) }}"
                       class="btn btn-primary" style="border: 1px solid #F1F1F1">
                        Edit
                    </a>
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-info"
                       style="border: 1px solid #F1F1F1">Back
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

