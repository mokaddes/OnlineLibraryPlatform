@extends('admin.layouts.master')
@section('admin-institute', 'active')
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
    <div class="content-wrapper mt-3">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">Add New</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.institute.index') }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{ route('admin.institute.store') }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row px-5">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Institution Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control" required
                                               placeholder="Institution Name" value="{{ old('name') }}">
                                    </div>
                                    {{-- @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control" required
                                               placeholder="Email" value="{{ old('email') }}">
                                    </div>
                                    {{-- @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-label">Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="password" id="password" class="form-control"
                                               required placeholder="Password">
                                    </div>
                                    {{-- @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">Confirm Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                               class="form-control" required placeholder="Confirm Password">
                                    </div>
                                    {{-- @error('cpassword')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn text-light px-5" id="custom_btn">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
