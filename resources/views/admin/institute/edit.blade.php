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
                                <h3 class="card-title">{{ $data['card_title'] ?? 'Edit User' }}</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.institute.index') }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{ route('admin.institute.update',['id' => $data['row']->id]) }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row px-md-5">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Institution Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control" required
                                               value="{{$data['row']->name}}"
                                               placeholder="Institution Name">
                                    </div>
                                    {{-- @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control"
                                               value="{{$data['row']->email}}"
                                               required placeholder="Email">
                                    </div>
                                    {{-- @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="@if($data['role'] != 'User') col-md-4 offset-md-4 @else col-md-6 @endif">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select form-control">
                                        <option value="1" {{ $data['row']->status == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $data['row']->status == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                                @if($data['role'] == 'User')
                                    <div class="col-md-6">
                                        <label for="package" class="form-label">Current Package</label>
                                        <select name="package" id="package" class="form-select form-control">
                                            @foreach ($data['packages'] as $package)
                                                <option
                                                    value="{{$package->id}}" {{ $package->id == $data['row']->plan_id ? 'selected' : '' }}>{{$package->title}}</option>
                                            @endforeach
                                            {{-- <option value="1" {{ $data['row']->status == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $data['row']->status == '0' ? 'selected' : '' }}>Inactive</option> --}}
                                        </select>
                                    </div>
                                @endif
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-4 offset-md-4">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select form-control">
                                        <option value="1" {{ $data['row']->status == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $data['row']->status == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div> --}}
                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn text-light px-5" id="custom_btn">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">Edit Password</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{ route('admin.institute.update.password',$data['row']->id) }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row px-md-5">
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
