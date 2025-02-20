@extends('admin.layouts.master')
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
    <div class="content-wrapper pb-5">
        {{-- <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Club Details</h1>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-7 col-lg-8">
                        <div class="card card-widget widget-user">
                            <div class="widget-user-header text-white position-relative"
                                style="background: url('{{ asset($row->covar_photo)}}') center center;">
                                <div class="club_group text-right mt-4">
                                    <h3 class="widget-user-desc text-right mb-0">{{$row->title}}</h3>
                                    <span class="text-right d-block">{{$member_count}} Members</span>
                                </div>
                            </div>
                            <div class="widget-user-image club_group">
                                <img class="img-circle" src="{{ asset($row->profile_photo ?? 'assets\default.png') }}"
                                    alt="User Avatar">
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="{{ route('admin.club.view', $club_id) }}" class="nav-link">Overview</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="{{ route('admin.club.clubMembers', $club_id) }}"
                                            class="nav-link active">Members</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div>
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
