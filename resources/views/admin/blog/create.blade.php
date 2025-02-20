@extends('admin.layouts.master')
@section('blog', 'active')
@section('blog_menu', 'active menu-open')
@section('title') {{ $data['title'] ?? '' }} @endsection
@push('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
<style>
    input, select, textarea {
    border-radius: 10px !important;
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
        border-radius: 10px;
        padding: 2px;
        font-size: 16px;
    }
    .select2-container .select2-selection--multiple .select2-selection__choice__remove{
        color: #fff;
    }
    .select2-container .select2-selection--multiple  .select2-selection__choice{
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
                                <h3 class="card-title">Add New</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.blog.index') }}" class="btn btn-sm btn-light"
                                    style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{ route('admin.blog.store') }}" method="post" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control" required placeholder="Title" value="{{ old('title') }}">
                                    </div>
                                    {{-- @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                        <select name="category_id[]" id="category_id" class="form-select form-control" multiple>

                                            @foreach ($data['rows'] as $key => $row)
                                                <option value="{{$row->id}}" {{ in_array($row->id, old('category_id', [])) ? 'selected' : '' }}>{{$row->name}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    {{-- @error('category_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tags" class="form-label">Tag <span class="text-danger">*</span></label>
                                        {{-- <input type="text" name="tags[]" id="tags" class="form-control" data-role="tagsinput" value="{{ implode(',', old('tags', [])) }}" required> --}}
                                        <input type="text" name="tags" id="tags" class="form-control" data-role="tagsinput" value="{{ old('tags') ? implode(',', old('tags', [])) : '' }}" required>
                                    </div>
                                    {{-- @error('tags')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-select form-control" >
                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    {{-- @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image" class="form-label">Image <span class="text-danger">*</span></label>
                                        <input type="file" name="image" id="image" accept="image/*" class="form-control" required placeholder="Image">
                                    </div>
                                    {{-- @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <br>
                                        <input type="checkbox" id="is_top" name="is_top" value="1" {{ old('is_top') ? 'checked' : '' }}>
                                        <label for="is_top" class="form-label"> Is On Top</label><br>
                                    </div>
                                    {{-- @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label">Short Description <span class="text-danger">*</span></label>
                                        <textarea name="short_descriptions"  class="form-control">{{ old('short_descriptions') }}</textarea>
                                    </div>
                                    {{-- @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea name="descriptions" id="summernote" class="summernote" required
                                        rows="5" class="form-control">{{ old('description') }}</textarea>
                                    </div>
                                    {{-- @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn text-light px-5" id="custom_btn">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script>
    $(document).ready(function() {
        $('#category_id').select2();
    });
</script>
@endpush
