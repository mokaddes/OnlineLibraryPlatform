@extends('admin.layouts.master')
@section('cpage', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection

@php

    $row = $data['row'];

@endphp

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $data['title'] ?? '' }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.cpage.index') }}">Manage
                                    {{ $data['title'] ?? '' }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $data['title'] ?? '' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <h3 class="card-title"> {{ $data['title'] ?? '' }}</h3>
                                    </div>
                                    <div class="col-6">
                                        <div class="float-right">
                                            <a href="{{ route('admin.cpage.index') }}" class="btn btn-primary">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body table-responsive p-4">
                                <form action="{{ route('admin.cpage.update', $row->id) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="title" class="form-lable">Page Name</label>
                                                <input type="text" name="title" id="title" class="form-control"
                                                    required value="{{ $row->title }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="slug" class="form-lable">Page Slug</label>
                                                <input type="text" name="slug" id="slug" readonly
                                                    class="form-control" required value="{{ $row->url_slug }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="status" class="form-lable">Published Status</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="1" {{ $row->is_active == 1 ? 'selected' : '' }}>
                                                        Published</option>
                                                    <option value="0" {{ $row->is_active == 0 ? 'selected' : '' }}>
                                                        Unpublished</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="status" class="form-lable">Description</label>
                                                <textarea name="body" cols="30" rows="5" class="form-control summernote" required>{!! $row->body !!}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success">Update Page</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // slug generator
        $(document).on('input','#title', function(){
            let titleValue = event.target.value;
            let slug = titleValue.toLowerCase()
                .replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ')
                .replace(/^\s+|\s+$/gm, '')
                .replace(/\s+/g, '-');
            $('#slug').val(slug);
        })
    </script>
@endpush
