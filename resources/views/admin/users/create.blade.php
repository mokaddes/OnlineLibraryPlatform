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
            padding: 12px 10px ;
            line-height: 13px;
        }
        .iti {
            display: block !important;
        }

        .invalid {
            width: 100%;
            margin-top: 0.25rem;
            font-size: .875em;
            color: #dc3545;
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
                                <h3 class="card-title">Add New</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{ route('admin.user.store',['role' => $data['role'] ]) }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row px-5">
                                @if($data['role'] == 'Author' || $data['role'] == 'Reader')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">First Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control" required
                                                   placeholder="First Name" value="{{ old('name') }}">
                                        </div>
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name" class="form-label">Last Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                   placeholder="Last Name" value="{{ old('last_name') }}">
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
                                                   placeholder="Institution Name" value="{{ old('name') }}">
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
                                        <input type="email" name="email" id="email" class="form-control" required
                                               placeholder="Email" value="{{ old('email') }}">
                                    </div>
                                    {{-- @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                @if($data['role'] == 'Author'  || $data['role'] == 'Reader')
                                    <div class="col-md-6">
                                        <div class="form-group" id="adminUser">
                                            <label for="phone" class="form-label">Phone Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" name="phone"
                                                   value="{{ old('phone') }}"
                                                   id="phone"
                                                   class="custom_form form-control @error('phone') is-invalid @enderror"
                                                   placeholder="Enter your phone number">
                                            @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <input type="hidden" id="dialCode"
                                                   name="dial_code">
                                            <input type="hidden" id="country_name"
                                                   name="country">
                                            <input type="hidden" id="country_code" name="country_code">
                                        </div>
                                    </div>
                                @endif
                                    @if($data['role'] == 'Author')
                                        <div class="col-md-6 ">
                                            <label for="is_buy_book" class="form-label">Book buy status</label>
                                            <select name="is_buy_book" id="is_buy_book" class="form-select form-control">
                                                <option value="0" {{ old('is_buy_book') == '0' ? 'selected' : '' }}>
                                                    Disable
                                                </option>
                                                <option value="1" {{ old('is_buy_book') == '1' ? 'selected' : '' }}>
                                                    Enable
                                                </option>
                                            </select>
                                        </div>
                                    @endif
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
                                @if($data['role'] == 'Reader')
                                    <div class="col-md-6 offset-md-3">
                                        <label for="package" class="form-label">Subscription Package</label>
                                        <select name="package" id="package" class="form-select form-control">
                                            @foreach ($data['packages'] as $package)
                                                <option
                                                    value="{{$package->id}}" >{{$package->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
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
@push("script")
    @include('frontend.phone_number_script')
@endpush

