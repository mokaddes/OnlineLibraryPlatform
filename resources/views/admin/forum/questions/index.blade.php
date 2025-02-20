@extends('admin.layouts.master')
@section('forumQuestions', 'active')
@section('forum_menu', 'active menu-open')
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

</style>
@endpush
@section('content')
    <div class="content-wrapper mt-3" style="background: #FFFFFF;">
        <div class="content">
            <div class="container-fluid">
                <div class="card" style="border: 1px solid#E6EDFF;">
                    <div class="card-header" style="border-bottom:none !important; background: #ebeefc91;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title" style="font-size: 1.3rem;">All Forum Questions</h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th class="text-center">Serial No.</th>
                                <th>Title</th>
                                <th class="text-center">Created At</th>
                                <th class="text-center">Asked By</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($data['rows'] as $key => $row)
                                    <tr>
                                        <td class="text-center" style="width: 10%">{{$key+1}}</td>
                                        <td><a class="{{ $row->status == 1 ? 'text-green' : '' }}" href="{{route('frontend.forum.details',['slug'=>$row->slug])}}">{{$row->title}}</a></td>
                                        <td class="text-center">{{$row->created_at}}</td>
                                        @php
                                            $role = ($row->getUser->role_id == 2) ? 'Author' : (($row->getUser->role_id == 3) ? 'Institution' : 'User');
                                         @endphp
                                        <td class="text-center"><a href="{{ route('admin.user.view',['id' => $row->getUser->id, 'role' => $role]) }}">{{$row->getUser->name}}</a></td>
                                        <td class="text-center">
                                            @if($row->status ==1)
                                            <a href="{{ route('admin.forum.updateForumStatus',['id'=>$row->id]) }}" class="btn btn-sm btn-warning">Unpublish</a>
                                        @else
                                        <a href="{{ route('admin.forum.updateForumStatus',['id'=>$row->id]) }}" class="btn btn-sm btn-success">Publish</a>
                                        @endif
                                        </td>

                                        <td class="text-center">
                                            <a href="{{ route('admin.forum.view',['id' => $row->id]) }}" 
                                                class="btn btn-sm" style="background: #9C9C9C" title="View">
                                                <i class="far fa-eye" style="color: #ffffff;"></i>
                                            </a>
                                            <a href="{{ route('admin.forum.delete', $row->id ) }}" title="Delete"
                                                onclick="return confirm('{{ __('Are you sure want to delete this item') }}')"
                                                class="btn btn-sm" style="background: #EC2626">
                                                <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                            </a>
                                            <span class="badge badge-primary">{{ $row->get_comment_count }}</span>
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
