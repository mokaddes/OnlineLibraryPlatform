@extends('admin.layouts.master')
@section('blogComments', 'active')
@section('blog_menu', 'active menu-open')
@section('title') {{ $data['title'] ?? '' }} @endsection
@push('style')
<style>
.status-badge {
    font-size: 0.3rem;
    font-weight: 300;
    padding: 0px 2px;
}
th {
    font-weight: normal !important;
}
.cursor {
    cursor: default !important;
}
</style>
@endpush
@section('content')
    <div class="content-wrapper mt-3" >
        <div class="content">
            <div class="container-fluid">
                <div class="card" >
                    <div class="card-header" >
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title" >All Blog Comments</h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th class="text-center">Serial No.</th>
                                <th>Blog</th>
                                <th class="text-center">Created At</th>
                                <th class="text-center">Comment By</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Engagement</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($data['rows'] as $key => $row)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td><a class="{{ $row->status == 1 ? 'text-green' : '' }}" href="{{route('frontend.blogs.details',['slug'=>$row->getBlog->slug])}}">{{$row->getBlog->title}}</a></td>
                                        <td class="text-center">{{$row->created_at}}</td>
                                        @php
                                            $role = ($row->getUser->role_id == 2) ? 'Author' : (($row->getUser->role_id == 3) ? 'Institution' : 'User');
                                         @endphp
                                        <td class="text-center"><a href="{{ route('admin.user.view',['id' => $row->getUser->id, 'role' => $role]) }}">{{$row->getUser->name}}</a></td>
                                        <td class="text-center">
                                            @if($row->status ==1)
                                            <a href="{{ route('admin.blog.comment.updateCommentStatus',['id'=>$row->id]) }}" class="btn btn-sm btn-warning">Unpublish</a>
                                        @else
                                        <a href="{{ route('admin.blog.comment.updateCommentStatus',['id'=>$row->id]) }}" class="btn btn-sm btn-success">Publish</a>
                                        @endif
                                        </td>
                                        <td class="text-center">
                                            <button title="Like" class="cursor btn btn-sm btn-outline-success"><i
                                                class="fa fa-thumbs-up"></i> <span class="badge badge-light">{{$row->like}}</span>
                                            </button>
                                            <button title="Dislike" class="cursor btn btn-sm btn-outline-danger"><i
                                                class="fa fa-thumbs-down"></i> <span class="badge badge-light">{{$row->dislike}}</span>
                                            </button>
                                            <button title="Reply" class="cursor btn btn-sm btn-outline-primary"><i
                                                class="fa fa-reply-all"></i> <span class="badge badge-light">{{$row->replyCount}}</span>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.blog.comment.view',['id' => $row->id]) }}" 
                                                class="btn btn-sm" style="background: #9C9C9C" title="View">
                                                <i class="far fa-eye" style="color: #ffffff;"></i>
                                            </a>
                                            <a href="{{ route('admin.blog.comment.delete', $row->id ) }}" title="Delete"
                                                onclick="return confirm('{{ __('Are you sure want to delete this item') }}')"
                                                class="btn btn-sm" style="background: #EC2626">
                                                <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push("script")
@endpush
