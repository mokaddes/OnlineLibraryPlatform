@extends('admin.layouts.master')
@section('user', 'active')
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
                                <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form
                            action="{{ route('admin.user.update',['id' => $data['row']->id, 'role' => $data['role'] ]) }}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row px-md-5">
                                @if($data['role'] == 'Author' or $data['role'] == 'User')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">First Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control" required
                                                   value="{{$data['row']->name}}"
                                                   placeholder="First Name">
                                        </div>
                                        {{-- @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror --}}
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                   value="{{$data['row']->last_name}}"
                                                   placeholder="Last Name">
                                        </div>
                                        {{-- @error('last_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror --}}
                                    </div>
                                @endif

                                @if($data['role'] == 'Institution')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Institution Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control" required
                                                   value="{{$data['row']->name}}"
                                                   placeholder="Institution Name">
                                        </div>
                                        {{-- @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror --}}
                                    </div>
                                @endif
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
                                @if($data['role'] == 'Author' || $data['role'] == 'User')
                                    <div class="col-md-6">
                                        <div class="form-group" id="adminUser">
                                            <label for="phone" class="form-label">Phone Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" name="phone"
                                                   value="{{ $data['row']->dial_code. $data['row']->phone }}"
                                                   id="phone"
                                                   class="custom_form form-control @error('phone') is-invalid @enderror"
                                                   placeholder="Enter your phone number">
                                            @error('phone')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                            <input type="hidden" id="dialCode"
                                                   name="dial_code">
                                            <input type="hidden" id="country_name"
                                                   name="country">
                                            <input type="hidden" id="country_code" name="country_code">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="age" class="form-label">Age</label>
                                            <input type="number" name="age" id="age" class="form-control"
                                                   value="{{$data['row']->age}}" placeholder="Age">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gender" class="form-label">Gender </label>
                                            <select name="gender" id="gender" class="form-select form-control">
                                                <option
                                                    value="Male" {{ $data['row']->gender == 'Male' ? 'selected' : '' }}>
                                                    Male
                                                </option>
                                                <option
                                                    value="Female" {{ $data['row']->gender == 'Female' ? 'selected' : '' }}>
                                                    Female
                                                </option>
                                                <option
                                                    value="Other" {{ $data['row']->gender == 'Other' ? 'selected' : '' }}>
                                                    Other
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if($data['role'] == 'Author')
                                    <div class="col-md-6 ">
                                        <label for="is_buy_book" class="form-label">Book buy status</label>
                                        <select name="is_buy_book" id="is_buy_book" class="form-select form-control">
                                            <option value="0" {{ $data['row']->is_buy_book == '0' ? 'selected' : '' }}>
                                                Disable
                                            </option>
                                            <option value="1" {{ $data['row']->is_buy_book == '1' ? 'selected' : '' }}>
                                                Enable
                                            </option>
                                        </select>
                                    </div>
                                @endif
                                <div class="col-md-6 ">
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
                        <form action="{{ route('admin.user.update.password',$data['row']->id) }}" method="post"
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
@push("script")
    @include('frontend.phone_number_script')
@endpush
