@extends('admin.layouts.master')
@section('category', 'active')
@section('library_menu', 'active menu-open')
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
                                <h3 class="card-title">Edit Category</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.category.index') }}" class="btn btn-sm btn-light"
                                    style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{ route('admin.category.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$data['row']->id}}">
                            <div class="row px-5">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name" id="name" value="{{ $data['row']->name }}" class="form-control" required placeholder="Name">
                                    </div>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="order_number" class="form-label">Order Number</label>
                                        <input type="number" name="order_number" value="{{ $data['row']->order_number }}" id="order_number" class="form-control" required placeholder="Order Number">
                                    </div>
                                    @error('order_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-select form-control" required>
                                            <option value="1" {{ $data['row']->status == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $data['row']->status == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="logo" class="form-label">Logo [<span class="text-danger"> Recommended size : 50 x 50 </span>]  @if($data['row']->logo)
                                            [<a href="{{ asset($data['row']->logo) }}" target="_blank"> Click Here to view previous logo </a>]
                                        @endif
                                        </label>
                                        <input type="file" name="logo" id="logo" accept="image/*" class="form-control" placeholder="Logo">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn text-light px-5" id="custom_btn">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
