@extends('frontend.layouts.app')
@section('title')
    {{ $title ?? 'Home' }}
@endsection

@push('style')
    <style>
        .book_of_month {
            position: relative;
        }

        .book_of_month a:before {
            content: '';
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("{{ asset($book_of_month->thumb ?? '') }}"), lightgray 50% / cover no-repeat;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 22px;
            opacity: .4;
        }
    </style>
@endpush

@section('content')

    <!-- ======================= banner start  ============================ -->
    <div class="banner-section pt-4 pb-4">
        <div class="container">
            <div class="row gy-4 gy-lg-0 align-items-center text-center text-lg-start">
                <div class="col-lg-6 order-lg-2">
                    @if (!empty($section->image))
                        <div class="banner-img float-lg-end">
                            <img src="{{ asset($section->image) }}" class="img-fluid" alt="image">
                        </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="banner-content">
                        @if (!empty($section->title))
                            <h2 class="mb-4">{{ $section->title }}</h2>
                        @endif
                        @if (!empty($section->sub_title))
                            <p class="mb-5">
                                {{ $section->sub_title }}
                            </p>
                        @endif
                        @if (!empty($section->button_text1))
                            <a href="{{ $section->button_link1 }}"
                               class="btn btn_primary me-0 me-sm-3">{{ $section->button_text1 }}</a>
                        @endif
                        @if (!empty($section->button_text2))
                            <a href="{{ $section->button_link2 }}"
                               class="btn btn_secondary">{{ $section->button_text2 }}</a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- ======================= banner end  ============================ -->

    <!-- ======================= highlights book start  ============================ -->
    @if (!empty($highlights) && $highlights->count() > 0)
        <div class="highlights_book_sec pt-5 pb-5">
            <div class="container">
                <div class="row">
                    <div class="section_heading pb-5 text-center">
                        <h4>Our Book Highlights</h4>
                        <div class="heading_divider">
                            <img src="{{ asset('assets/frontend/images/divider_shape.svg') }}" alt="Image">
                        </div>
                    </div>
                    <div class="swiper highlights_book">
                        <div class="swiper-wrapper">
                            @foreach ($highlights as $row)
                                <div class="swiper-slide">
                                    <a href="{{ route('user.book.details', $row->slug) }}">
                                        <img src="{{ asset($row->thumb) }}" class="img-fluid" alt="book">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- ======================= highlights book end  ============================ -->

    <!-- ======================= book of the month start  ============================ -->
    @if (!empty($book_of_month))
        <div class="book_month_sec pt-5 pb-5 mb-5">
            <div class="container">
                <div class="section_heading pb-5 text-center">
                    <h4>Book of the month</h4>
                    <div class="heading_divider">
                        <img src="{{ asset('assets/frontend/images/divider_shape.svg') }}" alt="Image">
                    </div>
                </div>

                <div class="row text-center text-lg-start align-items-center">
                    <div class="col-lg-6 order-lg-2">
                        <div class="text-center">
                            <img
                                src="{{ asset($section->book_of_month_image ?? 'assets/frontend/images/book-reading.gif') }}"
                                class="img-fluid"
                                alt="image">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="book_of_month mb-5">
                            <a href="{{ route('user.book.details', $book_of_month->slug) }}">
                                <img src="{{ asset($book_of_month->thumb) }}" class="img-fluid" alt="book"
                                     style="min-width: 300px; min-height: 350px;">
                            </a>
                        </div>
                        @if(!Auth::check())
                            <div class="text-center" id="responsive_text">
                                <a href="{{ route('user.login') }}" class="btn btn_secondary">Login to read for free</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- ======================= book of the month end  ============================ -->

    <!-- ======================= Our Categories start  ============================ -->
    @if (!empty($categories))
        <div class="categories_sec pt-5 pb-5">
            <div class="container">
                <div class="section_heading pb-5 text-center">
                    <h4>Our Categories</h4>
                    <div class="heading_divider">
                        <img src="{{ asset('assets/frontend/images/divider_shape.svg') }}" alt="Image">
                    </div>
                </div>

                <div class="row gy-3 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                    @foreach ($categories as $row)
                        <div class="col">
                            <div class="category_item position-relative overflow-hidden text-center">
                                <div class="category_icon mb-3">
                                    <img src="{{ asset($row->logo) }}" class="img-fluid" width="50" alt="image">
                                </div>
                                <div class="category_name">
                                    <a href="{{ route('user.book.index', ['category'=>$row->id]) }}"
                                       class="stretched-link">{{ $row->name }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <!-- ======================= Our Categories end  ============================ -->

    <!-- ======================= Become a Premium Member start  ============================ -->
    <div class="become_premium_member pb-5">
        <div class="heading_divider">
            <img src="{{ asset('assets/frontend/images/divider_shape2.svg') }}" class="w-100" alt="Image">
        </div>
        <div class="container">
            <div class="premium_content text-center">
                <h4 class="mb-4">Become a Premium Member today!</h4>
                <p class="mb-4">
                    We Can’t Wait To Let You Into Our World Of Endless Possibilities.
                </p>
                <a href="{{ route('frontend.pricing') }}" class="btn btn_secondary ">Premium Member</a>
            </div>
        </div>
    </div>
    <!-- ======================= Become a Premium Member end  ============================ -->

@endsection


