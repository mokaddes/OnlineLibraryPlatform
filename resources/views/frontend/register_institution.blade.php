@extends('frontend.layouts.app')
@section('title')
{{ $title ?? 'Registration' }}
@endsection

@push('style')
@endpush

@section('content')

    <!-- ======================= breadcrumb start  ============================ -->
    <div class="breadcrumb-sec pt-4 pb-4">
        <div class="container">
            <div class="breadcrumb-item text-center">
                <h4>Create a new account</h4>
                <img src="{{asset('assets/frontend/images/breadcrumb_shape.svg')}}" alt="">
            </div>
        </div>
    </div>
    <!-- ======================= breadcrumb end  ============================ -->

    <!-- ======================= sign in start  ============================ -->
    <div class="signin-sec pb-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="register_with text-center mb-3">
                    <a href="{{route('user.registration')}}" class="me-sm-3">User</a>
                    <a href="{{route('author.registration')}}" class="me-sm-3">Author</a>
                    <a href="{{route('institution.registration')}}" class="active">Institution</a>
                </div>
                <div class="signin_form">
                    <form action="#" class="pt-4" method="post">
                        <div class="form-group mb-3">
                            <label for="institution_name" class="form-label">Institution  Name</label>
                            <input type="text" name="institution_name" id="institution_name" autofocus class="custom_form form-control"
                                placeholder="Enter your institution name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="custom_form form-control"
                                placeholder="Enter your email" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password-field"
                                    class="custom_form form-control border-end-0" placeholder="Enter your password" required>
                                <span toggle="#password-field"
                                    class="fa fa-fw fa-eye field-icon toggle-password input-group-text"></span>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="confirm_password" id="confirm_password"
                                    class="custom_form form-control border-end-0" placeholder="confirm your password" required>
                                <span toggle="#confirm_password"
                                    class="fa fa-fw fa-eye field-icon confirm_pass input-group-text"></span>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn_primary w-100">Sign Up</button>
                        </div>

                        <p class="have_account mb-5 text-center">
                            Already have an account? <a href="signin.html">Sign In</a>
                        </p>

                        <div class="shape_divider mb-4 text-center">
                            <span>Or continue with:</span>
                        </div>

                        <div class="social_login text-center pb-4">
                            {{-- <a href="#" style="background-color: #584AF8;">
                                <img src="{{asset('assets/frontend/images/social/facebook.png')}}" alt="Facebook">
                                Facebook
                            </a> --}}
                            <a href="#" style="background-color: #FF7D7D;">
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
@endpush
