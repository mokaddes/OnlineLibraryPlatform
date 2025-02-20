@extends('admin.layouts.master')
@section('club', 'active')

@section('title') {{ $data['title'] ?? '' }} @endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $data['title'] ?? 'Page Header' }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row pb-5">
                    <div class="col-lg-8">
                        <div class="card club_wrapper">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="card-title">Prompt Engineer Club for Bangladseh</h3>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.club.add.question') }}"
                                            class="btn btn-success btn-sm">Start New Topic</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="club_wrap pt-4 pb-4"
                                    style="background-image: url('{{ asset('assets/images/banner.jpg') }}')">
                                    <div class="club_list border-0">
                                        <div class="d-sm-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center position-relative">
                                                <div class="mr-2">
                                                    <img src="{{ asset('assets/images/2.jpg') }}" class="rounded"
                                                        width="50" height="50" alt="">
                                                </div>
                                                <div>
                                                    <h4 class="mb-0">
                                                        <a href="javascript:void(0)" class="text-white">Prompt Engineer Club
                                                            for Bangladseh</a>
                                                    </h4>
                                                    <span>12,444 Members</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="post-comments p-3 mt-5">
                                    <div class="media">
                                        <div class="media-left">
                                            <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="image">
                                        </div>
                                        <div class="media-body">
                                            <div class="media-heading">
                                                <h4>Rabin</h4>
                                                <span class="time">5 min ago</span>
                                            </div>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor
                                                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                                nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                                consequat.
                                            </p>
                                            <a href="#" class="reply"> <i class="fa fa-reply-all"></i> Reply</a>
                                            <a href="#" class="reply like"> <i class="fa fa-thumbs-up"></i> Like</a>
                                            <a href="#" class="reply dislike"> <i class="fa fa-thumbs-down"></i>
                                                Dislike</a>


                                            <div class="media media-author">
                                                <div class="media-left">
                                                    <img src="{{ asset('assets/images/default-user.png') }}" alt="">
                                                </div>
                                                <div class="media-body">
                                                    <div class="media-heading">
                                                        <h4>Samantha</h4>
                                                        <span class="time">5 min ago</span>
                                                    </div>
                                                    <p>
                                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                                        eiusmod
                                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                                                        veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
                                                        ea
                                                        commodo consequat.

                                                    </p>
                                                    <a href="#" class="reply"> <i class="fa fa-reply-all"></i>
                                                        Reply</a>
                                                    <a href="#" class="reply like"> <i class="fa fa-thumbs-up"></i>
                                                        Like</a>
                                                    <a href="#" class="reply dislike"> <i
                                                            class="fa fa-thumbs-down"></i>
                                                        Dislike</a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="media">
                                        <div class="media-left">
                                            <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="media-heading">
                                                <h4>Shakib</h4>
                                                <span class="time">5 min ago</span>
                                            </div>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor
                                                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                                nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                                consequat.
                                            </p>
                                            <a href="#" class="reply"> <i class="fa fa-reply-all"></i> Reply</a>
                                            <a href="#" class="reply like"> <i class="fa fa-thumbs-up"></i> Like</a>
                                            <a href="#" class="reply dislike"> <i class="fa fa-thumbs-down"></i>
                                                Dislike</a>
                                        </div>
                                    </div>
                                    <form action="#" class="mt-5" method="post">
                                        <div class="form-group">
                                            <textarea name="message" id="message" cols="30" rows="10" class="form-control summernote" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Send</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="club_sidebar">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">12,5487 Club Members</h3>
                                </div>
                                <div class="card-body all_members">
                                    {{-- recent activity --}}
                                    @for ($i = 0; $i < 10; $i++)
                                        <div class="recent_activity position-relative">
                                            <div class="d-flex">
                                                <div class="mr-2">
                                                    <img src="{{ asset('assets/images/user.jpg') }}" class="rounded"
                                                        width="40" height="40" alt="">
                                                </div>
                                                <div>
                                                    <h4 class="mb-1">
                                                        <a href="#" class="stretched-link">John Doe</a>
                                                    </h4>
                                                    <p class="mb-0">
                                                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Inventore
                                                        expedita nihil aspernatur alias architecto ex reiciendis ea facere
                                                        earum...
                                                    </p>
                                                    <div class="date">
                                                        <span class="mr-3">
                                                            <i class="fa fa-clock"></i>
                                                            <span>2 hours ago</span>
                                                        </span>
                                                        <span>
                                                            <i class="fa fa-comment-alt"></i>
                                                            <span>2 replies</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    @endfor
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
@endpush
