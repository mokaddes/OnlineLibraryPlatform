@extends('admin.layouts.master')
@section('admin-permissions', 'active')
@section('admin_management', 'active menu-open')
@section('title') Admin| Permissions Create @endsection

@push('style')
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="m-0">{{ __('Admin permissions create') }}
                                    <span class="float-right">
                                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-sm btn-light"
                                        style="border: 1px solid #F1F1F1">back</a>
                                    </span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.permissions.store') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" placeholder="Name" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="group_name" class="form-label">Group Name</label>
                                            <input name="group_name" type="text" class="form-control"
                                                value="{{ old('group_name') }}" placeholder="Group Name">
                                        </div>
                                    </div>
                                    <div class="row mt-4 d-flex justify-content-center">
                                        <button type="submit" class="btn text-light" id="custom_btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
