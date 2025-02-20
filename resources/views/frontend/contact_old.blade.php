@extends('frontend.layouts.app')
@section('title')
{{ $settings->site_name ?? 'Page header' }}
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
    <div class="container">
        <div class="breadcrumb_nav text-center">
            <h5>BEST FRANCHISES</h5>
            <h2>Contact us</h2>
        </div>
    </div>
</div>
<!-- ======================= breadcrumb end  ============================ -->

<!-- ======================= contact start  ============================ -->
<div class="contact_sec mb-5 mt-5">
    <div class="container">
        <form action="#" method="post" class="contact_form">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="Enter your email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="number" name="phone" id="phone" class="form-control"
                                placeholder="Enter your phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Reason for Inquiry <span
                                    class="text-danger">*</span></label>
                            <select name="reason" id="reason" class="form-control">
                                <option value="" class="d-none">Reason for Inquiry*</option>
                                <option value="Advertising">Advertising</option>
                                <option value="Franchising my business">Franchising my business</option>
                                <option value="Help in finding a franchise">Help in finding a franchise</option>
                                <option value="Question about a franchise">Question about a franchise</option>
                                <option value="Site suggestion">Site suggestion</option>
                                <option value="Site question">Site question</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" cols="30" rows="5" class="form-control" required
                                placeholder="Write your message" style="height:150px !important;"></textarea>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ======================= contact end  ============================ -->
@endsection
@push('style')
@endpush
