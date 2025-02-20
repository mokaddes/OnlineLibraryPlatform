@extends('frontend.layouts.app')
@section('title')
    {{ $title ?? 'Pricing' }}
@endsection
@php
    $Offerings = Config::get('app.Offerings');
    $Library_Content = Config::get('app.Library_Content');
    $Book_Access = Config::get('app.Book_Access');
    $Blog_Access = Config::get('app.Blog_Access');
    $Forum_Access = Config::get('app.Forum_Access');
    $Book_Club_Access = Config::get('app.Book_Club_Access');

@endphp

@push('style')
<style>
    .subscribed {
        background: linear-gradient(90deg, #13a3c3, #1777c9c2);
    }
</style>
@endpush

@section('content')
    <!-- ======================= breadcrumb start  ============================ -->
    <div class="breadcrumb-sec pt-4 pb-4">
        <div class="container">
            <div class="breadcrumb-item text-center">
                <h4>Subscription</h4>
                <img src="{{ asset('assets/frontend/images/breadcrumb_shape.svg') }}" alt="">
            </div>
        </div>
    </div>
    <!-- ======================= breadcrumb end  ============================ -->

    <!-- ======================= package start  ============================ -->
    <div class="package-sec pb-5 mb-5">
        <div class="container">
            <div class="pricing_table table-responsive">
                <table class="table table-striped text-successtable-border border-light custom_table">
                    <thead class="border-light">
                    <tr>
                        <th style="width: 25%" class="title" scope="col"></th>
                        @foreach($packages as $package)
                            <th style="width: 25%" class="title" scope="col">{{ $package->title }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">Price</th>
                        @foreach($packages as $package)
                            <td>
                                @if($package->price == 0)
                                    Free
                                @else
                                    ${{ $package->price }} or N{{ $package->price_ngn }}   {{ $package->duration < '50' ? 'Monthly' : 'Annually' }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Offerings</th>
                        @foreach($packages as $package)
                            <td>
                                {{ $Offerings[$package->offerings] ?? '' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Library Content</th>
                        @foreach($packages as $package)
                            <td>
                                {{ $Library_Content[$package->library]?? '' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Book Access</th>
                        @foreach($packages as $package)
                            <td>
                                {{ $Book_Access[$package->book]?? '' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Blog</th>
                        @foreach($packages as $package)
                            <td>
                                {{ $Blog_Access[$package->blog]?? '' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Forum</th>
                        @foreach($packages as $package)
                            <td>
                                {{ $Forum_Access[$package->forum]?? '' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Book Club</th>
                        @foreach($packages as $package)
                            <td>
                                {{ $Book_Club_Access[$package->club]?? '' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row"></th>
                        @foreach($packages as $package)
                            @if($package->is_subscribed)
                                <td>
                                    <button class="pe-none btn btn_primary subscribed">Subscribed</button>
                                </td>
                            @else
                                <td>

                                    @if ($package->price != '0' &&  $package->price_ngn != '0')
                                        <a href="{{route('user.checkout', ['id' => $package->id])}}"
                                        class="btn btn_primary">Subscribe</a>
                                    @else
                                        <a href="{{ route('user.package.subscribe', $package->id) }}"
                                        class="btn btn_primary">Subscribe</a>
                                    @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- ======================= package end  ============================ -->
@endsection

@push('script')
@endpush
