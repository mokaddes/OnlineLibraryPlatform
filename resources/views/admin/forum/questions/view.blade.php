@extends('admin.layouts.master')
@section('forumQuestions', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection
@push('style')
<style>
    input, select, textarea {
    border-radius: 10px !important;
}
.profile-user-img{
    border:none;
    margin: 0 auto;
    padding: 3px;
    width: 500px;
}
</style>
@endpush
@section('content')
<div class="content-wrapper mt-4" style="background: #FFFFFF;">
    <div class="content">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-4 offset-md-4">
                    <div class="card card-outline">
                        <div class="card-body box-profile">
                            {{-- <img class="profile-user-img"
                                    src="{{ asset($data['row']->getBlog->image ?? 'assets/images/default-user.png') }}"
                                     alt=""> --}}
                            <h3 class="profile-username text-center">{{ $data['row']->title }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="card-title">Asked - By {{ $data['row']->getUser->name }}
                                    </h3>
                                </div>
                                <div>
                                    <a href="{{ route('admin.forum.index') }}" class="btn btn-sm btn-light"
                                        style="border: 1px solid #F1F1F1">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! $data['row']->descriptions !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            @if($data['row']->status ==1)
                                            <a href="{{ route('admin.forum.updateForumStatus',['id'=>$data['row']->id]) }}" class="btn btn-sm btn-warning">Unpublish</a>
                                        @else
                                        <a href="{{ route('admin.forum.updateForumStatus',['id'=>$data['row']->id]) }}" class="btn btn-sm btn-success">Publish</a>
                                        @endif
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

