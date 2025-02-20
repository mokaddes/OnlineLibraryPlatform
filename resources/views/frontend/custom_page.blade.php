@extends('frontend.layouts.app')

@section('title')
    {{ $data['title'] ?? 'Page header' }}
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
    <div class="breadcrumb-sec pt-4 pb-4">
        <div class="container">
            <div class="breadcrumb-item text-center">
                <h4>{{ $row->title ?? 'Page header' }}</h4>
                <img src="{{ asset('assets/frontend/images/breadcrumb_shape.svg') }}" alt="">
            </div>
        </div>
    </div>
    <!-- ======================= breadcrumb end  ============================ -->

    <div class="privacy_policy_sec section">
        <div class="container">
            <div class="row">
                <div class="page_content mb-5">
                    <div class="content_wrap mb-5">
                        <p>{!! $row->body ?? 'Page description' !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
