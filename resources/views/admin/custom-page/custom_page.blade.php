@extends('admin.layouts.master')
@section($data['nav_link'], 'active')
@section('pages_menu', 'active menu-open')
@section('title') {{ $data['title'] ?? '' }} @endsection
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
                                <h3 class="card-title">{{ $data['title'] ?? 'Custom-Page' }}</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.category.index') }}" class="btn btn-sm btn-light"
                                    style="border: 1px solid #F1F1F1"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4 px-5">
                        <form action="{{ route('admin.cpage.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="title" value="{{$data['row']->title}}">
                            <input type="hidden" name="url_slug" value="{{$data['row']->url_slug}}">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="body" class="form-lable"> <span style="font-weight: normal !important">{{$data['title']}}</span> Body</label>
                                        <textarea name="body" id="summernote" class="summernote"
                                        cols="30" rows="5" class="form-control">{{ $data['row']->body ?? old('body') }}</textarea>
                                    </div>
                                    {{-- @error('body')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>
                            
                            <div class="row mt-5">
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
