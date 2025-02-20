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
                                <h3 class="card-title">All Clubs</h3>
                            </div>
                            <div class="card-body p-0">
                                @for ($i = 0; $i <= 15; $i++)
                                    <div class="club_list">
                                        <div class="d-sm-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <a href="{{ route('admin.club.about') }}">
                                                        <img src="{{ asset('assets/images/2.jpg') }}" class="rounded"
                                                        width="50" height="50" alt="">
                                                    </a>
                                                </div>
                                                <div>
                                                    <h4 class="mb-0">
                                                        <a href="{{ route('admin.club.about') }}">Prompt Engineer Club for Bangladseh</a>
                                                    </h4>

                                                    <p>
                                                        Welcome to the Bangladesh community Club! We are so excited to have
                                                        you
                                                        with
                                                        us.
                                                        We will provide you with information about the community club and
                                                        the
                                                        community members....
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="pl-5 pl-sm-0 mt-2 mt-sm-0">
                                                <span class="shadow-sm p-2 rounded">12,444 Members</span>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="pagination_item mt-4 justify-content-center d-flex">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="club_sidebar">
                            <div class="my_club">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">My Clubs</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">
                                                <img src="{{ asset('assets/images/2.jpg') }}" class="rounded" width="40"
                                                    height="40" alt="">
                                            </div>
                                            <div>
                                                <h4 class="mb-0">
                                                    <a href="#">Prompt Engineer Club for Bangladseh</a>
                                                </h4>
                                                <span>10,1255 Members</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recent activity in my Clubs</h3>
                                </div>
                                <div class="card-body">

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
    </div>
@endsection
