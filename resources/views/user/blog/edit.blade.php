@extends('admin.layouts.user')
@section('blog', 'active')
@section('title') {{ $title ?? 'Edit Blog Post' }} @endsection

@push('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
    <style>
        body {
            overflow-x: hidden;
        }

        .bootstrap-tagsinput .tag {
            margin-right: 2px;
            color: white !important;
            background-color: #0d6efd;
            padding: 4px 6px;
            border-radius: 6px;
            font-size: 13px;
        }

        .bootstrap-tagsinput {
            border: none;
            width: 100%;
            font-size: 14px;
            padding: 10px;
            border-radius: 6px;
            color: var(--black);
            border: 1px solid #e8e8e8 !important;
            border-top: none !important;
        }

        .select2-container .select2-selection--multiple {
            height: 42px;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 2px;
            font-size: 16px;
        }

        .select2-container .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
        }

        .select2-container .select2-selection--multiple .select2-selection__choice {
            background-color: #0d6efd;
            border: 1px solid #aaa;
            border-radius: 3px;
            box-sizing: border-box;
            display: inline-block;
            margin-left: 5px;
            margin-top: 4px;
            padding: 0;
            padding-left: 31px;
            position: relative;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .select2-container .select2-selection--multiple .select2-selection__choice__remove:hover {
            background: transparent !important;
            color: #fff !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #ced4da !important;
        }

        textarea.select2-search__field {
            border: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper pb-5">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="mb-0">Blog</h1>
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
                                        <h3 class="card-title">{{ $data['title'] ?? 'Edit Blog Post' }} </h3>
                                    </div>
                                    <div class="col-6">
                                        <div class="float-right">
                                            <a href="{{ route('user.blog.index') }}" class="btn btn-sm"
                                                id="custom_btn">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-4">
                                <form action="{{ route('user.blog.update', $data['row']->id) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title" class="form-label">Title <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="title" id="title" class="form-control"
                                                    value="{{ $data['row']->title }}" placeholder="Title">
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="category_id" class="form-label">Category <span
                                                        class="text-danger">*</span></label>
                                                <select name="category_id[]" id="category_id"
                                                    class="form-select form-control" multiple>
                                                    <option value="" class="d-none">Select</option>
                                                    @foreach ($data['categories'] as $key => $row)
                                                        <option value="{{ $row->id }}"
                                                            {{ in_array($row->id, $data['maps']->pluck('blog_category_id')->toArray()) ? 'selected' : '' }}>
                                                            {{ $row->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tags" class="form-label">Tag <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="tags" id="tags" class="form-control"
                                                    data-role="tagsinput" value="{{ implode(',', $data['tags']) }}"
                                                    required>
                                            </div>
                                        </div>
                                        {{-- @if ($data['row']->status == 1)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="status" class="form-label">Status <span
                                                            class="text-danger">*</span></label>
                                                    <select name="status" id="status" class="form-select form-control">
                                                        <option value="1"
                                                            {{ $data['row']->status == 1 ? 'selected' : '' }}>Active</option>
                                                        <option value="0"
                                                            {{ $data['row']->status == 0 ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="image" class="form-label">Image <span class="text-danger">*
                                                        (Recommendation size: 250X200)</span> [<a
                                                        href="{{ asset($data['row']->image) }}" target="_blank"> Click Here
                                                        to view previous logo </a>]</label>
                                                <input type="file" name="image" id="image" accept="image/*"
                                                    class="form-control" placeholder="Image">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="description" class="form-label">Short Description <span
                                                        class="text-danger">* (Maximum character limit 250)</span></label>
                                                <textarea name="short_descriptions" id="short_description" class="form-control" rows="8">
                                                    {{ $data['row']->short_descriptions ?? old('short_descriptions') }}
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="description" class="form-label">Description<span
                                                        class="text-danger">*</span></label>
                                                <textarea name="descriptions" id="summernote" class="summernote" cols="30" rows="5" class="form-control">{{ $data['row']->descriptions ?? old('descriptions') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn text-light px-5"
                                                id="custom_btn">Update</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script>
        $('#category_id').select2();

        $(document).ready(function(){
            $(document).on('input', '#short_description', function(){
                var maxLength = 250;
                var length = $(this).val().length;
                var remaining = maxLength - length;

                if(length > maxLength){
                    $(this).val($(this).val().substring(0, maxLength));
                    $(this).next('p').remove();
                    $(this).after('<p class="text-danger">Maximum character limit exists</p>');
                } else {
                    $(this).next('p').remove();
                    $(this).after(`<p class="text-danger">You have remaining ${remaining} characters</p>`);
                }
            });
        });
    </script>
@endpush
