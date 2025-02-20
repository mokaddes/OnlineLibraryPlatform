@extends('admin.layouts.master')
@section('package', 'active')
@section('title')
    {{ $title ?? '' }}
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
        input, select, textarea {
            border-radius: 10px !important;
        }

    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-3">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">View Package</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.package.index') }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <div class="pricing_table table-responsive">
                            <table class="table table-striped text-successtable-border border-light custom_table">
                                <thead class="border-light">
                                <tr>
                                    <th style="width: 25%" class="title" scope="col">Title</th>
                                    <th style="width: 25%" class="title" scope="col">{{ $package->title }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row">Price</th>
                                    <td>
                                        @if($package->price == 0)
                                            Free for {{ $package->duration }} days.
                                        @else
                                            ${{ $package->price }} or N{{ $package->price_ngn }}
                                            for {{ $package->duration }} days.
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Offerings</th>
                                    <td>
                                        {{ $Offerings[$package->offerings] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Library Content</th>
                                    <td>
                                        {{ $Library_Content[$package->library]?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Book Access</th>
                                    <td>
                                        {{ $Book_Access[$package->book]?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Blog Access</th>
                                    <td>
                                        {{ $Blog_Access[$package->blog]?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Forum Access</th>
                                    <td>
                                        {{ $Forum_Access[$package->forum]?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Book Club Access</th>
                                    <td>
                                        {{ $Book_Club_Access[$package->club]?? '' }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
