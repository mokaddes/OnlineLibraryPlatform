@extends('admin.layouts.master')
@section('forumComments', 'active')
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
<div class="content-wrapper mt-4" >
    <div class="content">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-4 offset-md-4">
                    <div class="card card-outline">
                        <div class="card-body box-profile">
                            <img class="profile-user-img img-circle"
                                    src="{{ asset($data['row']->getUser->image ?? 'assets/images/default-user.png') }}" style="width:100px; height:100px; display:block;"
                                     alt="">
                            <h3 class="profile-username text-center">{{ $data['row']->getUser->title }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="card-title"><b>Forum Question:</b> {{ $data['row']->getForum->title }} , Comment - By {{ $data['row']->getUser->name }}
                                    </h3>
                                </div>
                                <div>
                                    <a href="{{ route('admin.forum.comment.index') }}" class="btn btn-sm btn-light"
                                        style="border: 1px solid #F1F1F1">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                    {{-- <div class="col-md-12">
                                        @if ($data['row']->comment_parent_id != 0)
                                        @php
                                        $parentId = $data['row']->comment_parent_id;
                                           $v = $data['row']->select('comments')->where('id',$parentId)->first();
                                        @endphp
                                        <div class="form-group">
                                        <p> <b>Parent Comment:</b> {{ $v['comments'] }} </p>
                                        <p> <b>Reply:</b> {{ $data['row']->comments }}</p>
                                        </div>
                                        @else
                                        <div class="form-group">
                                        <p><b>Parent Comment:</b> {{ $data['row']->comments }}</p>
                                        </div>
                                        @endif
                                    </div> --}}
                                    <div class="col-md-12">
                                        <p><b>Comment:</b> {{ $data['row']->comments }}</p>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            @if($data['row']->status ==1)
                                            <a href="{{ route('admin.forum.comment.updateForumCommentStatus',['id'=>$data['row']->id]) }}" class="btn btn-sm btn-warning">Unpublish</a>
                                        @else
                                        <a href="{{ route('admin.forum.comment.updateForumCommentStatus',['id'=>$data['row']->id]) }}" class="btn btn-sm btn-success">Publish</a>
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

