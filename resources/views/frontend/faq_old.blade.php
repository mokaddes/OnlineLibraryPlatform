@extends('frontend.layouts.app')

@section('title') {{ $data['title'] ?? 'Page header' }} @endsection
@section('meta')
<meta property="og:title" content="{{ $og_title }}" />
<meta property="og:description" content="{{ $og_description }}" />
<meta property="og:image" content="{{ asset($og_image) }}" />
@endsection

@push('style') @endpush

@section('content')
<!-- ======================= breadcrumb start  ============================ -->
<div class="breadcrumb_sec">
    <div class="container">
        <div class="breadcrumb_nav text-center">
            <h5>{{ $title ?? 'Page header' }}</h5>
            <h2>Faq</h2>
        </div>
    </div>
</div>
<!-- ======================= breadcrumb end  ============================ -->

<!-- ======================= faq start  ============================ -->
<div class="faq_sec section">
    <div class="container">
        <div class="row">
            <div class="faq_question_wrap">
                <div class="accordion" id="accordionExample">

                    @foreach ($faqs as $key => $row)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading_{{ $key }}">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse_{{ $key }}" aria-expanded="true"
                                aria-controls="collapse_{{ $key }}">
                                {{ $row->title }}
                            </button>
                        </h2>
                        <div id="collapse_{{ $key }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}"
                            aria-labelledby="heading_{{ $key }}" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ $row->body }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ======================= faq end  ============================ -->
@endsection

@push('script')

@endpush
