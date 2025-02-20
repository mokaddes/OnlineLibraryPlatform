@extends('frontend.layouts.app')
@section('title')
{{ $title ?? 'Faq' }}
@endsection

@push('style')
@endpush

@section('content')

    <!-- ======================= breadcrumb start  ============================ -->
    <div class="breadcrumb-sec pt-4 pb-4">
        <div class="container">
            <div class="breadcrumb-item text-center">
                <h4>Faq's</h4>
                <img src="{{asset('assets/frontend/images/breadcrumb_shape.svg')}}" alt="">
            </div>
        </div>
    </div>
    <!-- ======================= breadcrumb end  ============================ -->

    <!-- ======================= faq start  ============================ -->
    @if (!empty($faqs))
        <div class="faq-sec pb-5 mb-5">
            <div class="container">
                <div class="faq-question">
                    <div class="row">
                        <div class="col-lg-5 order-lg-2">
                            <img src="{{asset('assets/frontend/images/Questions-amico.png')}}" class="img-fluid" alt="">
                        </div>
                        <div class="col-lg-7">
                            <div class="accordion" id="accordionExample">
                                @foreach ($faqs as $key => $row)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button @if($key != 0) collapsed @endif" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{$key}}" aria-expanded="@if($key == 0) true @else false @endif" aria-controls="collapse{{$key}}">
                                                {{ $row->title }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{$key}}" class="accordion-collapse collapse @if($key == 0) show @endif"
                                            data-bs-parent="#accordionExample">
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
        </div>
    @endif
    <!-- ======================= faq end  ============================ -->

@endsection

@push('script')
@endpush
