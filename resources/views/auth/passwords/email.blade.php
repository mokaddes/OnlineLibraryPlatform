@extends('frontend.layouts.app')
@section('title')
{{ $title ?? 'Password' }}
@endsection

@push('style')
<style>

</style>
@endpush

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-5 mb-5">
            <div class="signin_form">
                <form method="POST" action="{{ route('password.forgotEmail') }}">
                    @csrf
                    <div class="text-center">
                        <img src="{{asset('assets/frontend/images/Login.gif')}}" width="300" class="img-fluid" alt="">
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="custom_form form-control @error('email') is-invalid @enderror"
                            placeholder="name@example.com" value="{{ old('email') }}" >
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn_primary w-100"> {{ __('Send Password Reset Link') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
@endpush
