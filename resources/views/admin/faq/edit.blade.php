@extends('admin.layouts.master')
@section('faq', 'active')
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
                                <h3 class="card-title">Edit Faq</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.faq.index') }}" class="btn btn-sm btn-light"
                                    style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="{{ route('admin.faq.update', $data['row']->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="question" class="form-label">Question</label>
                                        <input type="text" name="question" id="question" value="{{$data['row']->title}}"
                                        class="form-control" required placeholder="Question">
                                    </div>
                                    {{-- @error('question')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-select form-control">
                                            <option value="1" {{ $data['row']->is_active == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $data['row']->is_active == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    {{-- @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="order_number" class="form-label">Order Number</label>
                                        <input type="number" name="order_number" value="{{ $data['row']->order_id }}" id="order_number" class="form-control" required placeholder="Order Number">
                                    </div>
                                    {{-- @error('order_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="answer" class="form-lable">Answer</label>
                                        <textarea name="answer" required placeholder="Answer"
                                        cols="30" rows="5" class="form-control" style="line-height: 21px;"> {{ $data['row']->body }} </textarea>
                                    </div>
                                    {{-- @error('answer')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
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
