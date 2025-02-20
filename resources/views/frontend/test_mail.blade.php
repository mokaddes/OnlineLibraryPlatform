@extends('frontend.layouts.app')
@section('title')
    {{ $title ?? 'Test mail' }}
@endsection

@push('style')
@endpush

@section('content')

    <!-- ======================= breadcrumb start  ============================ -->
    <div class="breadcrumb-sec pt-4 pb-4">
        <div class="container">
            <div class="breadcrumb-item text-center">
                <h4>Test Mail</h4>
                <img src="{{asset('assets/frontend/images/breadcrumb_shape.svg')}}" alt="">
            </div>
        </div>
    </div>
    <!-- ======================= breadcrumb end  ============================ -->

    <!-- ======================= sign in start  ============================ -->
    <div class="signin-sec pb-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="signin_form">
                    <form method="POST" action="{{ route('sendTestMail') }}">
                        @csrf
                        <div class="text-center">
                            <img src="{{asset('assets/frontend/images/Login.gif')}}" width="300" class="img-fluid" alt="">
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="custom_form form-control @error('email') is-invalid @enderror"
                                   placeholder="name@example.com" >
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>




                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn_primary w-100">Send test mail</button>
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
