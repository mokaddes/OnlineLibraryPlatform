@extends('frontend.layouts.app')
@section('title')
{{ $title ?? 'Profile Complete' }}
@endsection

@section('meta')
<meta property="og:title" content="{{ $og_title }}" />
<meta property="og:description" content="{{ $og_description }}" />
<meta property="og:image" content="{{ asset($og_image) }}" />
@endsection

@push('style')
@endpush

@section('content')
<!-- ======================= breadcrumb start  ============================ -->
<div class="breadcrumb_sec">
    <div class="container mt-5">
        <div class="breadcrumb_nav text-center">
            <h2>Please complete your profile</h2>
        </div>
    </div>
</div>
<!-- ======================= breadcrumb end  ============================ -->

<!-- ======================= contact start  ============================ -->
<div class="contact_sec mb-5 mt-5">
    <div class="container">
        <form action="{{ route('user.profile.complete.update') }}" method="post" class="contact_form">
            @csrf
            <div class="row d-flex justify-content-center">
                <div class="col-lg-4">
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
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn_primary w-100">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ======================= contact end  ============================ -->
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
    </style>
@endpush
@push('script')
    @include('frontend.phone_number_script')
@endpush
