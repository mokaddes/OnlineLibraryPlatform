@extends('admin.layouts.master')
@section('admin-user', 'active')
@section('admin_management', 'active menu-open')
@section('title')
    {{ $data['title'] ?? '' }}
@endsection
@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <style>
        .form-control {
            font-size: 14px !important;
            height: auto !important;
            padding: 12px 10px;
            line-height: 13px;
        }

        .iti {
            display: block !important;
        }

        input, select, textarea {
            border-radius: 10px !important;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-3">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">{{ $data['card_title'] ?? 'Edit User' }}</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.admins.index') }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{route('admin.admins.update',$data['row']->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row px-md-5">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                            required value="{{$data['row']->name}}" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                            value="{{$data['row']->email}}" required placeholder="Email" id="email">
                                    </div>
                                </div>
                                <div class="col-md-6 offset-md-3">
                                    <label for="role" class="form-label">Role </label>
                                    <select name="role" id="role" class="form-select form-control @error('role') is-invalid @enderror">
                                        @foreach ($data['role'] as $role)
                                            <option value="{{$role->id}}" {{ $role->id == $data['row']->role_id ? 'selected' : '' }}>{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn text-light px-5" id="custom_btn">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push("script")
    @include('frontend.phone_number_script')
@endpush
