@extends('admin.layouts.user')
@section('club', 'active')
@section('title') {{ $title ?? '' }} @endsection
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
                    <div class="card-header" style="border-bottom:none !important; background: #99009926;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">Club Details</h3>
                            </div>
                            <div>
                                <a href="{{ route('user.club.index') }}" class="btn btn-sm btn-light"
                                    style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{ route('user.club.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title" class="form-label">Name</label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Club Name">
                                    </div>
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6" >
                                    <div class="form-group">
                                        <label for="profile_photo" class="form-label">Club Logo <span class="text-danger">[Recommended size: 150 x 150]</span></label>
                                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="form-control">
                                        @error('profile_photo')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6" >
                                    <div class="form-group">
                                        <label for="cover_photo" class="form-label">Cover Photo <span class="text-danger">[Recommended size: 1024 x 150]</span></label>
                                        <input type="file" name="cover_photo" id="cover_photo" accept="image/*" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="short_description" class="form-label">Short Description</label>
                                        <textarea name="short_description" id="short_description" cols="30" rows="5" class="form-control" 
                                        required style="height:100px !important;"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="about" class="form-lable">About</label>
                                        <textarea name="about" cols="30" rows="5" class="form-control summernote"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="rules" class="form-lable">Rules</label>
                                        <textarea name="rules" cols="30" rows="5" class="form-control summernote"></textarea>
                                    </div>
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
@push("script")
@endpush
