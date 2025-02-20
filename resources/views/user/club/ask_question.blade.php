@extends('admin.layouts.user')
@section('club', 'active')
@section('title') {{ $title ?? 'Club' }} @endsection
@push('style')
    <style>
        input,
        select,
        textarea {
            border-radius: 10px !important;
            border: 1px solid #800080;
        }
        .club-header{
            border-radius: 5px;
            background-color: #99009926;
            border-bottom: 1px solid rgba(0,0,0,.125);
            padding: 0.75rem 1.25rem;
            
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-3 pb-4">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="club-header d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title">Ask Question</h4>
                        </div>
                        <div>
                            <a href="{{ route('user.club.joinclub', $club_id) }}" class="btn btn-sm btn-light"
                                style="border: 1px solid #F1F1F1">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('user.club.question.submit')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="club_id" value="{{$club_id}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                                            value="{{ old('title') }}" placeholder="Title">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="msg" class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea name="msg" id="summernote" class="summernote" cols="30" rows="5" class="form-control">{{ old('msg') }}</textarea>
                                        @error('msg')
                                            <span class="text-danger" style="font-size: 12.8px;">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12" >
                                    <div class="form-group">
                                        <label for="attachment" class="form-label">Attachment </label>
                                        <input type="file" name="attachment" id="attachment" accept="image/*,application/pdf" class="form-control">
                                        {{-- @error('attachment')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn text-light px-5 mb-2" id="custom_btn">Submit</button>
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
