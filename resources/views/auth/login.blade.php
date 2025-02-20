@extends('frontend.layouts.app')
@section('title')
    {{ $title ?? 'Login' }}
@endsection

@push('style')
    <style>
        .forgotPassword {
            position: relative;
            margin-left: 260px;
            color: #800080
        }


        .register_with input[type="radio"] {
            display: none; /* Hide the default radio button */
        }

        .register_with label {
            display: inline-block;
            padding: 10px 28px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            max-width: 136px;
            width: 121px;
            margin-bottom: 8px;
            cursor: pointer;
            border-radius: 12px;
            border: 2px solid #8000801f;
            color: #323232;
            transition: background-color 0.3s ease;
        }

        .register_with input[type="radio"]:checked + label {
            background: #800080;
            color: #fff;
            border: transparent;
        }
    </style>

@endpush

@section('content')
    <!-- ======================= breadcrumb start  ============================ -->
    <div class="breadcrumb-sec pt-4 pb-4">
        <div class="container">
            <div class="breadcrumb-item text-center">
                <h4>Sign In</h4>
                <img src="{{ asset('assets/frontend/images/breadcrumb_shape.svg') }}" alt="">
            </div>
        </div>
    </div>
    <!-- ======================= breadcrumb end  ============================ -->

    <!-- ======================= sign in start  ============================ -->
    <div class="signin-sec pb-5 mb-5">
        <div class="container">
            <div class="row">

                <div class="signin_form">
                    <form method="POST" action="{{ route('user.login.submit') }}">
                        @csrf
                        <div class="register_with text-center my-5">
                            <input type="radio" id="userRadio" class="role_id" name="role_id" value="1"
                                   class="active me-sm-3" checked>
                            <label for="userRadio">User</label>

                            <input type="radio" id="authorRadio" class="role_id" name="role_id" value="2"
                                   class="me-sm-3">
                            <label for="authorRadio">Author</label>

                            <input type="radio" id="institutionRadio" class="role_id" name="role_id" value="3">
                            <label for="institutionRadio">Institution</label>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email"
                                   class="custom_form form-control @error('email') is-invalid @enderror"
                                   placeholder="name@example.com">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password-field"
                                       class="custom_form form-control border-end-0 @error('password') is-invalid @enderror"
                                       placeholder="Enter your password">
                                <span toggle="#password-field"
                                      class="fa fa-fw fa-eye field-icon confirm_pass input-group-text"></span>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <a href="{{ route('password.forgotRequest') }}" class="forgotPassword"><span>Forgot
                                    password?</span></a>
                        </div>


                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn_primary w-100 sign_in_btn">Sign In</button>
                        </div>

                        <p class="have_account mb-5 text-center readerOnly">
                            Don’t have an account? <a href="{{ route('user.registration') }}">Sign Up</a>
                        </p>
                        <p class="have_account mb-5 text-center notReader newAccount" style="display:none;">
                            <span class="author">Are you interested in
                            becoming an author? Kindly send us an email today indicating your interest
                            <a href="mailto:tclilibrary@gmail.com">tclilibrary@gmail.com</a>.</span>
                            {{-- <span class="institute">
                                Are you interested to create account for your institute? Kindly send us an email today indicating your interest
                            <a href="mailto:tclilibrary@gmail.com">tclilibrary@gmail.com</a>.
                            </span> --}}
                        </p>

                        <div class="shape_divider mb-4 text-center readerOnly">
                            <span>Or continue with:</span>
                        </div>

                        <div class="social_login text-center pb-4 readerOnly">
                            {{-- <a href="{{ url('auth/facebook/login') }}" style="background-color: #584AF8;">
                                <img src="{{ asset('assets/frontend/images/social/facebook.png') }}" alt="Facebook">
                                Facebook
                            </a> --}}
                            <a href="{{ url('google/login') }}" style="background-color: #FF7D7D;">
                                <img src="{{ asset('assets/frontend/images/social/google.png') }}" alt="Google">
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let successMessage = "{{ Session::get('success') }}";
            if (successMessage) {
                toastr.success(successMessage);
            }
        });

        $(document).ready(function () {
            $('.role_id').change(function () {
                var userType = $(this).val();
                var signupButton = $('.btn_primary');
                var checkedLabel = $('input[name="role_id"]:checked + label');
                var uncheckedLabels = $('.register_with label');
                var inputs = $('.custom_form');

                var colorMappings = {
                    '1': '#800080',
                    '2': '#d5d10a',
                    '3': '#FD9644'
                };
                var colorMappings2 = {
                    '1': '#fcd6fc',
                    '2': '#f8f7d8',
                    '3': '#f5ede6'
                };
                var colorMappings3 = {
                    '1': '#3b435454',
                    '2': '#3b435454',
                    '3': '#3b435454'
                };
                var styleRule = `
                    .custom_form::placeholder {
                        color: ${colorMappings3[userType]};
                    }
                `;

                $('<style>').text(styleRule).appendTo('head');

                uncheckedLabels.css('background', '#ffffff');
                signupButton.css('background', colorMappings[userType]);
                checkedLabel.css('background', colorMappings[userType]);
                inputs.css('border', '1px solid ' + colorMappings[userType]);
                inputs.css('background', colorMappings2[userType]);

                console.log(userType)
                if (parseInt(userType) === 1) {
                    $('.notReader').hide();
                    $('.readerOnly').show();
                } else {
                    if (parseInt(userType) === 2) {
                        $('.newAccount .author').show();
                        $('.newAccount .institute').hide();
                    }else{
                        $('.newAccount .author').hide();
                        $('.newAccount .institute').show();
                    }
                    $('.notReader').show();
                    $('.readerOnly').hide();
                }
            });
        })
    </script>
@endpush
