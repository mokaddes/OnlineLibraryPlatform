@extends('admin.layouts.master')
@section('package', 'active')
@section('title') {{ $title ?? '' }} @endsection
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
    <div class="content-wrapper mt-3" >
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header" >
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">Edit Package</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.package.index') }}" class="btn btn-sm btn-light"
                                    style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{ route('admin.package.update', $package->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title:</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                               value="{{ old('title') ?? $package->title }}" required placeholder="Package Title">
                                    </div>
                                    @error('title')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Price:</label>
                                        <input type="number" class="form-control" id="price" name="price"
                                               value="{{ old('price') ?? $package->price }}" @if(!empty($package->plan_id) || !empty($package->plan_id2)) disabled @endif placeholder="Price in USD">
                                        @if(!empty($package->plan_id) || !empty($package->plan_id2))<small class="text-info">If you want to edit this price, please edit it through the payment provider</small>@endif
                                    </div>
                                    @error('price')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price_ngn">Price in NGN:</label>
                                        <input type="number" class="form-control" id="price_ngn" name="price_ngn"
                                               value="{{ old('price_ngn') ?? $package->price_ngn }}" @if(!empty($package->plan_id) || !empty($package->plan_id2)) disabled @endif placeholder="Price in NGN">
                                        @if(!empty($package->plan_id) || !empty($package->plan_id2))<small class="text-info">If you want to edit this price, please edit it through the payment provider</small>@endif
                                    </div>
                                    @error('price_ngn')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="duration">Duration:</label>
                                        <input type="number" class="form-control" id="duration" name="duration"
                                               value="{{ old('duration') ?? $package->duration }}" @if(!empty($package->plan_id) || !empty($package->plan_id2)) disabled @endif placeholder="Package duration in days">
                                        @if(!empty($package->plan_id) || !empty($package->plan_id2))<small class="text-info">If you want to edit this duration, please edit it through the payment provider</small>@endif
                                    </div>
                                    @error('duration')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="offerings">Offerings:</label>
                                        <select class="form-control" id="offerings" name="offerings" required>
                                            <option value="">Select an option</option>
                                            @foreach($Offerings as $key => $value)
                                                <option
                                                    value="{{ $key }}" {{ old('club', $package->offerings) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('offerings')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="library">Library Content:</label>
                                        <select class="form-control" id="library" name="library" required>
                                            <option value="">Select an option</option>
                                            @foreach($Library_Content as $key => $value)
                                                <option
                                                    value="{{ $key }}" {{ old('club', $package->library) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('library')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="book">Book Access:</label>
                                        <select class="form-control" id="book" name="book" required>
                                            <option value="">Select an option</option>
                                            @foreach($Book_Access as $key => $value)
                                                <option
                                                    value="{{ $key }}" {{ old('club', $package->book) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('book')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="blog">Blog Access:</label>
                                        <select class="form-control" id="blog" name="blog" required>
                                            <option value="">Select an option</option>
                                            @foreach($Blog_Access as $key => $value)
                                                <option
                                                    value="{{ $key }}" {{ old('club', $package->blog) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('blog')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="forum">Forum Access:</label>
                                        <select class="form-control" id="forum" name="forum" required>
                                            <option value="">Select an option</option>
                                            @foreach($Forum_Access as $key => $value)
                                                <option
                                                    value="{{ $key }}" {{ old('club', $package->forum) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('forum')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="club">Book Club Access:</label>
                                        <select class="form-control" id="club" name="club" required>
                                            <option value="">Select an option</option>
                                            @foreach($Book_Club_Access as $key => $value)
                                                <option
                                                    value="{{ $key }}" {{ old('club', $package->club) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('club')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn text-light px-5" id="custom_btn">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
