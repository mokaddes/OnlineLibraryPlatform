@extends('admin.layouts.user')
@section('ticket', 'active')
@section('title') {{ $title ?? '' }} @endsection
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
                <div class="row px-2 mb-4">
                    <h4>{{ $title }}</h4>
                </div>
                <div class="card mt-4" style="border: 1px solid#E6EDFF;">
                    <div class="card-header" >
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">Add New</h3>
                            </div>
                            <div>
                                <a href="{{ route('user.ticket.index') }}" class="btn btn-sm" id="custom_btn">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{ route('user.ticket.store') }}" method="post" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-xl-4">
                                    <div class="form-group">
                                        <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                        <input type="text" name="subject" id="subject" class="form-control" required placeholder="Subject" value="{{ old('subject') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="form-group">
                                        <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                        <select name="priority" id="priority" class="form-select form-control" >
                                            <option value="" class="d-none">Select</option>
                                            <option value="1" {{ old('priority') == '1' ? 'selected' : '' }}>Low</option>
                                            <option value="2" {{ old('priority') == '2' ? 'selected' : '' }}>Medium </option>
                                            <option value="3" {{ old('priority') == '3' ? 'selected' : '' }}>High</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="form-group">
                                        <label for="attachment" class="form-label">Attachment</label>
                                        <input type="file" name="attachment" id="attachment" class="form-control" >
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="message" class="form-lable">Message <span class="text-danger">*</span></label>
                                        <textarea name="message" required cols="30" rows="5"
                                        class="form-control" placeholder="message">{{ old('message') }}</textarea>
                                    </div>
                                    {{-- @error('message')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn text-light px-5" id="custom_btn">Create Now</button>
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
@endpush
