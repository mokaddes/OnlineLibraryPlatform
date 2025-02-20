@extends('admin.layouts.user')
@section('settings', 'active')
@section('title') {{ $title ?? '' }} @endsection
@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <style>
        input,
        select,
        textarea {
            border-radius: 10px !important;
        }

        .iti {
            display: block !important;
        }

        .custom_form form-control {
            border-radius: 8px;
            background: #FFF;
            padding: 10px 10px;
            outline: none !important;
            box-shadow: none !important;
            border: 1px solid #CCC;
            width: 100%;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-3 p-3">
        <div class="content">
            <div class="container-fluid">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title">Update Profile</h4>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{route('user.profile.update', $user->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 form-group mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" name="image" id="image" class="form-control"
                                        placeholder="Image">
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ $user->name }}" id="name" autofocus
                                        required class="custom_form form-control @error('name') is-invalid @enderror"
                                        placeholder="Enter your first name">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" name="last_name" value="{{ $user->last_name }}" id="last_name"
                                        class="custom_form form-control @error('last_name') is-invalid @enderror"
                                        placeholder="Enter your last name">
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="@if(auth()->user()->role_id == 3) col-md-12 @else col-md-6 @endif form-group mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="text" name="email" value="{{ $user->email }}" id="email"
                                        required class="custom_form form-control @error('email') is-invalid @enderror"
                                        placeholder="Enter your last name">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                @if(auth()->user()->role_id != 3)
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" name="phone" value="{{ $user->dial_code . $user->phone }}"
                                                id="phone" class="custom_form form-control @error('phone') is-invalid @enderror"
                                                placeholder="Enter your phone number">
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <input type="hidden" id="dialCode" name="dial_code">
                                            <input type="hidden" id="country_name" name="country">
                                        </div>
                                    </div>
                                @endif
                            </div>
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
                        <h4 class="card-title">Password</h4>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{route('user.password.update', $user->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="old_pass" class="form-label">Current Password</label>
                                        <input type="password" name="old_pass" id="old_pass" class="custom_form form-control
                                        @error('old_pass') is-invalid @enderror" required
                                            placeholder="Current Password">
                                        @error('old_pass')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" id="password" class="custom_form form-control
                                        @error('password') is-invalid @enderror" required
                                            placeholder="New Password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">Re enter New Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="custom_form form-control @error('password_confirmation') is-invalid @enderror" required
                                            placeholder="Re enter New Password">
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
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
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title">Account Delete</h4>
                    </div>
                    <div class="card-body mt-2">
                        <form action="{{route('user.account.delete', $user->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-md-11">
                                    <p class="text-danger"> <strong>If you DELETE this account all data will be lost permanetly from our website!</strong> </p>
                                    <div class="form-group">
                                        <label for="delete" class="form-label">Enter "Delete" to confirm <span class="text-danger">*</span></label>
                                        <input type="text" name="delete" id="delete"
                                        class="form-control @error('delete') is-invalid @enderror" required>
                                        {{-- @error('delete')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror --}}
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-danger text-light p-2 mt-2" onclick="return confirm('{{ __('Are you sure want to delete your account') }}')">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    @include('frontend.phone_number_script')
@endpush
