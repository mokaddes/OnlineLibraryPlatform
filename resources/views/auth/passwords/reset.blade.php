@extends('frontend.layouts.app')
@section('title')
{{ $title ?? 'Password Reset' }}
@endsection
@push('style')
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="signin_form mb-5">
                <form method="post" action="{{ route('password.forgotReset') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="text-center">
                        <img src="{{asset('assets/frontend/images/Login.gif')}}" width="300" class="img-fluid" alt="">
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="custom_form form-control @error('email') is-invalid @enderror"
                            placeholder="name@example.com" value="{{ $email ?? old('email') }}" >
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
                                style="@error('password') border-top-right-radius: 8px;border-bottom-right-radius: 8px; @enderror"
                                class="fa fa-fw fa-eye field-icon toggle-password input-group-text @error('password') border-danger @enderror"></span>
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
                            <span toggle="#confirm_password"
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
                        <button type="submit" class="btn btn_primary w-100">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
@endpush
