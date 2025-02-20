@extends('frontend.layouts.app')
@section('title')
    {{ $title ?? 'Registration' }}
@endsection

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <style>
        .iti {
            display: block !important;
        }
        .invalid {
            width: 100%;
            margin-top: 0.25rem;
            font-size: .875em;
            color: #dc3545;
        }

        .showPassword {
            border: 1px solid;
        }
    </style>
@endpush

@section('content')
    <!-- ======================= breadcrumb start  ============================ -->
    <div class="breadcrumb-sec pt-4 pb-4">
        <div class="container">
            <div class="breadcrumb-item text-center">
                <h4>Create a new account</h4>
                <img src="{{ asset('assets/frontend/images/breadcrumb_shape.svg') }}" alt="">
            </div>
        </div>
    </div>
    <!-- ======================= breadcrumb end  ============================ -->

    <!-- ======================= sign in start  ============================ -->
    <div class="signin-sec pb-5 mb-5">
        <div class="container">
            <div class="row">
                {{-- <div class="register_with text-center mb-3">
                    <a href="{{route('user.registration')}}" class="active me-sm-3">User</a>
                    <a href="{{route('author.registration')}}" class="me-sm-3">Author</a>
                    <a href="{{route('institution.registration')}}">Institution</a>
                </div> --}}

                <div class="signin_form">
                    <form method="POST" class="p-4" action="{{ route('register') }}" style="max-width:32rem;">
                        @csrf
                        <input type="hidden" name="role_id" value="1">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="name" class="form-label">First Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" id="name" autofocus
                                    class="custom_form form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter your first name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" id="last_name"
                                    class="custom_form form-control @error('last_name') is-invalid @enderror"
                                    placeholder="Enter your last name">
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" id="email"
                                class="custom_form form-control @error('email') is-invalid @enderror" placeholder="Enter your email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" id="phone"
                                class="custom_form form-control @error('phone') is-invalid @enderror"
                                placeholder="Enter your phone number">
                            @error('phone')
                                <span class="invalid">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input type="hidden" id="dialCode" name="dial_code">
                            <input type="hidden" id="country_name" name="country">
                            <input type="hidden" id="country_code" name="country_code">


                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password-field"
                                    class="custom_form form-control border-end-0 @error('password') is-invalid @enderror"
                                    placeholder="Enter your password">
                                <span toggle="#password-field"
                                    style="@error('password') border-top-right-radius: 8px;border-bottom-right-radius: 8px; @enderror"
                                    class="fa fa-fw fa-eye field-icon confirm_pass showPassword input-group-text @error('password') border-danger @enderror"></span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="custom_form form-control border-end-0 @error('password_confirmation') is-invalid @enderror"
                                    placeholder="confirm your password">
                                <span toggle="#password_confirmation"
                                    style="@error('password_confirmation') border-top-right-radius: 8px;border-bottom-right-radius: 8px; @enderror"
                                    class="fa fa-fw fa-eye field-icon confirm_pass input-group-text @error('password_confirmation') border-danger @enderror"></span>
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn_primary w-100">Sign Up</button>
                        </div>
                        <p class="have_account mb-5 text-center">
                            Already have an account? <a href="{{ url('user/login') }}">Sign In</a>
                        </p>
                        <div class="shape_divider mb-4 text-center">
                            <span>Or continue with:</span>
                        </div>
                        <div class="social_login text-center pb-4">
                            {{-- <a href="{{ url('auth/facebook/login') }}" style="background-color: #584AF8;">
                                <img src="{{asset('assets/frontend/images/social/facebook.png')}}" alt="Facebook">
                                Facebook
                            </a> --}}
                            <a href="{{ url('google/login') }}" style="background-color: #FF7D7D;">
                                <img src="{{asset('assets/frontend/images/social/google.png')}}" alt="Google">
                                Google
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= sign in end  ============================ -->
@endsection

@push('script')
    @include('frontend.phone_number_script')
@endpush
