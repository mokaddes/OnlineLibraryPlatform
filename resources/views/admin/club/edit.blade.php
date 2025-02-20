@extends('admin.layouts.master')
@section('package', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection
@push('style')
<style>
    input, select, textarea {
    border-radius: 10px !important;
}   
@media only screen and (min-width: 768px) {
    #responsive_btn {
        margin-top: 30px;
    }
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
                                <h3 class="card-title">Edit Package</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.package.index') }}" class="btn btn-sm btn-light"
                                    style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="#" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" required placeholder="Name">
                                    </div>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="text" name="price" id="price" class="form-control" required placeholder="Price">
                                    </div>
                                    @error('price')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title" class="form-label">Status</label>
                                        <select name="physicalForm" id="physicalForm" class="form-select form-control">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row offset-md-1">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sub_title" class="form-label">Sub title</label>
                                        <input type="text" name="sub_title" id="sub_title" class="form-control" required placeholder="Sub title">
                                    </div>
                                    @error('sub_title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="details" class="form-label">Details</label>
                                        <input type="text" name="details" id="details" class="form-control" required placeholder="Details">
                                    </div>
                                    @error('details')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4" id="responsive_btn">
                                    <button type="submit" class="btn" style="background: #3139FB;"><i class="fas fa-plus" style="color: #f4f5f6;"></i></button>
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn text-light px-5" id="custom_btn">Edit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
