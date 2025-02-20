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
                <form action="{{ route('admin.cpage.home.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header" >
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="card-title">Home</h3>
                                </div>
                                <div>
                                    <a href="{{ route('admin.category.index') }}" class="btn btn-sm btn-light"
                                        style="border: 1px solid #F1F1F1"></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body mt-4 px-5">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label for="title" class="form-lable">Top section Title:</label>
                                        <input type="text" name="title" id="title" value="{{ old('title') ?? $data['sections']->title ?? ''  }}"
                                        class="form-control" placeholder="Top section title">

                                    </div>
                                    {{-- @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label for="sub_title" class="form-lable">Top section description:</label>
                                        <textarea name="sub_title" cols="30" rows="5" id="sub_title"
                                        class="form-control">{{ old('sub_title') ?? $data['sections']->sub_title ?? '' }}</textarea>
                                    </div>
                                    {{-- @error('subtitle')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label for="image" class="form-lable">Top section banner: [<span class="text-danger"> Recommended size : 500 x 500 </span>]
                                        @if(isset($data['sections']->image)) [<a href="{{ asset($data['sections']->image) }}" target="_blank"> Click Here to view previous banner </a>] @endif
                                        </label>
                                        <input type="file" name="image" id="image" class="form-control" placeholder="Top section banner">
                                    </div>
                                    {{-- @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>

                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label for="button_text1" class="form-lable">Button 1 Text:</label>
                                        <input type="text" name="button_text1" id="button_text1" value="{{ old('button_text1') ?? $data['sections']->button_text1 ?? '' }}"
                                        class="form-control" placeholder="Button 1 Text">
                                    </div>
                                    {{-- @error('button_text1')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>

                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label for="button_text1" class="form-lable">Button 1 Link:</label>
                                        <input type="text" name="button_link1" id="button_link1" value="{{ old('button_link1') ?? $data['sections']->button_link1 ?? '' }}"
                                        class="form-control" placeholder="Button 1 Text">
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label for="button_text2" class="form-lable">Button 2 Text:</label>
                                        <input type="text" name="button_text2" id="button_text2" value="{{ old('button_text2') ?? $data['sections']->button_text2 ?? '' }}"
                                        class="form-control" placeholder="Button 2 Text">
                                    </div>
                                    {{-- @error('button_text2')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label for="button_text1" class="form-lable">Button 2 Link:</label>
                                        <input type="text" name="button_link2" id="button_link2" value="{{ old('button_link2') ?? $data['sections']->button_link2 ?? '' }}"
                                        class="form-control" placeholder="Button 2 Link">
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label for="book_of_month_image" class="form-lable">Book of Month Image: [<span class="text-danger"> Recommended size : 500 x 500 </span>]
                                            @if(isset($data['sections']->book_of_month_image)) [<a href="{{ asset($data['sections']->book_of_month_image) }}" target="_blank"> Click Here to view previous banner </a>] @endif
                                        </label>
                                        <input type="file" name="book_of_month_image" id="book_of_month_image" class="form-control" >
                                    </div>
                                </div>
                                <div class="row mt-5 mb-5">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn text-light px-5" id="custom_btn">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
