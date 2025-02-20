@extends('admin.layouts.master')
@section('forumCategory', 'active')
@section('forum_menu', 'active menu-open')
@section('title') {{ $data['title'] ?? '' }} @endsection
@push('style')
<style>
    input, select, textarea {
    border-radius: 10px !important;
}
</style>
@endpush
@section('content')
    <div class="content-wrapper mt-3" style="background: #FFFFFF;">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header" style="border-bottom:none !important; background: #ebeefc91;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">Add New</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.forum.category.index') }}" class="btn btn-sm btn-light"
                                    style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{ route('admin.forum.category.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row px-5">
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" name="name" id="name" class="form-control" required placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="order_number" class="form-label">Order Number</label>
                                            <input type="number" name="order_number" id="order_number" class="form-control" required placeholder="Order Number">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" id="status" class="form-select form-control" required>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                        {{-- @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror --}}
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn text-light px-5" id="custom_btn">Add Category</button>
                                    </div>
                                </div>
                                <div class="col-md-6"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
