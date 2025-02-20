@extends('frontend.layouts.app')
@section('title')
    {{ $title ?? 'Contact' }}
@endsection

@push('style')
<style>
    .grecaptcha-badge {
        z-index: 10 !important;
        bottom: 100px !important;
    }
</style>
@endpush

@section('content')

    <!-- ======================= breadcrumb start  ============================ -->
    <div class="breadcrumb-sec pt-4 pb-4">
        <div class="container">
            <div class="breadcrumb-item text-center">
                <h4>Contact</h4>
                <img src="{{asset('assets/frontend/images/breadcrumb_shape.svg')}}" alt="">
            </div>
        </div>
    </div>
    <!-- ======================= breadcrumb end  ============================ -->

    <!-- ======================= contact start  ============================ -->
    <div class="contact-sec pb-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <form action="{{ route('frontend.contact.submit') }}" method="post" id="contactForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}" id="name" class="custom_form form-control" autofocus
                                           tabindex="1"
                                           placeholder="john doe" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" id="email" tabindex="2"
                                           class="custom_form form-control"
                                           placeholder="name@example.com" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                    <input type="subject" name="subject" value="{{ old('subject') }}" tabindex="3" id="subject"
                                           class="custom_form form-control"
                                           placeholder="subject" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea name="message" id="message" tabindex="4" cols="30" rows="6"
                                              placeholder="Write your comment..."
                                              class="custom_form form-control">{{ old('message') }}</textarea>
                                </div>
                            </div>


                            <div class="col-12">
                                @if ($settings->recaptcha_enable == 1)
                                    <button type="submit" class="g-recaptcha btn btn_primary"
                                            data-sitekey="{{ $settings->recaptcha_site_key }}"
                                            data-callback='onSubmit'
                                            data-action='submit'>Send Message
                                    </button>
                                @else
                                    <button type="submit" class="btn btn_primary">Send Message</button>
                                @endif

                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 d-none d-lg-block text-center">
                    <div class="">
                        <img src="{{asset('assets/frontend/images/contact.gif')}}" class="img-fluid" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= contact end  ============================ -->

@endsection

@push('script')
    <script src="https://www.google.com/recaptcha/api.js"></script>

    <script>
        function onSubmit(token) {
            document.getElementById("contactForm").submit();
        }
    </script>
@endpush
