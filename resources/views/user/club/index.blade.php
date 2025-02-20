@extends('admin.layouts.user')
@section('club', 'active')
@section('title') {{ $title ?? '' }} @endsection
@push('style')
    <style>
        p {
            margin-bottom: 0px;
        }
        .card-header{
            color: #495057;
            font-size: 18px;
            border-bottom: 1px solid #ebeefc91!important; 
            background: #99009926;
        }
        .club-header{
            background-color: transparent;
            border-bottom: 1px solid rgba(0,0,0,.125);
            padding: 0.75rem 1.25rem;
        }
        #col-img {
            width: 50px;
            border-radius: 50px;
        }

        #col-div {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #card-title {
            color: #000000;
            font-weight: 500;
            font-size: 18px;
        }

        #card-subtitle {
            font-size: 13px;
            color: #777777;
            margin-bottom: 10px;
        }

        #card-text {
            font-size: 14px;
            color: #777777;
        }

        #card {
            border-radius: 12px;
            border: 1px solid #F0F0F0;
            box-shadow: none;
        }

        #suggestion-text {
            font-size: 12px;
            color: #777777;
        }

    </style>
@endpush
@section('content')
@php
    $user_id = auth()->user()->id;
@endphp
    <div class="content-wrapper pb-5">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="mb-0">Club Directory</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a href="{{route('user.club.create')}}" class="btn btn-primary" id="custom_btn">Create Club</a>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-7 col-lg-8">
                        <div class="card" id="card">
                            <div class="card-header">
                               <strong>All clubs</strong>
                            </div>
                            <div class="club-header d-flex justify-content-between">
                                <div>
                                    {{ $rows->links() }}
                                </div>
                                <div>
                                    <a class="btn btn-sm btn-success dropdown-toggle" href="#" role="button" 
                                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        SORT BY
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="{{route('user.club.index', ['sort_by'=>'latest'])}}">Latest Created</a>
                                        <a class="dropdown-item" href="{{route('user.club.index', ['sort_by'=>'oldest'])}}">Oldest Club</a>
                                        <a class="dropdown-item" href="{{route('user.club.index', ['sort_by'=>'highest_member'])}}">Most Members</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body bg-white card-comments">
                                @forelse ($rows as $key => $row)
                                    <div class="card-comment pt-3 pb-3">
                                        <a href="{{ route('user.club.joinclub', $row->id) }}">
                                            <div class="self-align-center">
                                            <img class="img-circle" src="{{ asset($row->profile_photo ?? 'assets\default.png' ) }}"
                                                alt="User Image">
                                            </div>
                                            <div class="comment-text">
                                                <span class="username">
                                                    {{$row->title}}
                                                    <span class="text-muted float-right"><strong>{{$row->members_count}} Members</strong></span>
                                                </span>
                                                <p>
                                                {{$row->short_description}}
                                                </p>
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                <p class="text-center"> No available club </p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-4">
                        <div class="card" id="card">
                            <div class="card-header">
                               <strong>My clubs</strong>
                            </div>
                            <div class="card-body">
                                @forelse ($clubs as $key => $club)
                                    @if ($club->status == 1)
                                    <a href="{{ route('user.club.joinclub', $club->id) }}">
                                        <div class="post clearfix">
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                    src="{{ asset($club->profile_photo ?? 'assets\default.png') }}"
                                                    alt="User Image">
                                                <span class="username">
                                                    <span>{{$club->title}} @if($club->user_id == $user_id) &nbsp;<span class="badge badge-info">Owner</span> @endif</span>
                                                </span>
                                                <span class="description">{{$club->members_count}} Members</span>
                                            </div>
                                        </div>
                                    </a>
                                    @elseif($club->status == 0 && $club->user_id == $user_id)
                                    <a href="javascript:void(0)" id="pending_club">
                                        <div class="post clearfix">
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                    src="{{ asset($club->profile_photo ?? 'assets\default.png') }}"
                                                    alt="User Image">
                                                <span class="username">
                                                    <span>{{$club->title}} <span class="badge badge-warning">Pending</span> </span>
                                                </span>
                                                <span class="description">0 Members</span>
                                            </div>
                                        </div>
                                    </a>
                                    @endif
                                @empty
                                    <p class="text-center"> No clubs joined yet </p>
                                @endforelse
                            </div>
                        </div>
                        <div class="card" id="card">
                            <div class="card-header">
                                <strong>Recent activity in my clubs @if($posts_count > 0) &nbsp;<span style="color:#990099;">({{$posts_count}})</span> @endif</strong>
                             </div>
                            <div class="card-body">
                                @forelse ($posts as $key => $post)
                                    <a href="{{ route('user.club.question', $post->id) }}">
                                        <div class="post clearfix">
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                    src="{{ getProfile($post->user->image) }}"
                                                    alt="User Image">
                                                <span class="username">
                                                    <span>{{ $post->user->name }}&nbsp;{{ $post->user->last_name }} </span>
                                                </span>
                                                <span class="description">Shared - {{ \Carbon\Carbon::parse($post->created_at)->format('g:i A j F Y') }} 
                                                    <span class="float-right" style="color:#990099;">{{ $post->club->title }}</span>
                                                </span>
                                            </div>
                                            <p>{{ $post->title }}</p>
                                        </div>
                                    </a>
                                @empty
                                    No available activities
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
<script>
    document.getElementById('pending_club').addEventListener('click', function(event) {
        event.preventDefault();
        toastr.warning('Please wait for admin approval to visit the club');
    });
</script>
@endpush
