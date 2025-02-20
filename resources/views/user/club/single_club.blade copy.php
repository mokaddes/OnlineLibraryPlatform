@extends('admin.layouts.user')
@section('club', 'active')
@section('title') {{ $title ?? '' }} @endsection
@push('style')
    <style>
        p {
            margin-bottom: 0px;
        }

        #col-div {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .club-header{
            background-color: transparent;
            border-bottom: 1px solid rgba(0,0,0,.125);
            padding: 0.75rem 1.25rem;
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

        .widget-user .widget-user-image>img {
            height: 90px;
        }

        .nav-tabs .nav-link {
            padding: 6px 18px;
            font-size: 16px;
            font-family: 'Rubik', sans-serif;
            font-weight: 400;
            color: #323232 !important;
        }

        .nav-tabs .nav-item.show .nav-link,
        .nav-tabs .nav-link.active {
            color: #fff !important;
            background-color: #800080;
            border-color: #dee2e6 #dee2e6 #fff;
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
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Club Details</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-7 col-lg-8">
                        <div class="card card-widget widget-user">
                            <div class="widget-user-header text-white position-relative"
                                style="background: url('{{ asset($row->covar_photo)}}') center center;">
                                <div class="club_group text-right @if($row->user_id == $user_id) mt-4 @endif">
                                    <h3 class="widget-user-desc text-right mb-0">{{$row->title}}</h3>
                                    <span class="text-right d-block mb-2">{{$member_count}} Members</span>
                                    @if($row->user_id != $user_id)
                                        <form action="{{ $flag == 2 || $flag == 3 ? route('user.club.leave') : route('user.club.joinclub.submit') }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user_id }}">
                                            <input type="hidden" name="club_id" value="{{ $row->id }}">
                                            @if($flag == 3)
                                            <input type="hidden" name="join_request" value="1">
                                            @endif
                                            <button type="submit" class="text-right btn btn-primary" id="custom_btn">
                                                @if($flag == 3)
                                                    <i class="fas fa-times"></i> Cancel Join Request
                                                @elseif($flag == 2)
                                                    Leave Club
                                                @else
                                                    Join Club
                                                @endif
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="widget-user-image club_group">
                                <img class="img-circle" src="{{ asset($row->profile_photo ?? 'assets\default.png') }}"
                                    alt="User Avatar">
                            </div>
                            <div class="card-body">

                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home"
                                            type="button" role="tab" aria-controls="home"
                                            aria-selected="true">Overview</button>
                                    </li>
                                    @if($flag == 2)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile"
                                                type="button" role="tab" aria-controls="profile"
                                                aria-selected="false">Discussion</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact"
                                                type="button" role="tab" aria-controls="contact"
                                                aria-selected="false">Members</button>
                                        </li>
                                    @endif
                                    @if($row->user_id == $user_id)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="setting-tab" data-toggle="tab" data-target="#setting"
                                                type="button" role="tab" aria-controls="setting"
                                                aria-selected="false">Settings</button>
                                        </li>
                                    @endif
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                        aria-labelledby="home-tab">
                                        <div class="card-body">
                                            <div class="tab-content mb-4">
                                                <h6>About Club</h6>
                                                <div class="text-sm mb-4">
                                                    {!! $row->about_club !!}
                                                </div>
                                            </div>
                                            <div class="tab-content">
                                                <h6>Rules</h6>
                                                <div class="text-sm mb-4">
                                                    {!! $row->rules_club !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <div class="club-header d-flex align-items-center justify-content-between">
                                            <div>{{ $posts->links() }}</div>
                                            <a href="{{route('user.club.question.ask', $row->id)}}" class="btn text-light" id="custom_btn">Start new topic</a>
                                        </div>
                                        <div class="card-body">
                                            @forelse ($posts as $key => $post)
                                                <a href="{{ route('user.club.question', $post->id) }}">
                                                    <div class="post clearfix">
                                                        <div class="user-block">
                                                            <img class="img-circle img-bordered-sm"
                                                                src="{{ getProfile( $post->user->image ) }}"
                                                                alt="{{ $post->user->name }}">
                                                            <span class="username">
                                                                <span>{{ $post->user->name }}&nbsp;{{ $post->user->last_name }}</span>
                                                            </span>
                                                            <span class="description">Shared - {{ \Carbon\Carbon::parse($post->created_at)->format('g:i A j F Y') }}</span>
                                                        </div>
                                                        <p>{{ $post->title }}</p>
                                                    </div>
                                                </a> 
                                            @empty
                                                <p class="text-center"> No available question </p>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="contact" role="tabpanel"
                                        aria-labelledby="contact-tab">
                                        <div class="">
                                            @if($row->user_id == $user_id)
                                                <div class="club-header pt-4 card-comments">
                                                    <div class="row">
                                                        <h6 class="text-success mb-3">Membership Join Requests</h6>
                                                    </div>
                                                    @forelse ($pending_members as $key => $pending_member)
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div>
                                                                <img class="img-circle img-sm" src="{{ getProfile($pending_member->user->image) }}"
                                                                alt="User Image">
                                                                <div class="comment-text">
                                                                    <span class="username mb-0">
                                                                        <h6 class="mb-0">
                                                                            {{ $pending_member->user->name }}&nbsp;{{ $pending_member->user->last_name }}
                                                                            @if ($pending_member->user_id == $row->user_id)
                                                                                &nbsp;<span class="badge badge-info">Owner</span>
                                                                            @endif
                                                                        </h6>
                                                                        <span
                                                                            class="text-muted">{{ \Carbon\Carbon::parse($pending_member->created_at)->format('g:i A j F Y') }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <a href="{{ route('user.club.status.change', ['id' => $pending_member->id, 'status' => '1']) }}"
                                                                    class="btn btn-sm btn-success mb-2" style="width: 100px">Approve</a>
                                                                <a href="{{ route('user.club.status.change', ['id' => $pending_member->id, 'status' => '3']) }}"
                                                                    class="btn btn-sm btn-danger mb-2" style="width: 100px">Deny</a>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <p class="text-center"> No join requests are currently available. </p>
                                                    @endforelse
                                                    {{ $pending_members->links() }}
                                                </div>
                                            @endif
                                            <div class="card-footer pt-4 card-comments">
                                                <div class="row">
                                                    <h6 class="text-info mb-3">All Members</h6>
                                                </div>
                                                @forelse ($members as $key => $member)
                                                    <div class="card-comment">
                                                        <img class="img-circle img-sm" src="{{ getProfile($member->user->image) }}"
                                                            alt="User Image">
                                                        <div class="comment-text">
                                                            <span class="username mb-0">
                                                                <h6 class="mb-0">
                                                                    {{ $member->user->name }}&nbsp;{{ $member->user->last_name }}
                                                                    @if ($member->user_id == $row->user_id)
                                                                        &nbsp;<span class="badge badge-info">Owner</span>
                                                                    @endif
                                                                </h6>
                                                                <span
                                                                    class="text-muted">{{ \Carbon\Carbon::parse($member->created_at)->format('g:i A j F Y') }}</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-center"> No available member </p>
                                                @endforelse
                                                {{ $members->links() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="setting" role="tabpanel"
                                        aria-labelledby="setting-tab">
                                        <div class="card-body">
                                            <form action="{{route('user.club.update', $row->id)}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="title" class="form-label">Name</label>
                                                            <input type="text" name="title" value="{{ $row->title }}" id="title" class="form-control" placeholder="Club Name">
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
                                                            required style="height:100px !important;">{!! $row->short_description !!}</textarea>
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="about" class="form-lable">About</label>
                                                            <textarea name="about" cols="30" rows="5" class="form-control summernote">{!! $row->about_club !!}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="rules" class="form-lable">Rules</label>
                                                            <textarea name="rules" cols="30" rows="5" class="form-control summernote">{!! $row->rules_club !!}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                    
                                                <div class="row mt-4">
                                                    <div class="col-md-12 text-center">
                                                        <button type="submit" class="btn text-light px-5" id="custom_btn">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-4">
                        <div class="card" id="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ $member_count }} Members</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="card-footer card-comments">
                                    @forelse ($members as $key => $member)
                                        <div class="card-comment">
                                            <img class="img-circle img-sm" src="{{ getProfile($member->user->image) }}"
                                                alt="User Image">
                                            <div class="comment-text">
                                                <span class="username mb-0">
                                                    <h6 class="mb-0">
                                                        {{ $member->user->name }}&nbsp;{{ $member->user->last_name }}
                                                        @if ($member->user_id == $row->user_id)
                                                            &nbsp;<span class="badge badge-info">Owner</span>
                                                        @endif
                                                    </h6>
                                                    <span
                                                        class="text-muted">{{ \Carbon\Carbon::parse($member->created_at)->format('g:i A j F Y') }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center"> No available member </p>
                                    @endforelse
                                </div>
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
    $(document).ready(function () {
        // Retrieve the active tab from localStorage
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            // Show the previously active tab
            $('#myTab a[href="#' + activeTab + '"]').tab('show');
        }

        // Store the active tab in localStorage when a tab is clicked
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var targetTab = $(e.target).attr("href").substring(1);
            localStorage.setItem('activeTab', targetTab);
        });
    });
</script>
@endpush
